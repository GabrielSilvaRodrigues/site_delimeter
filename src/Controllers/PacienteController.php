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
            echo json_encode(['error' => 'Dados incompletos para vincular paciente.']);
            return;
        }

        $paciente = new \Htdocs\Src\Models\Entity\Paciente(
            null, // id_paciente será gerado pelo banco
            $id_usuario,
            $cpf,
            $nis
        );

        $result = $this->service->criar($paciente);

        if (is_array($result) && isset($result['error'])) {
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

        // Redireciona para painel do paciente
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Location: /paciente');
            exit;
        }
        echo json_encode(['success' => true]);
    }

    public function mostrarFormulario(){
        $formPath = dirname(__DIR__, 2) . '/view/paciente/form.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Formulário não encontrado em $formPath";
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
            echo json_encode(['error' => 'Paciente não encontrado.']);
            header('Location: /paciente/cadastro');
            exit;
        } else {
            $_SESSION['usuario']['tipo'] = 'paciente';
            $_SESSION['paciente'] = $paciente; // Salvar dados do paciente na sessão
        }
        $formPath = dirname(__DIR__, 2) . '/view/paciente/index.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Início não encontrado em $formPath";
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
            echo json_encode(['error' => 'Dados incompletos para atualizar paciente.']);
            return;
        }

        $paciente = new \Htdocs\Src\Models\Entity\Paciente(
            null, // id_paciente será usado para update
            (int)$id_usuario,
            $cpf,
            $nis
        );

        $result = $this->service->atualizarConta($paciente);

        if (is_array($result) && isset($result['error'])) {
            echo json_encode(['error' => $result['error']]);
            return;
        }

        // Atualiza sessão com os novos dados
        $_SESSION['usuario']['cpf'] = $cpf;
        $_SESSION['usuario']['nis'] = $nis;

        // Compatível com rota exclusiva, não redireciona para /conta
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Location: /conta');
            exit;
        }

        echo json_encode(['success' => true]);
    }

    public function deletarConta() {
        $id = $_SESSION['usuario']['id_usuario'] ?? $_SESSION['usuario']['id'] ?? null;
        if ($id) {
            $this->service->deletarConta($id);
            $_SESSION['usuario']['tipo'] = 'usuario'; // Redefine tipo para usuário padrão
            // Compatível com rota exclusiva, não redireciona para /
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header('Location: /usuario');
                exit;
            }
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(["error" => "ID do usuário não fornecido."]);
        }
    }

    public function sairConta() {
        $_SESSION['usuario']['tipo'] = 'usuario';
        // Compatível com rota exclusiva, não redireciona para /
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Location: /usuario');
            exit;
        }
        echo json_encode(["success" => "Sessão encerrada."]);
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
            return true;
        }
        
        error_log("PacienteController: Paciente não encontrado no banco para usuário ID: " . $id_usuario);
        return false;
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
            echo "Erro: Página não encontrada em $formPath";
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
            echo "Erro: Página não encontrada em $formPath";
        }
    }
}
?>