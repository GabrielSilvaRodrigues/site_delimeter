<?php
namespace Htdocs\Src\Controllers;

use Htdocs\Src\Services\DiarioDeAlimentosService;
use Htdocs\Src\Models\Entity\DiarioDeAlimentos;

class DiarioDeAlimentosController {
    private $service;

    public function __construct(DiarioDeAlimentosService $service) {
        $this->service = $service;
    }

    public function criar() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_paciente = $data['id_paciente'] ?? null;
        $data_diario = $data['data_diario'] ?? date('Y-m-d');
        $descricao_diario = $data['descricao_diario'] ?? null;

        if (!$id_paciente) {
            echo json_encode(['error' => 'ID do paciente é obrigatório.']);
            return;
        }

        $diario = new DiarioDeAlimentos(
            null,
            $id_paciente,
            $data_diario,
            $descricao_diario
        );

        $result = $this->service->criar($diario);

        if ($result) {
            echo json_encode(['success' => 'Registro do diário criado com sucesso.', 'id' => $result]);
        } else {
            echo json_encode(['error' => 'Erro ao criar registro do diário.']);
        }
    }

    public function listar() {
        $registros = $this->service->listar();
        echo json_encode($registros);
    }

    public function buscarPorId() {
        $id_diario = $_GET['id'] ?? null;
        
        if (!$id_diario) {
            echo json_encode(['error' => 'ID do diário é obrigatório.']);
            return;
        }

        $registro = $this->service->buscarPorId($id_diario);
        echo json_encode($registro);
    }

    public function buscarPorPaciente() {
        $id_paciente = $_GET['id_paciente'] ?? null;
        
        if (!$id_paciente) {
            echo json_encode(['error' => 'ID do paciente é obrigatório.']);
            return;
        }

        $registros = $this->service->buscarPorPaciente($id_paciente);
        echo json_encode($registros);
    }

    public function buscarPorPacienteEData() {
        $id_paciente = $_GET['id_paciente'] ?? null;
        $data_diario = $_GET['data_diario'] ?? null;
        
        if (!$id_paciente || !$data_diario) {
            echo json_encode(['error' => 'ID do paciente e data são obrigatórios.']);
            return;
        }

        $registro = $this->service->buscarPorPacienteEData($id_paciente, $data_diario);
        echo json_encode($registro);
    }

    public function buscarPorPeriodo() {
        $id_paciente = $_GET['id_paciente'] ?? null;
        $data_inicio = $_GET['data_inicio'] ?? null;
        $data_fim = $_GET['data_fim'] ?? null;
        
        if (!$id_paciente || !$data_inicio || !$data_fim) {
            echo json_encode(['error' => 'ID do paciente, data início e data fim são obrigatórios.']);
            return;
        }

        $registros = $this->service->buscarPorPeriodo($id_paciente, $data_inicio, $data_fim);
        echo json_encode($registros);
    }

    public function atualizar() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_diario = $data['id_diario'] ?? null;
        $id_paciente = $data['id_paciente'] ?? null;
        $data_diario = $data['data_diario'] ?? null;
        $descricao_diario = $data['descricao_diario'] ?? null;

        if (!$id_diario) {
            echo json_encode(['error' => 'ID do diário é obrigatório.']);
            return;
        }

        $diario = new DiarioDeAlimentos(
            $id_diario,
            $id_paciente,
            $data_diario,
            $descricao_diario
        );

        $result = $this->service->atualizar($diario);
        
        if ($result !== false) {
            echo json_encode(['success' => 'Registro do diário atualizado com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao atualizar registro do diário.']);
        }
    }

    public function deletar() {
        $id_diario = $_GET['id'] ?? $_POST['id'] ?? null;
        
        if (!$id_diario) {
            echo json_encode(['error' => 'ID do diário é obrigatório.']);
            return;
        }

        $result = $this->service->deletar($id_diario);
        
        if ($result !== false) {
            echo json_encode(['success' => 'Registro do diário deletado com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao deletar registro do diário.']);
        }
    }

    public function associarAlimento() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_diario = $data['id_diario'] ?? null;
        $id_alimento = $data['id_alimento'] ?? null;

        if (!$id_diario || !$id_alimento) {
            echo json_encode(['error' => 'ID do diário e ID do alimento são obrigatórios.']);
            return;
        }

        $result = $this->service->associarAlimento($id_diario, $id_alimento);
        
        if ($result !== false) {
            echo json_encode(['success' => 'Alimento associado ao diário com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao associar alimento ao diário.']);
        }
    }

    public function removerAlimento() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_diario = $data['id_diario'] ?? $_GET['id_diario'] ?? null;
        $id_alimento = $data['id_alimento'] ?? $_GET['id_alimento'] ?? null;

        if (!$id_diario || !$id_alimento) {
            echo json_encode(['error' => 'ID do diário e ID do alimento são obrigatórios.']);
            return;
        }

        $result = $this->service->removerAlimento($id_diario, $id_alimento);
        
        if ($result !== false) {
            echo json_encode(['success' => 'Alimento removido do diário com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao remover alimento do diário.']);
        }
    }

    public function obterDiarioSemanal() {
        $id_paciente = $_GET['id_paciente'] ?? null;
        $data_inicio = $_GET['data_inicio'] ?? null;
        
        if (!$id_paciente) {
            echo json_encode(['error' => 'ID do paciente é obrigatório.']);
            return;
        }

        $registros = $this->service->obterDiarioSemanal($id_paciente, $data_inicio);
        echo json_encode($registros);
    }
}
?>
