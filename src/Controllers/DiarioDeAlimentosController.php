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
        // Garantir que sempre retornamos JSON limpo
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_paciente = $data['id_paciente'] ?? null;
        $data_diario = $data['data_diario'] ?? date('Y-m-d');
        $descricao_diario = $data['descricao_diario'] ?? null;

        if (!$id_paciente) {
            echo json_encode(['success' => false, 'error' => 'ID do paciente é obrigatório.']);
            exit;
        }

        try {
            $diario = new DiarioDeAlimentos(
                null,
                (int)$id_paciente,
                $data_diario,
                $descricao_diario
            );

            $result = $this->service->criar($diario);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Registro do diário criado com sucesso.', 'id' => $result]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erro ao criar registro do diário.']);
            }
        } catch (\Exception $e) {
            error_log("Erro ao criar diário: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro interno do servidor.']);
        }
        exit;
    }

    public function listar() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        try {
            $registros = $this->service->listar();
            echo json_encode(['success' => true, 'data' => $registros]);
        } catch (\Exception $e) {
            error_log("Erro ao listar diários: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro ao buscar registros.']);
        }
        exit;
    }

    public function buscarPorId() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $id_diario = $_GET['id'] ?? null;
        
        if (!$id_diario) {
            echo json_encode(['success' => false, 'error' => 'ID do diário é obrigatório.']);
            exit;
        }

        try {
            $registro = $this->service->buscarPorId($id_diario);
            echo json_encode(['success' => true, 'data' => $registro]);
        } catch (\Exception $e) {
            error_log("Erro ao buscar diário por ID: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro ao buscar registro.']);
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
            $registros = $this->service->buscarPorPaciente($id_paciente);
            echo json_encode(['success' => true, 'data' => $registros]);
        } catch (\Exception $e) {
            error_log("Erro ao buscar diários por paciente: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro ao buscar registros do paciente.']);
        }
        exit;
    }

    public function buscarPorPacienteEData() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $id_paciente = $_GET['id_paciente'] ?? null;
        $data_diario = $_GET['data_diario'] ?? null;
        
        if (!$id_paciente || !$data_diario) {
            echo json_encode(['success' => false, 'error' => 'ID do paciente e data são obrigatórios.']);
            exit;
        }

        try {
            $registro = $this->service->buscarPorPacienteEData($id_paciente, $data_diario);
            echo json_encode(['success' => true, 'data' => $registro]);
        } catch (\Exception $e) {
            error_log("Erro ao buscar diário por paciente e data: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro ao buscar registro.']);
        }
        exit;
    }

    public function buscarPorPeriodo() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $id_paciente = $_GET['id_paciente'] ?? null;
        $data_inicio = $_GET['data_inicio'] ?? null;
        $data_fim = $_GET['data_fim'] ?? null;
        
        if (!$id_paciente || !$data_inicio || !$data_fim) {
            echo json_encode(['success' => false, 'error' => 'ID do paciente, data início e data fim são obrigatórios.']);
            exit;
        }

        try {
            $registros = $this->service->buscarPorPeriodo($id_paciente, $data_inicio, $data_fim);
            echo json_encode(['success' => true, 'data' => $registros]);
        } catch (\Exception $e) {
            error_log("Erro ao buscar diários por período: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro ao buscar registros do período.']);
        }
        exit;
    }

    public function atualizar() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_diario = $data['id_diario'] ?? null;
        $id_paciente = $data['id_paciente'] ?? null;
        $data_diario = $data['data_diario'] ?? null;
        $descricao_diario = $data['descricao_diario'] ?? null;

        if (!$id_diario) {
            echo json_encode(['success' => false, 'error' => 'ID do diário é obrigatório.']);
            exit;
        }

        try {
            $diario = new DiarioDeAlimentos(
                $id_diario,
                $id_paciente,
                $data_diario,
                $descricao_diario
            );

            $result = $this->service->atualizar($diario);
            
            if ($result !== false) {
                echo json_encode(['success' => true, 'message' => 'Registro do diário atualizado com sucesso.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erro ao atualizar registro do diário.']);
            }
        } catch (\Exception $e) {
            error_log("Erro ao atualizar diário: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro interno do servidor.']);
        }
        exit;
    }

    public function deletar() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        // Aceitar tanto GET quanto POST, e também JSON
        $data = json_decode(file_get_contents("php://input"), true);
        $id_diario = $data['id'] ?? $_GET['id'] ?? $_POST['id'] ?? $_GET['id_diario'] ?? $_POST['id_diario'] ?? null;
        
        error_log("DiarioDeAlimentosController::deletar - ID recebido: " . ($id_diario ?? 'null'));
        error_log("DiarioDeAlimentosController::deletar - GET: " . print_r($_GET, true));
        error_log("DiarioDeAlimentosController::deletar - POST: " . print_r($_POST, true));
        error_log("DiarioDeAlimentosController::deletar - JSON: " . print_r($data, true));
        
        if (!$id_diario) {
            echo json_encode(['success' => false, 'error' => 'ID do diário é obrigatório.']);
            exit;
        }

        try {
            $result = $this->service->deletar($id_diario);
            
            if ($result !== false) {
                echo json_encode(['success' => true, 'message' => 'Registro do diário deletado com sucesso.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erro ao deletar registro do diário.']);
            }
        } catch (\Exception $e) {
            error_log("Erro ao deletar diário: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro interno do servidor: ' . $e->getMessage()]);
        }
        exit;
    }

    public function associarAlimento() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_diario = $data['id_diario'] ?? null;
        $id_alimento = $data['id_alimento'] ?? null;

        if (!$id_diario || !$id_alimento) {
            echo json_encode(['success' => false, 'error' => 'ID do diário e ID do alimento são obrigatórios.']);
            exit;
        }

        try {
            // Verificar se a associação já existe
            $existeAssociacao = $this->service->getDiarioRepository()->verificarAssociacaoAlimento($id_diario, $id_alimento);
            
            if ($existeAssociacao) {
                echo json_encode(['success' => true, 'message' => 'Alimento já está associado ao diário.']);
                exit;
            }

            $result = $this->service->associarAlimento($id_diario, $id_alimento);
            
            if ($result !== false) {
                echo json_encode(['success' => true, 'message' => 'Alimento associado ao diário com sucesso.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erro ao associar alimento ao diário.']);
            }
        } catch (\Exception $e) {
            error_log("Erro ao associar alimento: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro interno do servidor: ' . $e->getMessage()]);
        }
        exit;
    }

    public function removerAlimento() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_diario = $data['id_diario'] ?? $_GET['id_diario'] ?? null;
        $id_alimento = $data['id_alimento'] ?? $_GET['id_alimento'] ?? null;

        if (!$id_diario || !$id_alimento) {
            echo json_encode(['success' => false, 'error' => 'ID do diário e ID do alimento são obrigatórios.']);
            exit;
        }

        try {
            $result = $this->service->removerAlimento($id_diario, $id_alimento);
            
            if ($result !== false) {
                echo json_encode(['success' => true, 'message' => 'Alimento removido do diário com sucesso.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erro ao remover alimento do diário.']);
            }
        } catch (\Exception $e) {
            error_log("Erro ao remover alimento: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro interno do servidor.']);
        }
        exit;
    }

    public function obterDiarioSemanal() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $id_paciente = $_GET['id_paciente'] ?? null;
        $data_inicio = $_GET['data_inicio'] ?? null;
        
        if (!$id_paciente) {
            echo json_encode(['success' => false, 'error' => 'ID do paciente é obrigatório.']);
            exit;
        }

        try {
            $registros = $this->service->obterDiarioSemanal($id_paciente, $data_inicio);
            echo json_encode(['success' => true, 'data' => $registros]);
        } catch (\Exception $e) {
            error_log("Erro ao obter diário semanal: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro ao buscar registros semanais.']);
        }
        exit;
    }

    /**
     * Método para mostrar a página de diário de alimentos
     */
    public function mostrarDiarioAlimentos() {
        $formPath = dirname(__DIR__, 2) . '/view/paciente/diario-alimentos.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            http_response_code(404);
            echo "Erro: Página não encontrada";
        }
    }

    /**
     * Método para limpar output buffer e garantir JSON limpo
     */
    private function limparOutput() {
        // Limpar qualquer output anterior
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Iniciar novo buffer
        ob_start();
        
        // Configurar headers para evitar cache
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
    }
}
?>
