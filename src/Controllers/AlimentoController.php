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
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $descricao_alimento = $data['descricao_alimento'] ?? null;
        $dados_nutricionais = $data['dados_nutricionais'] ?? null;

        if (!$descricao_alimento) {
            echo json_encode(['error' => 'Descrição do alimento é obrigatória.']);
            return;
        }

        if (!$this->service->validarDadosNutricionais($dados_nutricionais)) {
            echo json_encode(['error' => 'Dados nutricionais inválidos.']);
            return;
        }

        $alimento = new Alimento(
            null,
            $descricao_alimento,
            $dados_nutricionais
        );

        $result = $this->service->criar($alimento);

        if ($result) {
            echo json_encode(['success' => 'Alimento cadastrado com sucesso.', 'id' => $result]);
        } else {
            echo json_encode(['error' => 'Erro ao cadastrar alimento.']);
        }
    }

    public function listar() {
        $alimentos = $this->service->listar();
        echo json_encode($alimentos);
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
        $descricao = $_GET['descricao'] ?? null;
        
        if (!$descricao) {
            echo json_encode(['error' => 'Descrição é obrigatória para busca.']);
            return;
        }

        $alimentos = $this->service->buscarPorDescricao($descricao);
        echo json_encode($alimentos);
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
        $id_diario = $_GET['id_diario'] ?? null;
        
        if (!$id_diario) {
            echo json_encode(['error' => 'ID do diário é obrigatório.']);
            return;
        }

        $alimentos = $this->service->buscarPorDiario($id_diario);
        echo json_encode($alimentos);
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
}
?>
