<?php
namespace Htdocs\Src\Controllers;

use Htdocs\Src\Services\MedicoService;

class MedicoController {
    private $service;

    public function __construct(MedicoService $service) {
        $this->service = $service;
    }

    public function criar() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_usuario = $_SESSION['usuario']['id_usuario'] ?? $_SESSION['usuario']['id'] ?? null;
        $crm = $data['crm_medico'] ?? $data['crm'] ?? null;
        $cpf = $data['cpf'] ?? null;

        if (!$id_usuario || !$crm || !$cpf) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados incompletos para vincular médico.']);
            return;
        }

        $medico = new \Htdocs\Src\Models\Entity\Medico(
            null, // id_medico será gerado pelo banco
            (int)$id_usuario,
            $crm,
            $cpf
        );

        $result = $this->service->criar($medico);

        if (is_array($result) && isset($result['error'])) {
            http_response_code(400);
            echo json_encode(['error' => $result['error']]);
            return;
        }

        // LOGIN AUTOMÁTICO APÓS CADASTRO
        $_SESSION['usuario']['tipo'] = 'medico';
        $_SESSION['usuario']['crm_medico'] = $crm;
        $_SESSION['usuario']['cpf'] = $cpf;

        // Carregar os dados completos do médico e salvar na sessão
        $medicoData = $this->service->getMedicoRepository()->findByUsuarioId($id_usuario);
        if ($medicoData) {
            $_SESSION['medico'] = $medicoData;
        }

        // Para requisições AJAX/JSON
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode(['success' => true, 'redirect' => '/medico']);
            return;
        }

        // Para requisições normais via formulário
        header('Location: /medico');
        exit;
    }

    public function listar() {
        echo json_encode($this->service->listar());
    }

    public function mostrarFormulario(){
        $formPath = dirname(__DIR__, 2) . '/view/medico/form.php';
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
        
        $medico = $this->service->getMedicoRepository()->findByUsuarioId($id_usuario);
        if (!$medico) {
            header('Location: /medico/cadastro');
            exit;
        }
        
        $_SESSION['usuario']['tipo'] = 'medico';
        $_SESSION['medico'] = $medico;
        
        $formPath = dirname(__DIR__, 2) . '/view/medico/index.php';
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
        if ($id) {
            echo json_encode($this->service->mostrarConta($id));
        } else {
            echo json_encode(["error" => "Usuário não está logado."]);
        }
    }

    public function atualizarConta() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_usuario = $_SESSION['usuario']['id_usuario'] ?? $_SESSION['usuario']['id'] ?? null;
        $crm = $data['crm_medico'] ?? $data['crm'] ?? null;
        $cpf = $data['cpf'] ?? null;

        if (!$id_usuario || !$crm || !$cpf) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados incompletos para atualizar médico.']);
            return;
        }

        $medico = new \Htdocs\Src\Models\Entity\Medico(
            null, // id_medico será usado para update
            (int)$id_usuario,
            $crm,
            $cpf
        );
        $result = $this->service->atualizarConta($medico);

        if (is_array($result) && isset($result['error'])) {
            http_response_code(400);
            echo json_encode(['error' => $result['error']]);
            return;
        }

        // Atualiza sessão com o novo CRM
        $_SESSION['usuario']['crm_medico'] = $crm;
        $_SESSION['usuario']['cpf'] = $cpf;

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
        $id = $_SESSION['usuario']['id_usuario'] ?? $_SESSION['usuario']['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(["error" => "ID do usuário não fornecido."]);
            return;
        }

        $result = $this->service->deletarConta($id);
        $_SESSION['usuario']['tipo'] = 'usuario'; // Redefine tipo para usuário padrão
        unset($_SESSION['medico']); // Remove dados do médico da sessão
        
        // Para requisições AJAX/JSON
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode(['success' => true, 'redirect' => '/usuario']);
            return;
        }

        // Para requisições normais
        header('Location: /usuario');
        exit;
    }

    public function sairConta() {
        $_SESSION['usuario']['tipo'] = 'usuario';
        unset($_SESSION['medico']); // Remove dados do médico da sessão
        
        // Para requisições AJAX/JSON
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode(["success" => "Sessão encerrada.", 'redirect' => '/usuario']);
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
        $medico = $this->service->getMedicoRepository()->findByUsuarioId($id_usuario);
        if ($medico) {
            echo json_encode($medico);
            $_SESSION['usuario']['tipo'] = 'medico';
            $_SESSION['medico'] = $medico; // Salvar dados do médico na sessão
            header('Location: /medico');
            exit;
        } else {
            header('Location: /medico/cadastro');
            exit;
        }
    }

    /**
     * Método auxiliar para garantir que os dados do médico estejam na sessão
     */
    private function garantirDadosMedicoNaSessao() {
        $id_usuario = $_SESSION['usuario']['id_usuario'] ?? $_SESSION['usuario']['id'] ?? null;
        
        if (!$id_usuario) {
            error_log("MedicoController: ID do usuário não encontrado na sessão");
            error_log("MedicoController: Conteúdo da sessão: " . print_r($_SESSION, true));
            return false;
        }
        
        error_log("MedicoController: ID do usuário na sessão: " . $id_usuario);
        
        // Se já temos os dados na sessão, não precisa buscar novamente
        if (isset($_SESSION['medico']['id_medico'])) {
            error_log("MedicoController: Dados do médico já existem na sessão - ID: " . $_SESSION['medico']['id_medico']);
            return true;
        }
        
        error_log("MedicoController: Buscando dados do médico no banco para usuário ID: " . $id_usuario);
        
        // Buscar dados do médico no banco
        $medico = $this->service->getMedicoRepository()->findByUsuarioId($id_usuario);
        if ($medico) {
            $_SESSION['medico'] = $medico;
            $_SESSION['usuario']['tipo'] = 'medico';
            error_log("MedicoController: Dados do médico carregados do banco - ID: " . $medico['id_medico']);
            error_log("MedicoController: Dados do médico: " . print_r($medico, true));
            return true;
        }
        
        error_log("MedicoController: Médico não encontrado no banco para usuário ID: " . $id_usuario);
        return false;
    }

    /**
     * Método para servir a página de pacientes
     */
    public function mostrarPacientes() {
        if (!$this->garantirDadosMedicoNaSessao()) {
            header('Location: /medico/cadastro');
            exit;
        }
        
        $formPath = dirname(__DIR__, 2) . '/view/medico/pacientes.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            http_response_code(404);
            echo "Erro: Página não encontrada";
        }
    }

    /**
     * Método para servir a página de consultas
     */
    public function mostrarConsultas() {
        if (!$this->garantirDadosMedicoNaSessao()) {
            header('Location: /medico/cadastro');
            exit;
        }
        
        $formPath = dirname(__DIR__, 2) . '/view/medico/consultas.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            http_response_code(404);
            echo "Erro: Página não encontrada";
        }
    }

    /**
     * Método para servir a página de prescrições
     */
    public function mostrarPrescricoes() {
        if (!$this->garantirDadosMedicoNaSessao()) {
            header('Location: /medico/cadastro');
            exit;
        }
        
        $formPath = dirname(__DIR__, 2) . '/view/medico/prescricoes.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            http_response_code(404);
            echo "Erro: Página não encontrada";
        }
    }

    /**
     * Método para servir a página de relatórios
     */
    public function mostrarRelatorios() {
        if (!$this->garantirDadosMedicoNaSessao()) {
            header('Location: /medico/cadastro');
            exit;
        }
        
        $formPath = dirname(__DIR__, 2) . '/view/medico/relatorios.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            http_response_code(404);
            echo "Erro: Página não encontrada";
        }
    }

    /**
     * Método para servir a página de validações
     */
    public function mostrarValidacoes() {
        if (!$this->garantirDadosMedicoNaSessao()) {
            header('Location: /medico/cadastro');
            exit;
        }
        
        $formPath = dirname(__DIR__, 2) . '/view/medico/validacoes.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            http_response_code(404);
            echo "Erro: Página não encontrada";
        }
    }

    /**
     * Método para listar todos os pacientes - API
     */
    public function listarPacientes() {
        header('Content-Type: application/json');
        
        if (!$this->garantirDadosMedicoNaSessao()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Acesso não autorizado']);
            return;
        }
        
        try {
            $pacientes = $this->service->listarTodosPacientes();
            echo json_encode(['success' => true, 'data' => $pacientes]);
        } catch (\Exception $e) {
            error_log("Erro ao listar pacientes: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Erro interno do servidor']);
        }
    }

    /**
     * Método para buscar pacientes por termo - API
     */
    public function buscarPacientes() {
        header('Content-Type: application/json');
        
        if (!$this->garantirDadosMedicoNaSessao()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Acesso não autorizado']);
            return;
        }
        
        $termo = $_GET['termo'] ?? '';
        
        if (empty($termo)) {
            echo json_encode(['success' => false, 'error' => 'Termo de busca é obrigatório']);
            return;
        }
        
        try {
            $pacientes = $this->service->buscarPacientes($termo);
            echo json_encode(['success' => true, 'data' => $pacientes]);
        } catch (\Exception $e) {
            error_log("Erro ao buscar pacientes: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Erro interno do servidor']);
        }
    }

    /**
     * Método para obter dados de um paciente específico - API
     */
    public function obterPaciente() {
        header('Content-Type: application/json');
        
        if (!$this->garantirDadosMedicoNaSessao()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Acesso não autorizado']);
            return;
        }
        
        $idPaciente = $_GET['id'] ?? '';
        
        if (empty($idPaciente)) {
            echo json_encode(['success' => false, 'error' => 'ID do paciente é obrigatório']);
            return;
        }
        
        try {
            $paciente = $this->service->obterPacientePorId($idPaciente);
            if ($paciente) {
                echo json_encode(['success' => true, 'data' => $paciente]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Paciente não encontrado']);
            }
        } catch (\Exception $e) {
            error_log("Erro ao obter paciente: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Erro interno do servidor']);
        }
    }

    /**
     * Método para mostrar detalhes de um paciente específico
     */
    public function mostrarDetalhesPaciente() {
        if (!$this->garantirDadosMedicoNaSessao()) {
            header('Location: /medico/cadastro');
            exit;
        }
        
        // Extrair ID do paciente da URL
        $uri = $_SERVER['REQUEST_URI'];
        $matches = [];
        if (preg_match('/\/medico\/paciente\/(\d+)$/', $uri, $matches)) {
            $idPaciente = $matches[1];
            
            try {
                $paciente = $this->service->obterPacientePorId($idPaciente);
                if (!$paciente) {
                    http_response_code(404);
                    echo "Paciente não encontrado";
                    return;
                }
                
                $formPath = dirname(__DIR__, 2) . '/view/medico/paciente-detalhes.php';
                if (file_exists($formPath)) {
                    include_once $formPath;
                } else {
                    http_response_code(404);
                    echo "Erro: Página não encontrada";
                }
            } catch (\Exception $e) {
                error_log("Erro ao carregar detalhes do paciente: " . $e->getMessage());
                http_response_code(500);
                echo "Erro interno do servidor";
            }
        } else {
            http_response_code(400);
            echo "ID do paciente inválido";
        }
    }

    /**
     * Método para mostrar histórico de um paciente específico
     */
    public function mostrarHistoricoPaciente() {
        if (!$this->garantirDadosMedicoNaSessao()) {
            header('Location: /medico/cadastro');
            exit;
        }
        
        // Extrair ID do paciente da URL
        $uri = $_SERVER['REQUEST_URI'];
        $matches = [];
        if (preg_match('/\/medico\/paciente\/(\d+)\/historico$/', $uri, $matches)) {
            $idPaciente = $matches[1];
            
            try {
                $paciente = $this->service->obterPacientePorId($idPaciente);
                if (!$paciente) {
                    http_response_code(404);
                    echo "Paciente não encontrado";
                    return;
                }
                
                $formPath = dirname(__DIR__, 2) . '/view/medico/paciente-historico.php';
                if (file_exists($formPath)) {
                    include_once $formPath;
                } else {
                    http_response_code(404);
                    echo "Erro: Página não encontrada";
                }
            } catch (\Exception $e) {
                error_log("Erro ao carregar histórico do paciente: " . $e->getMessage());
                http_response_code(500);
                echo "Erro interno do servidor";
            }
        } else {
            http_response_code(400);
            echo "ID do paciente inválido";
        }
    }
}
?>