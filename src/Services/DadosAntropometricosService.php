<?php
namespace Htdocs\Src\Services;

use Htdocs\Src\Models\Repository\DadosAntropometricosRepository;
use Htdocs\Src\Models\Entity\DadosAntropometricos;

class DadosAntropometricosService {
    private $dadosRepository;

    public function __construct(DadosAntropometricosRepository $dadosRepository) {
        $this->dadosRepository = $dadosRepository;
    }

    public function getDadosRepository() {
        return $this->dadosRepository;
    }

    public function criar(DadosAntropometricos $dados) {
        try {
            $result = $this->dadosRepository->save($dados);
            error_log("DadosAntropometricosService: Dados criados com ID: " . $result);
            return $result;
        } catch (\Exception $e) {
            error_log("DadosAntropometricosService: Erro ao criar dados: " . $e->getMessage());
            throw $e;
        }
    }

    public function listar() {
        try {
            $dados = $this->dadosRepository->findAll();
            error_log("DadosAntropometricosService: Listando " . count($dados) . " registros");
            return $dados;
        } catch (\Exception $e) {
            error_log("DadosAntropometricosService: Erro ao listar dados: " . $e->getMessage());
            throw $e;
        }
    }

    public function buscarPorPaciente($id_paciente) {
        try {
            $dados = $this->dadosRepository->findByPacienteId($id_paciente);
            error_log("DadosAntropometricosService: Encontrados " . count($dados) . " registros para paciente " . $id_paciente);
            return $dados;
        } catch (\Exception $e) {
            error_log("DadosAntropometricosService: Erro ao buscar por paciente: " . $e->getMessage());
            throw $e;
        }
    }

    public function buscarUltimaMedida($id_paciente) {
        return $this->dadosRepository->findLatestByPacienteId($id_paciente);
    }

    public function buscarPorId($id_medida) {
        return $this->dadosRepository->findById($id_medida);
    }

    public function atualizar(DadosAntropometricos $dados) {
        return $this->dadosRepository->update($dados);
    }

    public function deletar($id_medida) {
        return $this->dadosRepository->delete($id_medida);
    }

    public function calcularIMC($altura, $peso) {
        if ($altura > 0 && $peso > 0) {
            return $peso / ($altura * $altura);
        }
        return null;
    }

    public function classificarIMC($imc) {
        if ($imc < 18.5) {
            return "Abaixo do peso";
        } elseif ($imc < 25) {
            return "Peso normal";
        } elseif ($imc < 30) {
            return "Sobrepeso";
        } else {
            return "Obesidade";
        }
    }
}
?>
