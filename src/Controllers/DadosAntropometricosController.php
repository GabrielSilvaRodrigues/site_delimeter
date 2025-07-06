<?php
namespace Htdocs\Src\Controllers;

use Htdocs\Src\Services\DadosAntropometricosService;
use Htdocs\Src\Models\Entity\DadosAntropometricos;

class DadosAntropometricosController {
    private $service;

    public function __construct(DadosAntropometricosService $service) {
        $this->service = $service;
        error_log("DadosAntropometricosController: Controller inicializado");
    }

    public function criar() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !is_array($data)) $data = $_POST;

        $id_paciente = $data['id_paciente'] ?? null;
        $sexo_paciente = $data['sexo_paciente'] ?? null;
        $altura_paciente = $data['altura_paciente'] ?? null; // em metros
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
            // Salvar dados antropométricos na sessão
            $this->salvarDadosNaSessao($sexo_paciente, $altura_paciente, $peso_paciente, $data_medida);
            
            echo json_encode(['success' => 'Dados antropométricos cadastrados com sucesso.', 'id' => $result]);
        } else {
            echo json_encode(['error' => 'Erro ao cadastrar dados antropométricos.']);
        }
    }

    public function listar() {
        error_log("DadosAntropometricosController: listar() chamado");
        try {
            $dados = $this->service->listar();
            error_log("DadosAntropometricosController: listar() retornou " . count($dados) . " registros");
            echo json_encode($dados);
        } catch (\Exception $e) {
            error_log("DadosAntropometricosController: Erro em listar(): " . $e->getMessage());
            echo json_encode(['error' => 'Erro ao listar dados: ' . $e->getMessage()]);
        }
    }

    public function buscarPorPaciente() {
        $id_paciente = $_GET['id_paciente'] ?? null;
        
        error_log("DadosAntropometricosController: buscarPorPaciente chamado com ID: " . ($id_paciente ?? 'null'));
        
        if (!$id_paciente) {
            error_log("DadosAntropometricosController: ID do paciente não fornecido");
            echo json_encode(['error' => 'ID do paciente é obrigatório.']);
            return;
        }

        try {
            $dados = $this->service->buscarPorPaciente($id_paciente);
            error_log("DadosAntropometricosController: Dados encontrados: " . print_r($dados, true));
            echo json_encode($dados);
        } catch (\Exception $e) {
            error_log("DadosAntropometricosController: Erro ao buscar dados: " . $e->getMessage());
            echo json_encode(['error' => 'Erro ao buscar dados: ' . $e->getMessage()]);
        }
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
        $altura_paciente = $data['altura_paciente'] ?? null; // em metros
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
            // Salvar dados antropométricos atualizados na sessão
            $this->salvarDadosNaSessao($sexo_paciente, $altura_paciente, $peso_paciente, $data_medida);
            
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

    /**
     * Método auxiliar para salvar dados antropométricos na sessão
     */
    private function salvarDadosNaSessao($sexo, $altura, $peso, $data_medida) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Garantir que existe a estrutura de dados na sessão
        if (!isset($_SESSION['dados_antropometricos'])) {
            $_SESSION['dados_antropometricos'] = [];
        }
        
        // Salvar dados mais recentes
        $_SESSION['dados_antropometricos'] = [
            'sexo_paciente' => $sexo,
            'altura_paciente' => $altura,
            'peso_paciente' => $peso,
            'data_medida' => $data_medida,
            'ultima_atualizacao' => date('Y-m-d H:i:s')
        ];
        
        // Calcular e salvar IMC se temos altura e peso
        if ($altura && $peso) {
            $imc = $this->service->calcularIMC($altura, $peso);
            $classificacao = $this->service->classificarIMC($imc);
            
            $_SESSION['dados_antropometricos']['imc'] = $imc;
            $_SESSION['dados_antropometricos']['classificacao_imc'] = $classificacao;
        }
        
        error_log("Dados antropométricos salvos na sessão: " . print_r($_SESSION['dados_antropometricos'], true));
    }

    /**
     * Método para carregar dados antropométricos na sessão (se não existirem)
     */
    public function carregarDadosNaSessao() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Se já temos dados na sessão, não precisa carregar
        if (isset($_SESSION['dados_antropometricos']) && !empty($_SESSION['dados_antropometricos'])) {
            return true;
        }
        
        // Buscar dados do paciente na sessão
        $id_paciente = $_SESSION['paciente']['id_paciente'] ?? null;
        
        if (!$id_paciente) {
            return false;
        }
        
        try {
            // Buscar última medida do paciente
            $ultimaMedida = $this->service->buscarUltimaMedida($id_paciente);
            
            if ($ultimaMedida) {
                $this->salvarDadosNaSessao(
                    $ultimaMedida['sexo_paciente'],
                    $ultimaMedida['altura_paciente'],
                    $ultimaMedida['peso_paciente'],
                    $ultimaMedida['data_medida']
                );
                return true;
            }
        } catch (\Exception $e) {
            error_log("Erro ao carregar dados antropométricos na sessão: " . $e->getMessage());
        }
        
        return false;
    }
}
?>
