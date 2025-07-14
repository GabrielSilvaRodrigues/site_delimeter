<?php
namespace Htdocs\Src\Models\Entity;

class Receita
{
    private ?int $id_receita;
    private ?string $data_inicio_receita;
    private ?string $data_termino_receita;
    private ?string $descricao_receita;

    public function __construct(
        ?int $id_receita,
        ?string $data_inicio_receita = null,
        ?string $data_termino_receita = null,
        ?string $descricao_receita = null
    ) {
        $this->id_receita = $id_receita;
        $this->data_inicio_receita = $data_inicio_receita;
        $this->data_termino_receita = $data_termino_receita;
        $this->descricao_receita = $descricao_receita;
    }

    public function getIdReceita(): ?int
    {
        return $this->id_receita;
    }

    public function setIdReceita(?int $id_receita): void
    {
        $this->id_receita = $id_receita;
    }

    public function getDataInicioReceita(): ?string
    {
        return $this->data_inicio_receita;
    }

    public function setDataInicioReceita(?string $data_inicio_receita): void
    {
        $this->data_inicio_receita = $data_inicio_receita;
    }

    public function getDataTerminoReceita(): ?string
    {
        return $this->data_termino_receita;
    }

    public function setDataTerminoReceita(?string $data_termino_receita): void
    {
        $this->data_termino_receita = $data_termino_receita;
    }

    public function getDescricaoReceita(): ?string
    {
        return $this->descricao_receita;
    }

    public function setDescricaoReceita(?string $descricao_receita): void
    {
        $this->descricao_receita = $descricao_receita;
    }
}
?>
