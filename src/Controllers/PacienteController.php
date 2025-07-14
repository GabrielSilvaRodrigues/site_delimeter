<?php
namespace Htdocs\Src\Controllers;

use Htdocs\Src\Services\PacienteService;

class PacienteController {
    private $service;

    public function __construct(PacienteService $service) {
        $this->service = $service;
    }

    public function criar() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_usuario = $_SESSION['usuario']['id_usuario'] ?? $_SESSION['usuario']['id'] ?? null;
        $cpf = $data['cpf'] ?? $data['cpf_paciente'] ?? null;
        $nis = $data['nis'] ?? $data['nis_paciente'] ?? null;

        if (!$id_usuario || !$cpf) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados incompletos para vincular paciente.']);
            return;
        }

        // Converter id_usuario para int se for string
        $id_usuario = is_string($id_usuario) ? (int)$id_usuario : $id_usuario;

        // Verificar se o usuário já possui cadastro de paciente
        $pacienteExistente = $this->service->getPacienteRepository()->findByUsuarioId($id_usuario);
        if ($pacienteExistente) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['error' => 'Este usuário já possui um cadastro de paciente.']);
                return;
            }
            header('Location: /paciente');
            exit;
        }

        $paciente = new \Htdocs\Src\Models\Entity\Paciente(
            null, // id_paciente será gerado pelo banco
            $id_usuario,
            $cpf,
            $nis
        );

        $result = $this->service->criar($paciente);

        if (is_array($result) && isset($result['error'])) {
            http_response_code(400);
            echo json_encode(['error' => $result['error']]);
            return;
        }

        // LOGIN AUTOMÁTICO APÓS CADASTRO
        $_SESSION['usuario']['tipo'] = 'paciente';
        $_SESSION['usuario']['cpf'] = $cpf;
        $_SESSION['usuario']['nis'] = $nis;
        
        // Carregar os dados completos do paciente e salvar na sessão
        $pacienteData = $this->service->getPacienteRepository()->findByUsuarioId($id_usuario);
        if ($pacienteData) {
            $_SESSION['paciente'] = $pacienteData;
        }

        // Para requisições AJAX/JSON
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode(['success' => true, 'redirect' => '/paciente']);
            return;
        }

        // Para requisições normais via formulário
        header('Location: /paciente');
        exit;
    }

    public function mostrarFormulario(){
        $formPath = dirname(__DIR__, 2) . '/view/paciente/form.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            http_response_code(404);
            echo "Erro: Formulário não encontrado";
        }
    }

    public function mostrarHome(){
        $id_usuario = $_SESSION['usuario']['id_usuario'] ?? $_SESSION['usuario']['id'] ?? $_SESSION['usuario_id'] ?? null;
        if (!$id_usuario) {
            header('Location: /usuario/login');
            exit;
        }
        
        $paciente = $this->service->getPacienteRepository()->findByUsuarioId($id_usuario);
        if (!$paciente) {
            header('Location: /paciente/cadastro');
            exit;
        }
        
        $_SESSION['usuario']['tipo'] = 'paciente';
        $_SESSION['paciente'] = $paciente;
        
        $formPath = dirname(__DIR__, 2) . '/view/paciente/index.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            http_response_code(404);
            echo "Erro: Página inicial não encontrada";
        }
    }

    public function mostrarLogin(){
       try {
            header('Location: /usuario');
            exit;
        } catch (\Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

    public function mostrarConta(){
        $id = $_SESSION['usuario']['id_usuario'] ?? $_SESSION['usuario']['id'] ?? null;
        if (!$id) {
            echo json_encode(["error" => "Usuário não está logado."]);
            return;
        }
        echo json_encode($this->service->mostrarConta($id));
    }

    public function atualizarConta() {
        // Aceita tanto JSON quanto POST tradicional
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_usuario = $_SESSION['usuario']['id_usuario'] ?? $_SESSION['usuario']['id'] ?? null;
        $cpf = $data['cpf'] ?? null;
        $nis = $data['nis'] ?? null;

        if (!$id_usuario || !$cpf) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados incompletos para atualizar paciente.']);
            return;
        }

        // Buscar o paciente existente para obter o id_paciente
        $pacienteExistente = $this->service->getPacienteRepository()->findByUsuarioId($id_usuario);
        $id_paciente = $pacienteExistente ? $pacienteExistente['id_paciente'] : null;

        $paciente = new \Htdocs\Src\Models\Entity\Paciente(
            $id_paciente,
            (int)$id_usuario,
            $cpf,
            $nis
        );

        $result = $this->service->atualizarConta($paciente);

        if (is_array($result) && isset($result['error'])) {
            http_response_code(400);
            echo json_encode(['error' => $result['error']]);
            return;
        }

        // Atualiza sessão com os novos dados
        $_SESSION['usuario']['cpf'] = $cpf;
        $_SESSION['usuario']['nis'] = $nis;

        // Para requisições AJAX/JSON
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode(['success' => true]);
            return;
        }

        // Para requisições normais via formulário
        header('Location: /conta');
        exit;
    }

    public function deletarConta() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = $_SESSION['usuario']['id_usuario'] ?? $_SESSION['usuario']['id'] ?? null;
        if (!$id) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                http_response_code(400);
                echo json_encode(["error" => "ID do usuário não fornecido."]);
                return;
            }
            header('Location: /usuario/login');
            exit;
        }

        try {
            $result = $this->service->deletarConta($id);
            
            // Limpar dados do paciente da sessão
            $_SESSION['usuario']['tipo'] = 'usuario';
            unset($_SESSION['paciente']);
            unset($_SESSION['dados_antropometricos']);
            unset($_SESSION['usuario']['cpf']);
            unset($_SESSION['usuario']['nis']);
            
            // Para requisições AJAX/JSON
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['success' => true, 'redirect' => '/usuario']);
                return;
            }

            // Para requisições normais
            header('Location: /usuario');
            exit;
            
        } catch (\Exception $e) {
            error_log("Erro ao deletar conta do paciente: " . $e->getMessage());
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['error' => 'Erro ao excluir perfil: ' . $e->getMessage()]);
                return;
            }
            
            header('Location: /conta?erro=1');
            exit;
        }
    }

    public function sairConta() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Limpar apenas dados específicos do paciente, mantendo o usuário logado
        $_SESSION['usuario']['tipo'] = 'usuario';
        unset($_SESSION['paciente']);
        unset($_SESSION['dados_antropometricos']);
        
        // Remover dados específicos do paciente da sessão do usuário
        if (isset($_SESSION['usuario']['cpf'])) unset($_SESSION['usuario']['cpf']);
        if (isset($_SESSION['usuario']['nis'])) unset($_SESSION['usuario']['nis']);
        
        // Para requisições AJAX/JSON
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode([
                "success" => true, 
                "message" => "Saiu do perfil de paciente com sucesso.",
                'redirect' => '/usuario'
            ]);
            return;
        }

        // Para requisições normais
        header('Location: /usuario');
        exit;
    }

    public function procurarPorID() {
        $id_usuario = $_SESSION['usuario']['id_usuario'] ?? $_SESSION['usuario']['id'] ?? null;
        if (!$id_usuario) {
            echo json_encode(['error' => 'Usuário não está logado.']);
            return;
        }
        $paciente = $this->service->getPacienteRepository()->findByUsuarioId($id_usuario);
        if ($paciente) {
            echo json_encode($paciente);
            $_SESSION['usuario']['tipo'] = 'paciente';
            $_SESSION['paciente'] = $paciente; // Salvar dados do paciente na sessão
            header('Location: /paciente');
            exit;
        } else {
            header('Location: /paciente/cadastro');
            exit;
        }
    }

    /**
     * Método auxiliar para garantir que os dados do paciente estejam na sessão
     */
    private function garantirDadosPacienteNaSessao() {
        $id_usuario = $_SESSION['usuario']['id_usuario'] ?? $_SESSION['usuario']['id'] ?? null;
        
        if (!$id_usuario) {
            error_log("PacienteController: ID do usuário não encontrado na sessão");
            error_log("PacienteController: Conteúdo da sessão: " . print_r($_SESSION, true));
            return false;
        }
        
        error_log("PacienteController: ID do usuário na sessão: " . $id_usuario);
        
        // Se já temos os dados na sessão, não precisa buscar novamente
        if (isset($_SESSION['paciente']['id_paciente'])) {
            error_log("PacienteController: Dados do paciente já existem na sessão - ID: " . $_SESSION['paciente']['id_paciente']);
            
            // Carregar dados antropométricos se não existirem na sessão
            $this->carregarDadosAntropometricosNaSessao();
            
            return true;
        }
        
        error_log("PacienteController: Buscando dados do paciente no banco para usuário ID: " . $id_usuario);
        
        // Buscar dados do paciente no banco
        $paciente = $this->service->getPacienteRepository()->findByUsuarioId($id_usuario);
        if ($paciente) {
            $_SESSION['paciente'] = $paciente;
            $_SESSION['usuario']['tipo'] = 'paciente';
            error_log("PacienteController: Dados do paciente carregados do banco - ID: " . $paciente['id_paciente']);
            error_log("PacienteController: Dados do paciente: " . print_r($paciente, true));
            
            // Carregar dados antropométricos
            $this->carregarDadosAntropometricosNaSessao();
            
            return true;
        }
        
        error_log("PacienteController: Paciente não encontrado no banco para usuário ID: " . $id_usuario);
        return false;
    }

    /**
     * Método auxiliar para carregar dados antropométricos na sessão
     */
    private function carregarDadosAntropometricosNaSessao(): void {
        if (!isset($_SESSION['paciente']['id_paciente'])) {
            return;
        }

        $dadosAntropometricosRepo = new \Htdocs\Src\Models\Repository\DadosAntropometricosRepository();
        $dadosAntropometricosService = new \Htdocs\Src\Services\DadosAntropometricosService($dadosAntropometricosRepo);
        
        try {
            $dadosController = new \Htdocs\Src\Controllers\DadosAntropometricosController(
                $dadosAntropometricosService
            );
            $dadosController->carregarDadosNaSessao();
        } catch (\Exception $e) {
            error_log("PacienteController: Erro ao carregar dados antropométricos: " . $e->getMessage());
        }
    }

    /**
     * Método para servir a página de dados antropométricos
     */
    public function mostrarDadosAntropometricos() {
        if (!$this->garantirDadosPacienteNaSessao()) {
            header('Location: /paciente/cadastro');
            exit;
        }
        
        $formPath = dirname(__DIR__, 2) . '/view/paciente/dados-antropometricos.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            http_response_code(404);
            echo "Erro: Página não encontrada";
        }
    }

    /**
     * Método para servir a página de diário de alimentos
     */
    public function mostrarDiarioAlimentos() {
        if (!$this->garantirDadosPacienteNaSessao()) {
            header('Location: /paciente/cadastro');
            exit;
        }
        
        $formPath = dirname(__DIR__, 2) . '/view/paciente/diario-alimentos.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            http_response_code(404);
            echo "Erro: Página não encontrada";
        }
    }

    /**
     * Método para servir a página de consultas do paciente
     */
    public function mostrarConsultas() {
        if (!$this->garantirDadosPacienteNaSessao()) {
            header('Location: /paciente/cadastro');
            exit;
        }
        
        $formPath = dirname(__DIR__, 2) . '/view/paciente/consultas.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            http_response_code(404);
            echo "Erro: Página não encontrada";
        }
    }
}
?>