<?php
namespace Htdocs\Src\Services;

use Htdocs\Src\Models\Repository\NutricionistaRepository;
use Htdocs\Src\Models\Entity\Nutricionista;

class NutricionistaService {
    private $nutricionistaRepository;

    public function __construct(NutricionistaRepository $nutricionistaRepository) {
        $this->nutricionistaRepository = $nutricionistaRepository;
    }

    public function getNutricionistaRepository() {
        return $this->nutricionistaRepository;
    }

    public function criar(Nutricionista $nutricionista) {
        try {
            return $this->nutricionistaRepository->save($nutricionista);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function listar() {
        return $this->nutricionistaRepository->findAll();
    }

    public function atualizarConta($nutricionista) {
        try {
            return $this->nutricionistaRepository->update($nutricionista);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function deletarConta($id_usuario) {
        return $this->nutricionistaRepository->delete($id_usuario);
    }

    public function mostrarConta($id_usuario) {
        return $this->nutricionistaRepository->findById($id_usuario);
    }
}
?>