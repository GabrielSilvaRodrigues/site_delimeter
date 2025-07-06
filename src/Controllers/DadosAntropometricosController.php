<?php
namespace Htdocs\Src\Controllers;

use Htdocs\Src\Services\DadosAntropometricosService;
use Htdocs\Src\Models\Entity\DadosAntropometricos;

class DadosAntropometricosController {
    private $service;

    public function __construct(DadosAntropometricosService $service) {
        $this->service = $service;
    }

    public function criar() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_paciente = $data['id_paciente'] ?? null;
        $sexo_paciente = $data['sexo_paciente'] ?? null;
        $altura_paciente = $data['altura_paciente'] ?? null;
        $peso_paciente = $data['peso_paciente'] ?? null;
        $status_paciente = $data['status_paciente'] ?? 1;
        $data_medida = $data['data_medida'] ?? date('Y-m-d');

        if (!$id_paciente) {
            echo json_encode(['error' => 'ID do paciente é obrigatório.']);
            return;
        }

        $dados = new DadosAntropometricos(
            null,
            $id_paciente,
            $sexo_paciente,
            $altura_paciente,
            $peso_paciente,
            $status_paciente,
            $data_medida
        );

        $result = $this->service->criar($dados);

        if ($result) {
            echo json_encode(['success' => 'Dados antropométricos cadastrados com sucesso.', 'id' => $result]);
        } else {
            echo json_encode(['error' => 'Erro ao cadastrar dados antropométricos.']);
        }
    }

    public function listar() {
        $dados = $this->service->listar();
        echo json_encode($dados);
    }

    public function buscarPorPaciente() {
        $id_paciente = $_GET['id_paciente'] ?? null;
        
        if (!$id_paciente) {
            echo json_encode(['error' => 'ID do paciente é obrigatório.']);
            return;
        }

        $dados = $this->service->buscarPorPaciente($id_paciente);
        echo json_encode($dados);
    }

    public function buscarUltimaMedida() {
        $id_paciente = $_GET['id_paciente'] ?? null;
        
        if (!$id_paciente) {
            echo json_encode(['error' => 'ID do paciente é obrigatório.']);
            return;
        }

        $dados = $this->service->buscarUltimaMedida($id_paciente);
        
        if ($dados) {
            $imc = $this->service->calcularIMC($dados['altura_paciente'], $dados['peso_paciente']);
            $dados['imc'] = $imc;
            $dados['classificacao_imc'] = $this->service->classificarIMC($imc);
        }
        
        echo json_encode($dados);
    }

    public function atualizar() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_medida = $data['id_medida'] ?? null;
        $id_paciente = $data['id_paciente'] ?? null;
        $sexo_paciente = $data['sexo_paciente'] ?? null;
        $altura_paciente = $data['altura_paciente'] ?? null;
        $peso_paciente = $data['peso_paciente'] ?? null;
        $status_paciente = $data['status_paciente'] ?? null;
        $data_medida = $data['data_medida'] ?? null;

        if (!$id_medida || !$id_paciente) {
            echo json_encode(['error' => 'ID da medida e ID do paciente são obrigatórios.']);
            return;
        }

        $dados = new DadosAntropometricos(
            $id_medida,
            $id_paciente,
            $sexo_paciente,
            $altura_paciente,
            $peso_paciente,
            $status_paciente,
            $data_medida
        );

        $result = $this->service->atualizar($dados);
        
        if ($result !== false) {
            echo json_encode(['success' => 'Dados antropométricos atualizados com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao atualizar dados antropométricos.']);
        }
    }

    public function deletar() {
        $id_medida = $_GET['id'] ?? $_POST['id'] ?? null;
        
        if (!$id_medida) {
            echo json_encode(['error' => 'ID da medida é obrigatório.']);
            return;
        }

        $result = $this->service->deletar($id_medida);
        
        if ($result !== false) {
            echo json_encode(['success' => 'Dados antropométricos deletados com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao deletar dados antropométricos.']);
        }
    }

    public function calcularIMC() {
        $altura = $_GET['altura'] ?? $_POST['altura'] ?? null;
        $peso = $_GET['peso'] ?? $_POST['peso'] ?? null;
        
        if (!$altura || !$peso) {
            echo json_encode(['error' => 'Altura e peso são obrigatórios.']);
            return;
        }

        $imc = $this->service->calcularIMC($altura, $peso);
        $classificacao = $this->service->classificarIMC($imc);
        
        echo json_encode([
            'imc' => $imc,
            'classificacao' => $classificacao
        ]);
    }
}
?>
