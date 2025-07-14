<?php
namespace Htdocs\Src\Controllers;

use Htdocs\Src\Services\ConsultaService;
use Htdocs\Src\Models\Entity\Consulta;

class ConsultaController {
    private $service;

    public function __construct(ConsultaService $service) {
        $this->service = $service;
    }

    public function criar() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $data_consulta = $data['data_consulta'] ?? null;
        $id_paciente = $data['id_paciente'] ?? null;
        $id_profissional = $data['id_profissional'] ?? null;
        $tipo_profissional = $data['tipo_profissional'] ?? null;

        if (!$data_consulta) {
            echo json_encode(['success' => false, 'error' => 'Data da consulta é obrigatória.']);
            exit;
        }

        try {
            $consulta = new Consulta(null, $data_consulta);
            $result = $this->service->agendarConsulta($consulta, $id_paciente, $id_profissional, $tipo_profissional);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Consulta agendada com sucesso.', 'id' => $result]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erro ao agendar consulta.']);
            }
        } catch (\Exception $e) {
            error_log("Erro ao criar consulta: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro interno do servidor.']);
        }
        exit;
    }

    public function listar() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        try {
            $consultas = $this->service->listar();
            echo json_encode(['success' => true, 'data' => $consultas]);
        } catch (\Exception $e) {
            error_log("Erro ao listar consultas: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro ao buscar consultas.']);
        }
        exit;
    }

    public function buscarPorPaciente() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $id_paciente = $_GET['id_paciente'] ?? null;
        
        if (!$id_paciente) {
            echo json_encode(['success' => false, 'error' => 'ID do paciente é obrigatório.']);
            exit;
        }

        try {
            $consultas = $this->service->buscarPorPaciente($id_paciente);
            echo json_encode(['success' => true, 'data' => $consultas]);
        } catch (\Exception $e) {
            error_log("Erro ao buscar consultas por paciente: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro ao buscar consultas do paciente.']);
        }
        exit;
    }

    public function buscarPorMedico() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $id_medico = $_GET['id_medico'] ?? null;
        
        if (!$id_medico) {
            echo json_encode(['success' => false, 'error' => 'ID do médico é obrigatório.']);
            exit;
        }

        try {
            $consultas = $this->service->buscarPorMedico($id_medico);
            echo json_encode(['success' => true, 'data' => $consultas]);
        } catch (\Exception $e) {
            error_log("Erro ao buscar consultas por médico: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro ao buscar consultas do médico.']);
        }
        exit;
    }

    public function buscarPorNutricionista() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $id_nutricionista = $_GET['id_nutricionista'] ?? null;
        
        if (!$id_nutricionista) {
            echo json_encode(['success' => false, 'error' => 'ID do nutricionista é obrigatório.']);
            exit;
        }

        try {
            $consultas = $this->service->buscarPorNutricionista($id_nutricionista);
            echo json_encode(['success' => true, 'data' => $consultas]);
        } catch (\Exception $e) {
            error_log("Erro ao buscar consultas por nutricionista: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro ao buscar consultas do nutricionista.']);
        }
        exit;
    }

    public function deletar() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents("php://input"), true);
        $id_consulta = $data['id'] ?? $_GET['id'] ?? $_POST['id'] ?? null;
        
        if (!$id_consulta) {
            echo json_encode(['success' => false, 'error' => 'ID da consulta é obrigatório.']);
            exit;
        }

        try {
            $result = $this->service->deletar($id_consulta);
            
            if ($result !== false) {
                echo json_encode(['success' => true, 'message' => 'Consulta cancelada com sucesso.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erro ao cancelar consulta.']);
            }
        } catch (\Exception $e) {
            error_log("Erro ao deletar consulta: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro interno do servidor.']);
        }
        exit;
    }

    public function mostrarConsultasPaciente() {
        $formPath = dirname(__DIR__, 2) . '/view/paciente/consultas.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            http_response_code(404);
            echo "Erro: Página não encontrada";
        }
    }

    private function limparOutput() {
        while (ob_get_level()) {
            ob_end_clean();
        }
        ob_start();
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
    }
}
?>
