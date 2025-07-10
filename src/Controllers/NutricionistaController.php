<?php
namespace Htdocs\Src\Controllers;

use Htdocs\Src\Services\NutricionistaService;

class NutricionistaController {
    private $service;

    public function __construct(NutricionistaService $service) {
        $this->service = $service;
    }

    public function criar() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_usuario = $_SESSION['usuario']['id_usuario'] ?? $_SESSION['usuario']['id'] ?? null;
        $crm = $data['crm_nutricionista'] ?? $data['crm'] ?? null;
        $cpf = $data['cpf'] ?? null;

        if (!$id_usuario || !$crm || !$cpf) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados incompletos para vincular nutricionista.']);
            return;
        }

        $nutricionista = new \Htdocs\Src\Models\Entity\Nutricionista(
            null, // id_nutricionista será gerado pelo banco
            (int)$id_usuario,
            $crm,
            $cpf
        );

        $result = $this->service->criar($nutricionista);

        if (is_array($result) && isset($result['error'])) {
            http_response_code(400);
            echo json_encode(['error' => $result['error']]);
            return;
        }

        // LOGIN AUTOMÁTICO APÓS CADASTRO
        $_SESSION['usuario']['tipo'] = 'nutricionista';
        $_SESSION['usuario']['crm_nutricionista'] = $crm;
        $_SESSION['usuario']['cpf'] = $cpf;

        // Carregar os dados completos do nutricionista e salvar na sessão
        $nutricionistaData = $this->service->getNutricionistaRepository()->findByUsuarioId($id_usuario);
        if ($nutricionistaData) {
            $_SESSION['nutricionista'] = $nutricionistaData;
        }

        // Para requisições AJAX/JSON
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode(['success' => true, 'redirect' => '/nutricionista']);
            return;
        }

        // Para requisições normais via formulário
        header('Location: /nutricionista');
        exit;
    }

    public function listar() {
        echo json_encode($this->service->listar());
    }
    
    public function mostrarFormulario(){
        $formPath = dirname(__DIR__, 2) . '/view/nutricionista/form.php';
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
        
        $nutricionista = $this->service->getNutricionistaRepository()->findByUsuarioId($id_usuario);
        if (!$nutricionista) {
            header('Location: /nutricionista/cadastro');
            exit;
        }
        
        $_SESSION['usuario']['tipo'] = 'nutricionista';
        $_SESSION['nutricionista'] = $nutricionista;
        
        $formPath = dirname(__DIR__, 2) . '/view/nutricionista/index.php';
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
        $crm = $data['crm_nutricionista'] ?? $data['crm'] ?? null;
        $cpf = $data['cpf'] ?? null;

        if (!$id_usuario || !$crm || !$cpf) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados incompletos para atualizar nutricionista.']);
            return;
        }

        $nutricionista = new \Htdocs\Src\Models\Entity\Nutricionista(
            null, // id_nutricionista será usado para update
            (int)$id_usuario,
            $crm,
            $cpf
        );
        $result = $this->service->atualizarConta($nutricionista);

        if (is_array($result) && isset($result['error'])) {
            http_response_code(400);
            echo json_encode(['error' => $result['error']]);
            return;
        }

        // Atualiza sessão com o novo CRM
        $_SESSION['usuario']['crm_nutricionista'] = $crm;
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
        unset($_SESSION['nutricionista']); // Remove dados do nutricionista da sessão
        
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
        unset($_SESSION['nutricionista']); // Remove dados do nutricionista da sessão
        
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
        $nutricionista = $this->service->getNutricionistaRepository()->findByUsuarioId($id_usuario);
        if ($nutricionista) {
            echo json_encode($nutricionista);
            $_SESSION['usuario']['tipo'] = 'nutricionista';
            $_SESSION['nutricionista'] = $nutricionista; // Salvar dados do nutricionista na sessão
            header('Location: /nutricionista');
            exit;
        } else {
            header('Location: /nutricionista/cadastro');
            exit;
        }
    }

    /**
     * Método auxiliar para garantir que os dados do nutricionista estejam na sessão
     */
    private function garantirDadosNutricionistaNaSessao() {
        $id_usuario = $_SESSION['usuario']['id_usuario'] ?? $_SESSION['usuario']['id'] ?? null;
        
        if (!$id_usuario) {
            error_log("NutricionistaController: ID do usuário não encontrado na sessão");
            error_log("NutricionistaController: Conteúdo da sessão: " . print_r($_SESSION, true));
            return false;
        }
        
        error_log("NutricionistaController: ID do usuário na sessão: " . $id_usuario);
        
        // Se já temos os dados na sessão, não precisa buscar novamente
        if (isset($_SESSION['nutricionista']['id_nutricionista'])) {
            error_log("NutricionistaController: Dados do nutricionista já existem na sessão - ID: " . $_SESSION['nutricionista']['id_nutricionista']);
            return true;
        }
        
        error_log("NutricionistaController: Buscando dados do nutricionista no banco para usuário ID: " . $id_usuario);
        
        // Buscar dados do nutricionista no banco
        $nutricionista = $this->service->getNutricionistaRepository()->findByUsuarioId($id_usuario);
        if ($nutricionista) {
            $_SESSION['nutricionista'] = $nutricionista;
            $_SESSION['usuario']['tipo'] = 'nutricionista';
            error_log("NutricionistaController: Dados do nutricionista carregados do banco - ID: " . $nutricionista['id_nutricionista']);
            error_log("NutricionistaController: Dados do nutricionista: " . print_r($nutricionista, true));
            return true;
        }
        
        error_log("NutricionistaController: Nutricionista não encontrado no banco para usuário ID: " . $id_usuario);
        return false;
    }

    /**
     * Método para servir a página de pacientes
     */
    public function mostrarPacientes() {
        if (!$this->garantirDadosNutricionistaNaSessao()) {
            header('Location: /nutricionista/cadastro');
            exit;
        }
        
        $formPath = dirname(__DIR__, 2) . '/view/nutricionista/pacientes.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            http_response_code(404);
            echo "Erro: Página não encontrada";
        }
    }

    /**
     * Método para servir a página de planos alimentares
     */
    public function mostrarPlanosAlimentares() {
        if (!$this->garantirDadosNutricionistaNaSessao()) {
            header('Location: /nutricionista/cadastro');
            exit;
        }
        
        $formPath = dirname(__DIR__, 2) . '/view/nutricionista/planos-alimentares.php';
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
        if (!$this->garantirDadosNutricionistaNaSessao()) {
            header('Location: /nutricionista/cadastro');
            exit;
        }
        
        $formPath = dirname(__DIR__, 2) . '/view/nutricionista/relatorios.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            http_response_code(404);
            echo "Erro: Página não encontrada";
        }
    }
}
?>