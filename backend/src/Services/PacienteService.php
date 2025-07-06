<?php
namespace Htdocs\Src\Services;

use Htdocs\Src\Models\Repository\PacienteRepository;
use Htdocs\Src\Models\Entity\Paciente;

class PacienteService {
    private $pacienteRepository;

    public function __construct(PacienteRepository $pacienteRepository) {
        $this->pacienteRepository = $pacienteRepository;
    }

    public function getPacienteRepository() {
        return $this->pacienteRepository;
    }

    public function criar(Paciente $paciente) {
        try {
            return $this->pacienteRepository->save($paciente);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function listar() {
        return $this->pacienteRepository->findAll();
    }

    public function mostrarConta($id_usuario) {
        return $this->pacienteRepository->findById($id_usuario);
    }

    public function atualizarConta(Paciente $paciente) {
        return $this->pacienteRepository->update($paciente);
    }

    public function deletarConta($id_usuario) {
        return $this->pacienteRepository->delete($id_usuario);
    }
}
?>