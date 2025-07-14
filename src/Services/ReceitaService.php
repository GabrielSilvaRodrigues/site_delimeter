<?php
namespace Htdocs\Src\Services;

use Htdocs\Src\Models\Repository\ReceitaRepository;
use Htdocs\Src\Models\Entity\Receita;

class ReceitaService {
    private $receitaRepository;

    public function __construct(ReceitaRepository $receitaRepository) {
        $this->receitaRepository = $receitaRepository;
    }

    public function getReceitaRepository() {
        return $this->receitaRepository;
    }

    public function criar(Receita $receita) {
        return $this->receitaRepository->save($receita);
    }

    public function listar() {
        return $this->receitaRepository->findAll();
    }

    public function buscarPorId($id_receita) {
        return $this->receitaRepository->findById($id_receita);
    }

    public function buscarPorPaciente($id_paciente) {
        return $this->receitaRepository->findByPacienteId($id_paciente);
    }

    public function buscarPorNutricionista($id_nutricionista) {
        return $this->receitaRepository->findByNutricionistaId($id_nutricionista);
    }

    public function buscarValidadasPorMedico($id_medico) {
        return $this->receitaRepository->findValidatedByMedicoId($id_medico);
    }

    public function atualizar(Receita $receita) {
        return $this->receitaRepository->update($receita);
    }

    public function deletar($id_receita) {
        return $this->receitaRepository->delete($id_receita);
    }

    public function associarPaciente($id_receita, $id_paciente) {
        return $this->receitaRepository->associarPaciente($id_receita, $id_paciente);
    }

    public function associarNutricionista($id_receita, $id_nutricionista) {
        return $this->receitaRepository->associarNutricionista($id_receita, $id_nutricionista);
    }

    public function validarPorMedico($id_receita, $id_medico) {
        return $this->receitaRepository->validarPorMedico($id_receita, $id_medico);
    }

    public function criarReceitaCompleta(Receita $receita, $id_paciente, $id_nutricionista) {
        $id_receita = $this->criar($receita);
        if ($id_receita) {
            $this->associarPaciente($id_receita, $id_paciente);
            $this->associarNutricionista($id_receita, $id_nutricionista);
        }
        return $id_receita;
    }

    public function verificarValidacao($id_receita, $id_medico) {
        $receitas_validadas = $this->buscarValidadasPorMedico($id_medico);
        foreach ($receitas_validadas as $receita) {
            if ($receita['id_receita'] == $id_receita) {
                return true;
            }
        }
        return false;
    }
}
?>
