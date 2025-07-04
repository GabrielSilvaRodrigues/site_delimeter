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
            echo json_encode(['error' => 'Dados incompletos para vincular médico.']);
            return;
        }

        $medico = new \Htdocs\Src\Models\Entity\Medico(
            $id_usuario,
            $crm,
            $cpf
        );

        try {
            $this->service->criar($medico);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }

        // LOGIN AUTOMÁTICO APÓS CADASTRO
        $_SESSION['usuario']['tipo'] = 'medico';
        $_SESSION['usuario']['crm_medico'] = $crm;
        $_SESSION['usuario']['cpf'] = $cpf;


        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Location: /medico');
            exit;
        }

        echo json_encode(['success' => true]);
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
        $medico = $this->service->getMedicoRepository()->findById($id_usuario);
        if (!$medico) {
            header('Location: /medico/cadastro');
            exit;
        }  else {
            $_SESSION['usuario']['tipo'] = 'medico';
        }
        $formPath = dirname(__DIR__, 2) . '/view/medico/index.php';
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
            echo json_encode(['error' => 'Dados incompletos para atualizar médico.']);
            return;
        }

        $medico = new \Htdocs\Src\Models\Entity\Medico(
            $id_usuario,
            $crm,
            $cpf
        );
        $this->service->atualizarConta($medico);

        // Atualiza sessão com o novo CRM
        $_SESSION['usuario']['crm_medico'] = $crm;

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Location: /usuario');
            exit;
        }
        echo json_encode(["success" => "Usuário deslogado com sucesso."]);
    }
}
?>