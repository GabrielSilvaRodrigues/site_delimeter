<?php
namespace Htdocs\Src\Controllers;

use Htdocs\Src\Services\DietaService;
use Htdocs\Src\Models\Entity\Dieta;

class DietaController {
    private $service;

    public function __construct(DietaService $service) {
        $this->service = $service;
    }

    public function criar() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $data_inicio_dieta = $data['data_inicio_dieta'] ?? null;
        $data_termino_dieta = $data['data_termino_dieta'] ?? null;
        $descricao_dieta = $data['descricao_dieta'] ?? null;
        $id_paciente = $data['id_paciente'] ?? null;
        $id_nutricionista = $data['id_nutricionista'] ?? null;

        if (!$descricao_dieta) {
            echo json_encode(['error' => 'Descrição da dieta é obrigatória.']);
            return;
        }

        $dieta = new Dieta(
            null,
            $data_inicio_dieta,
            $data_termino_dieta,
            $descricao_dieta
        );

        if ($id_paciente && $id_nutricionista) {
            $result = $this->service->criarDietaCompleta($dieta, $id_paciente, $id_nutricionista);
        } else {
            $result = $this->service->criar($dieta);
        }

        if ($result) {
            echo json_encode(['success' => 'Dieta criada com sucesso.', 'id' => $result]);
        } else {
            echo json_encode(['error' => 'Erro ao criar dieta.']);
        }
    }

    public function listar() {
        $dietas = $this->service->listar();
        echo json_encode($dietas);
    }

    public function buscarPorId() {
        $id_dieta = $_GET['id'] ?? null;
        
        if (!$id_dieta) {
            echo json_encode(['error' => 'ID da dieta é obrigatório.']);
            return;
        }

        $dieta = $this->service->buscarPorId($id_dieta);
        echo json_encode($dieta);
    }

    public function buscarPorPaciente() {
        $id_paciente = $_GET['id_paciente'] ?? null;
        
        if (!$id_paciente) {
            echo json_encode(['error' => 'ID do paciente é obrigatório.']);
            return;
        }

        $dietas = $this->service->buscarPorPaciente($id_paciente);
        echo json_encode($dietas);
    }

    public function buscarPorNutricionista() {
        $id_nutricionista = $_GET['id_nutricionista'] ?? null;
        
        if (!$id_nutricionista) {
            echo json_encode(['error' => 'ID do nutricionista é obrigatório.']);
            return;
        }

        $dietas = $this->service->buscarPorNutricionista($id_nutricionista);
        echo json_encode($dietas);
    }

    public function atualizar() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_dieta = $data['id_dieta'] ?? null;
        $data_inicio_dieta = $data['data_inicio_dieta'] ?? null;
        $data_termino_dieta = $data['data_termino_dieta'] ?? null;
        $descricao_dieta = $data['descricao_dieta'] ?? null;

        if (!$id_dieta) {
            echo json_encode(['error' => 'ID da dieta é obrigatório.']);
            return;
        }

        $dieta = new Dieta(
            $id_dieta,
            $data_inicio_dieta,
            $data_termino_dieta,
            $descricao_dieta
        );

        $result = $this->service->atualizar($dieta);
        
        if ($result !== false) {
            echo json_encode(['success' => 'Dieta atualizada com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao atualizar dieta.']);
        }
    }

    public function deletar() {
        $id_dieta = $_GET['id'] ?? $_POST['id'] ?? null;
        
        if (!$id_dieta) {
            echo json_encode(['error' => 'ID da dieta é obrigatório.']);
            return;
        }

        $result = $this->service->deletar($id_dieta);
        
        if ($result !== false) {
            echo json_encode(['success' => 'Dieta deletada com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao deletar dieta.']);
        }
    }

    public function associarPaciente() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_dieta = $data['id_dieta'] ?? null;
        $id_paciente = $data['id_paciente'] ?? null;

        if (!$id_dieta || !$id_paciente) {
            echo json_encode(['error' => 'ID da dieta e ID do paciente são obrigatórios.']);
            return;
        }

        $result = $this->service->associarPaciente($id_dieta, $id_paciente);
        
        if ($result !== false) {
            echo json_encode(['success' => 'Paciente associado à dieta com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao associar paciente à dieta.']);
        }
    }

    public function associarNutricionista() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_dieta = $data['id_dieta'] ?? null;
        $id_nutricionista = $data['id_nutricionista'] ?? null;

        if (!$id_dieta || !$id_nutricionista) {
            echo json_encode(['error' => 'ID da dieta e ID do nutricionista são obrigatórios.']);
            return;
        }

        $result = $this->service->associarNutricionista($id_dieta, $id_nutricionista);
        
        if ($result !== false) {
            echo json_encode(['success' => 'Nutricionista associado à dieta com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao associar nutricionista à dieta.']);
        }
    }
}
?>
