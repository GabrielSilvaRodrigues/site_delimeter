<?php
namespace Htdocs\Src\Services;

use Htdocs\Src\Models\Repository\MedicoRepository;
use Htdocs\Src\Models\Entity\Medico;

class MedicoService {
    private $medicoRepository;

    public function __construct(MedicoRepository $medicoRepository) {
        $this->medicoRepository = $medicoRepository;
    }

    public function criar(Medico $medico) {
        return $this->medicoRepository->save($medico);
    }

    public function listar() {
        return $this->medicoRepository->findAll();
    }
}
?>