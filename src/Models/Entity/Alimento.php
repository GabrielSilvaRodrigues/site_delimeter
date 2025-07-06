<?php
namespace Htdocs\Src\Models\Entity;

class Alimento
{
    private ?int $id_alimento;
    private ?string $descricao_alimento;
    private ?string $dados_nutricionais;

    public function __construct(
        ?int $id_alimento,
        ?string $descricao_alimento = null,
        ?string $dados_nutricionais = null
    ) {
        $this->id_alimento = $id_alimento;
        $this->descricao_alimento = $descricao_alimento;
        $this->dados_nutricionais = $dados_nutricionais;
    }

    public function getIdAlimento(): ?int
    {
        return $this->id_alimento;
    }

    public function setIdAlimento(?int $id_alimento): void
    {
        $this->id_alimento = $id_alimento;
    }

    public function getDescricaoAlimento(): ?string
    {
        return $this->descricao_alimento;
    }

    public function setDescricaoAlimento(?string $descricao_alimento): void
    {
        $this->descricao_alimento = $descricao_alimento;
    }

    public function getDadosNutricionais(): ?string
    {
        return $this->dados_nutricionais;
    }

    public function setDadosNutricionais(?string $dados_nutricionais): void
    {
        $this->dados_nutricionais = $dados_nutricionais;
    }
}
?>
