<?php
namespace Htdocs\Src\Services;

use Htdocs\Src\Models\Repository\AlimentoRepository;
use Htdocs\Src\Models\Entity\Alimento;

class AlimentoService {
    private $alimentoRepository;

    public function __construct(AlimentoRepository $alimentoRepository) {
        $this->alimentoRepository = $alimentoRepository;
    }

    public function getAlimentoRepository() {
        return $this->alimentoRepository;
    }

    public function criar(Alimento $alimento) {
        return $this->alimentoRepository->save($alimento);
    }

    public function listar() {
        return $this->alimentoRepository->findAll();
    }

    public function buscarPorId($id_alimento) {
        return $this->alimentoRepository->findById($id_alimento);
    }

    public function buscarPorDescricao($descricao) {
        return $this->alimentoRepository->findByDescricao($descricao);
    }

    public function buscarPorDieta($id_dieta) {
        return $this->alimentoRepository->findByDietaId($id_dieta);
    }

    public function buscarPorDiario($id_diario) {
        return $this->alimentoRepository->findByDiarioId($id_diario);
    }

    public function atualizar(Alimento $alimento) {
        return $this->alimentoRepository->update($alimento);
    }

    public function deletar($id_alimento) {
        return $this->alimentoRepository->delete($id_alimento);
    }

    public function validarDadosNutricionais($dados_nutricionais) {
        // Validação básica dos dados nutricionais
        if (empty($dados_nutricionais)) {
            return false;
        }
        // Aqui poderia haver validações mais específicas
        return true;
    }
}
?>
