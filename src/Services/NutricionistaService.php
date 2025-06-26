<?php
namespace Htdocs\Src\Services;

use Htdocs\Src\Models\Repository\NutricionistaRepository;
use Htdocs\Src\Models\Entity\Nutricionista;

class NutricionistaService {
    private $nutricionistaRepository;

    public function __construct(NutricionistaRepository $nutricionistaRepository) {
        $this->nutricionistaRepository = $nutricionistaRepository;
    }

    public function criar(Nutricionista $nutricionista) {
        return $this->nutricionistaRepository->save($nutricionista);
    }

    public function listar() {
        return $this->nutricionistaRepository->findAll();
    }

    public function isReady() {
        return $this->nutricionistaRepository->isConnected();
    }
}
?>