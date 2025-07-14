<?php
namespace Htdocs\Src\Controllers;

use Htdocs\Src\Services\ReceitaService;
use Htdocs\Src\Models\Entity\Receita;

class ReceitaController {
    private $service;

    public function __construct(ReceitaService $service) {
        $this->service = $service;
    }

    public function criar() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $data_inicio = $data['data_inicio_receita'] ?? null;
        $data_termino = $data['data_termino_receita'] ?? null;
        $descricao = $data['descricao_receita'] ?? null;
        $id_paciente = $data['id_paciente'] ?? null;
        $id_nutricionista = $data['id_nutricionista'] ?? null;

        if (!$descricao || !$id_paciente) {
            echo json_encode(['success' => false, 'error' => 'Descrição e ID do paciente são obrigatórios.']);
            exit;
        }

        try {
            $receita = new Receita(null, $data_inicio, $data_termino, $descricao);
            $result = $this->service->criarReceitaCompleta($receita, $id_paciente, $id_nutricionista);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Receita criada com sucesso.', 'id' => $result]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erro ao criar receita.']);
            }
        } catch (\Exception $e) {
            error_log("Erro ao criar receita: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro interno do servidor.']);
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
            $receitas = $this->service->buscarPorPaciente($id_paciente);
            echo json_encode(['success' => true, 'data' => $receitas]);
        } catch (\Exception $e) {
            error_log("Erro ao buscar receitas por paciente: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro ao buscar receitas do paciente.']);
        }
        exit;
    }

    public function validarPorMedico() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_receita = $data['id_receita'] ?? null;
        $id_medico = $data['id_medico'] ?? null;

        if (!$id_receita || !$id_medico) {
            echo json_encode(['success' => false, 'error' => 'ID da receita e ID do médico são obrigatórios.']);
            exit;
        }

        try {
            $result = $this->service->validarPorMedico($id_receita, $id_medico);
            
            if ($result !== false) {
                echo json_encode(['success' => true, 'message' => 'Receita validada com sucesso.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erro ao validar receita.']);
            }
        } catch (\Exception $e) {
            error_log("Erro ao validar receita: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro interno do servidor.']);
        }
        exit;
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
