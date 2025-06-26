<?php
namespace Htdocs\Src\Services;
use Htdocs\Src\Models\Repository\PacienteRepository;
use Htdocs\Src\Models\Entity\Paciente;

class PacienteService {
    private $pacienteRepository;

    public function __construct(PacienteRepository $pacienteRepository) {
        $this->pacienteRepository = $pacienteRepository;
    }

    public function criar(Paciente $paciente) {
        return $this->pacienteRepository->save($paciente);
    }

    public function isReady() {
        return $this->pacienteRepository->isConnected();
    }
}
?>