<?php
namespace Htdocs\Src\Services;

use Htdocs\Src\Models\Repository\ConsultaRepository;
use Htdocs\Src\Models\Entity\Consulta;

class ConsultaService {
    private $consultaRepository;

    public function __construct(ConsultaRepository $consultaRepository) {
        $this->consultaRepository = $consultaRepository;
    }

    public function getConsultaRepository() {
        return $this->consultaRepository;
    }

    public function criar(Consulta $consulta) {
        return $this->consultaRepository->save($consulta);
    }

    public function listar() {
        return $this->consultaRepository->findAll();
    }

    public function buscarPorId($id_consulta) {
        return $this->consultaRepository->findById($id_consulta);
    }

    public function buscarPorPaciente($id_paciente) {
        return $this->consultaRepository->findByPacienteId($id_paciente);
    }

    public function buscarPorMedico($id_medico) {
        return $this->consultaRepository->findByMedicoId($id_medico);
    }

    public function buscarPorNutricionista($id_nutricionista) {
        return $this->consultaRepository->findByNutricionistaId($id_nutricionista);
    }

    public function buscarPorData($data_consulta) {
        return $this->consultaRepository->findByDate($data_consulta);
    }

    public function buscarPorPeriodo($data_inicio, $data_fim) {
        return $this->consultaRepository->findByDateRange($data_inicio, $data_fim);
    }

    public function atualizar(Consulta $consulta) {
        return $this->consultaRepository->update($consulta);
    }

    public function deletar($id_consulta) {
        return $this->consultaRepository->delete($id_consulta);
    }

    public function associarPaciente($id_consulta, $id_paciente) {
        return $this->consultaRepository->associarPaciente($id_consulta, $id_paciente);
    }

    public function associarMedico($id_consulta, $id_medico) {
        return $this->consultaRepository->associarMedico($id_consulta, $id_medico);
    }

    public function associarNutricionista($id_consulta, $id_nutricionista) {
        return $this->consultaRepository->associarNutricionista($id_consulta, $id_nutricionista);
    }

    public function agendarConsulta(Consulta $consulta, $id_paciente, $id_profissional = null, $tipo_profissional = null) {
        $id_consulta = $this->criar($consulta);
        if ($id_consulta) {
            $this->associarPaciente($id_consulta, $id_paciente);
            
            if ($id_profissional && $tipo_profissional) {
                if ($tipo_profissional === 'medico') {
                    $this->associarMedico($id_consulta, $id_profissional);
                } elseif ($tipo_profissional === 'nutricionista') {
                    $this->associarNutricionista($id_consulta, $id_profissional);
                }
            }
        }
        return $id_consulta;
    }

    public function obterConsultasHoje() {
        return $this->buscarPorData(date('Y-m-d'));
    }

    public function obterConsultasSemana() {
        $data_inicio = date('Y-m-d');
        $data_fim = date('Y-m-d', strtotime('+7 days'));
        return $this->buscarPorPeriodo($data_inicio, $data_fim);
    }
}
?>
