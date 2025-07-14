<?php
namespace Htdocs\Src\Services;

use Htdocs\Src\Models\Repository\DietaRepository;
use Htdocs\Src\Models\Entity\Dieta;

class DietaService {
    private $dietaRepository;

    public function __construct(DietaRepository $dietaRepository) {
        $this->dietaRepository = $dietaRepository;
    }

    public function getDietaRepository() {
        return $this->dietaRepository;
    }

    public function criar(Dieta $dieta) {
        return $this->dietaRepository->save($dieta);
    }

    public function listar() {
        return $this->dietaRepository->findAll();
    }

    public function buscarPorId($id_dieta) {
        return $this->dietaRepository->findById($id_dieta);
    }

    public function buscarPorPaciente($id_paciente) {
        return $this->dietaRepository->findByPacienteId($id_paciente);
    }

    public function buscarPorNutricionista($id_nutricionista) {
        return $this->dietaRepository->findByNutricionistaId($id_nutricionista);
    }

    public function atualizar(Dieta $dieta) {
        return $this->dietaRepository->update($dieta);
    }

    public function deletar($id_dieta) {
        return $this->dietaRepository->delete($id_dieta);
    }

    public function associarPaciente($id_dieta, $id_paciente) {
        return $this->dietaRepository->associarPaciente($id_dieta, $id_paciente);
    }

    public function associarNutricionista($id_dieta, $id_nutricionista) {
        return $this->dietaRepository->associarNutricionista($id_dieta, $id_nutricionista);
    }

    public function criarDietaCompleta(Dieta $dieta, $id_paciente, $id_nutricionista) {
        $id_dieta = $this->criar($dieta);
        if ($id_dieta) {
            $this->associarPaciente($id_dieta, $id_paciente);
            $this->associarNutricionista($id_dieta, $id_nutricionista);
        }
        return $id_dieta;
    }
}
?>
