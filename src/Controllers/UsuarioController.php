<?php
namespace Htdocs\Src\Controllers;

use Htdocs\Src\Services\UsuarioService;

class UsuarioController {
    private $service;

    public function __construct(UsuarioService $service) {
        $this->service = $service;
    }

    public function criar() {
        $data = json_decode(file_get_contents("php://input"));
        if (!$data) $data = (object)$_POST;

        // Verifica se os campos esperados estão presentes
        if (!isset($data->nome_usuario) || !isset($data->email_usuario) || !isset($data->senha_usuario)) {
            echo json_encode(['error' => 'Dados incompletos.']);
            return;
        }

        $usuario = new \Htdocs\Src\Models\Entity\Usuario(
            null,
            $data->nome_usuario,
            $data->email_usuario,
            password_hash($data->senha_usuario, PASSWORD_DEFAULT)
        );
        $this->service->criar($usuario);

        // Se for requisição POST tradicional (formulário), redireciona para login
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Location: /usuario/login');
            exit;
        }

        // Caso contrário, retorna JSON (para AJAX)
        echo json_encode(['success' => true]);
    }

    public function mostrar() {
        $usuarios = $this->service->listar();
        echo json_encode($usuarios);
    }
    public function mostrarFormulario(){
        $formPath = dirname(__DIR__, 2) . '/view/usuario/form.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Formulário não encontrado em $formPath";
        }
    }
    public function mostrarHome(){
        $formPath = dirname(__DIR__, 2) . '/view/usuario/index.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Início não encontrado em $formPath";
        }
    }
    public function mostrarLogin(){
        $formPath = dirname(__DIR__, 2) . '/view/usuario/login.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Login não encontrado em $formPath";
        }
    }
    public function entrar() {
        // Garantir que não há output antes dos headers
        if (ob_get_level()) {
            ob_clean();
        }
        
        // Inicializar sessão se não estiver ativa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Log para debug
        error_log("UsuarioController: Método entrar() chamado");
        error_log("UsuarioController: POST data: " . print_r($_POST, true));

        $email = $_POST['email_usuario'] ?? null;
        $senha = $_POST['senha_usuario'] ?? null;

        // Verificar se é requisição AJAX
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

        // Verifica se os campos esperados estão presentes
        if (!$email || !$senha) {
            error_log("UsuarioController: Campos vazios - Email: $email, Senha: " . ($senha ? 'presente' : 'vazio'));
            
            if ($isAjax) {
                http_response_code(400);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'error' => 'Por favor, preencha todos os campos.']);
                exit;
            }
            header('Location: /usuario/login?error=missing_data');
            exit;
        }

        try {
            error_log("UsuarioController: Tentando fazer login para email: $email");
            
            $usuario = $this->service->login($email, $senha);
            error_log("UsuarioController: Resultado do login: " . ($usuario ? 'sucesso' : 'falhou'));

            if ($usuario) {
                // Define dados básicos do usuário na sessão
                $_SESSION['usuario'] = [
                    'id_usuario' => $usuario['id_usuario'],
                    'id' => $usuario['id_usuario'], // Compatibilidade
                    'nome_usuario' => $usuario['nome_usuario'],
                    'email_usuario' => $usuario['email_usuario'],
                    'status_usuario' => $usuario['status_usuario'] ?? 1,
                    'tipo' => 'usuario' // Tipo padrão
                ];

                error_log("UsuarioController: Sessão criada para usuário ID: " . $usuario['id_usuario']);

                // Se for requisição AJAX
                if ($isAjax) {
                    http_response_code(200);
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode([
                        'success' => true, 
                        'redirect' => '/usuario',
                        'usuario' => [
                            'id' => $_SESSION['usuario']['id_usuario'],
                            'nome' => $_SESSION['usuario']['nome_usuario'],
                            'email' => $_SESSION['usuario']['email_usuario'],
                            'tipo' => $_SESSION['usuario']['tipo']
                        ]
                    ]);
                    exit;
                }

                // Redirecionamento para formulário tradicional
                header('Location: /usuario');
                exit;

            } else {
                // Falha na autenticação
                error_log("UsuarioController: Login falhou para email: $email");
                
                if ($isAjax) {
                    http_response_code(401);
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(['success' => false, 'error' => 'Email ou senha incorretos.']);
                    exit;
                }
                header('Location: /usuario/login?error=invalid_credentials');
                exit;
            }
        } catch (\Exception $e) {
            error_log("UsuarioController: Erro no login: " . $e->getMessage());
            error_log("UsuarioController: Stack trace: " . $e->getTraceAsString());
            
            if ($isAjax) {
                http_response_code(500);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'error' => 'Erro interno do servidor. Tente novamente.']);
                exit;
            }
            header('Location: /usuario/login?error=server_error');
            exit;
        }
    }

    public function mostrarConta() {
        if (!isset($_SESSION['usuario'])) {
            header('Location: /usuario/login');
            exit;
        }
        $formPath = dirname(__DIR__, 2) . '/view/usuario/conta.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Conta não encontrada em $formPath";
        }
    }

    public function atualizarConta() {
        if (!isset($_SESSION['usuario'])) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                http_response_code(401);
                echo json_encode(['error' => 'Usuário não está logado.']);
                return;
            }
            header('Location: /usuario/login');
            exit;
        }
        
        $id = $_SESSION['usuario']['id_usuario'] ?? $_SESSION['usuario']['id'] ?? null;
        $nome = $_POST['nome_usuario'] ?? '';
        $email = $_POST['email_usuario'] ?? '';
        $senha = $_POST['senha_usuario'] ?? null;

        if ($id && $nome && $email) {
            try {
                $usuario = new \Htdocs\Src\Models\Entity\Usuario($id, $nome, $email, $senha ? password_hash($senha, PASSWORD_DEFAULT) : null);
                $this->service->getUsuarioRepository()->update($usuario);
                
                // Atualiza sessão
                $_SESSION['usuario']['nome_usuario'] = $nome;
                $_SESSION['usuario']['email_usuario'] = $email;
                
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                    echo json_encode(['success' => true]);
                    return;
                }
                
                header('Location: /conta?atualizado=1');
                exit;
            } catch (\Exception $e) {
                error_log("Erro ao atualizar conta: " . $e->getMessage());
                
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                    echo json_encode(['error' => 'Erro ao atualizar dados: ' . $e->getMessage()]);
                    return;
                }
            }
        }
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode(['error' => 'Dados incompletos.']);
            return;
        }
        
        header('Location: /conta?erro=1');
        exit;
    }

    public function deletarConta() {
        if (!isset($_SESSION['usuario'])) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                http_response_code(401);
                echo json_encode(['error' => 'Usuário não está logado.']);
                return;
            }
            header('Location: /usuario/login');
            exit;
        }
        
        $id = $_SESSION['usuario']['id_usuario'] ?? $_SESSION['usuario']['id'] ?? null;
        
        if ($id) {
            try {
                $this->service->getUsuarioRepository()->delete($id);
                session_destroy();
                
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                    echo json_encode(['success' => true, 'redirect' => '/delimeter']);
                    return;
                }
                
                header('Location: /delimeter');
                exit;
            } catch (\Exception $e) {
                error_log("Erro ao deletar conta: " . $e->getMessage());
                
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                    echo json_encode(['error' => 'Erro ao excluir conta: ' . $e->getMessage()]);
                    return;
                }
            }
        }
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode(['error' => 'ID do usuário não encontrado.']);
            return;
        }
        
        header('Location: /conta?erro=1');
        exit;
    }

    public function sairConta() {
        session_destroy();
        // Compatível com rota genérica
        header('Location: /delimeter');
        exit;
    }
}
?>