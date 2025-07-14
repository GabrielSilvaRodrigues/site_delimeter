<?php
namespace Htdocs\Src\Controllers;

use Htdocs\Src\Services\AlimentoService;
use Htdocs\Src\Models\Entity\Alimento;

class AlimentoController {
    private $service;

    public function __construct(AlimentoService $service) {
        $this->service = $service;
    }

    public function criar() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $descricao_alimento = $data['descricao_alimento'] ?? null;
        $dados_nutricionais = $data['dados_nutricionais'] ?? null;

        if (!$descricao_alimento) {
            echo json_encode(['success' => false, 'error' => 'Descrição do alimento é obrigatória.']);
            exit;
        }

        try {
            $alimento = new Alimento(
                null,
                $descricao_alimento,
                $dados_nutricionais
            );

            $result = $this->service->criar($alimento);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Alimento cadastrado com sucesso.', 'id' => $result]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erro ao cadastrar alimento.']);
            }
        } catch (\Exception $e) {
            error_log("Erro ao criar alimento: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro interno do servidor.']);
        }
        exit;
    }

    public function listar() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        try {
            $alimentos = $this->service->listar();
            echo json_encode(['success' => true, 'data' => $alimentos]);
        } catch (\Exception $e) {
            error_log("Erro ao listar alimentos: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro ao buscar alimentos.']);
        }
        exit;
    }

    public function buscarPorId() {
        $id_alimento = $_GET['id'] ?? null;
        
        if (!$id_alimento) {
            echo json_encode(['error' => 'ID do alimento é obrigatório.']);
            return;
        }

        $alimento = $this->service->buscarPorId($id_alimento);
        echo json_encode($alimento);
    }

    public function buscarPorDescricao() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $descricao = $_GET['descricao'] ?? null;
        
        if (!$descricao || strlen(trim($descricao)) < 2) {
            echo json_encode(['success' => false, 'error' => 'Digite pelo menos 2 caracteres para buscar.']);
            exit;
        }

        try {
            $alimentos = $this->service->buscarPorDescricao(trim($descricao));
            echo json_encode(['success' => true, 'data' => $alimentos]);
        } catch (\Exception $e) {
            error_log("Erro ao buscar alimentos por descrição: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro ao buscar alimentos.']);
        }
        exit;
    }

    public function buscarPorDieta() {
        $id_dieta = $_GET['id_dieta'] ?? null;
        
        if (!$id_dieta) {
            echo json_encode(['error' => 'ID da dieta é obrigatório.']);
            return;
        }

        $alimentos = $this->service->buscarPorDieta($id_dieta);
        echo json_encode($alimentos);
    }

    public function buscarPorDiario() {
        $this->limparOutput();
        header('Content-Type: application/json');
        
        $id_diario = $_GET['id_diario'] ?? null;
        
        if (!$id_diario) {
            echo json_encode(['success' => false, 'error' => 'ID do diário é obrigatório.']);
            exit;
        }

        try {
            $alimentos = $this->service->buscarPorDiario($id_diario);
            echo json_encode(['success' => true, 'data' => $alimentos]);
        } catch (\Exception $e) {
            error_log("Erro ao buscar alimentos do diário: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro ao buscar alimentos do diário.']);
        }
        exit;
    }

    public function atualizar() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_alimento = $data['id_alimento'] ?? null;
        $descricao_alimento = $data['descricao_alimento'] ?? null;
        $dados_nutricionais = $data['dados_nutricionais'] ?? null;

        if (!$id_alimento) {
            echo json_encode(['error' => 'ID do alimento é obrigatório.']);
            return;
        }

        if (!$this->service->validarDadosNutricionais($dados_nutricionais)) {
            echo json_encode(['error' => 'Dados nutricionais inválidos.']);
            return;
        }

        $alimento = new Alimento(
            $id_alimento,
            $descricao_alimento,
            $dados_nutricionais
        );

        $result = $this->service->atualizar($alimento);
        
        if ($result !== false) {
            echo json_encode(['success' => 'Alimento atualizado com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao atualizar alimento.']);
        }
    }

    public function deletar() {
        $id_alimento = $_GET['id'] ?? $_POST['id'] ?? null;
        
        if (!$id_alimento) {
            echo json_encode(['error' => 'ID do alimento é obrigatório.']);
            return;
        }

        $result = $this->service->deletar($id_alimento);
        
        if ($result !== false) {
            echo json_encode(['success' => 'Alimento deletado com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao deletar alimento.']);
        }
    }

    // Método para busca avançada
    public function buscarAvancado() {
        $termo = $_GET['termo'] ?? '';
        $tipo_busca = $_GET['tipo'] ?? 'descricao'; // descricao, nutrientes, etc.
        
        switch ($tipo_busca) {
            case 'descricao':
                $resultado = $this->service->buscarPorDescricao($termo);
                break;
            default:
                $resultado = $this->service->buscarPorDescricao($termo);
                break;
        }
        
        echo json_encode($resultado);
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
