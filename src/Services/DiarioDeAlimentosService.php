<?php
namespace Htdocs\Src\Services;

use Htdocs\Src\Models\Repository\DiarioDeAlimentosRepository;
use Htdocs\Src\Models\Entity\DiarioDeAlimentos;

class DiarioDeAlimentosService {
    private $diarioRepository;

    public function __construct(DiarioDeAlimentosRepository $diarioRepository) {
        $this->diarioRepository = $diarioRepository;
    }

    public function getDiarioRepository() {
        return $this->diarioRepository;
    }

    public function criar(DiarioDeAlimentos $diario) {
        return $this->diarioRepository->save($diario);
    }

    public function listar() {
        return $this->diarioRepository->findAll();
    }

    public function buscarPorId($id_diario) {
        return $this->diarioRepository->findById($id_diario);
    }

    public function buscarPorPaciente($id_paciente) {
        return $this->diarioRepository->findByPacienteId($id_paciente);
    }

    public function buscarPorPacienteEData($id_paciente, $data_diario) {
        return $this->diarioRepository->findByPacienteAndDate($id_paciente, $data_diario);
    }

    public function buscarPorPeriodo($id_paciente, $data_inicio, $data_fim) {
        return $this->diarioRepository->findByDateRange($id_paciente, $data_inicio, $data_fim);
    }

    public function atualizar(DiarioDeAlimentos $diario) {
        return $this->diarioRepository->update($diario);
    }

    public function deletar($id_diario) {
        return $this->diarioRepository->delete($id_diario);
    }

    public function associarAlimento($id_diario, $id_alimento) {
        return $this->diarioRepository->associarAlimento($id_diario, $id_alimento);
    }

    public function removerAlimento($id_diario, $id_alimento) {
        return $this->diarioRepository->removerAlimento($id_diario, $id_alimento);
    }

    public function criarDiarioComAlimentos(DiarioDeAlimentos $diario, array $alimentos) {
        $id_diario = $this->criar($diario);
        if ($id_diario && !empty($alimentos)) {
            foreach ($alimentos as $id_alimento) {
                $this->associarAlimento($id_diario, $id_alimento);
            }
        }
        return $id_diario;
    }

    public function obterDiarioSemanal($id_paciente, $data_inicio = null) {
        if (!$data_inicio) {
            $data_inicio = date('Y-m-d', strtotime('-7 days'));
        }
        $data_fim = date('Y-m-d', strtotime($data_inicio . ' +6 days'));
        return $this->buscarPorPeriodo($id_paciente, $data_inicio, $data_fim);
    }
}
?>
