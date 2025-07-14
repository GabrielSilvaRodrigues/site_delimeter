<?php
namespace Htdocs\Src\Models\Entity;

class Dieta
{
    private ?int $id_dieta;
    private ?string $data_inicio_dieta;
    private ?string $data_termino_dieta;
    private ?string $descricao_dieta;

    public function __construct(
        ?int $id_dieta,
        ?string $data_inicio_dieta = null,
        ?string $data_termino_dieta = null,
        ?string $descricao_dieta = null
    ) {
        $this->id_dieta = $id_dieta;
        $this->data_inicio_dieta = $data_inicio_dieta;
        $this->data_termino_dieta = $data_termino_dieta;
        $this->descricao_dieta = $descricao_dieta;
    }

    public function getIdDieta(): ?int
    {
        return $this->id_dieta;
    }

    public function setIdDieta(?int $id_dieta): void
    {
        $this->id_dieta = $id_dieta;
    }

    public function getDataInicioDieta(): ?string
    {
        return $this->data_inicio_dieta;
    }

    public function setDataInicioDieta(?string $data_inicio_dieta): void
    {
        $this->data_inicio_dieta = $data_inicio_dieta;
    }

    public function getDataTerminoDieta(): ?string
    {
        return $this->data_termino_dieta;
    }

    public function setDataTerminoDieta(?string $data_termino_dieta): void
    {
        $this->data_termino_dieta = $data_termino_dieta;
    }

    public function getDescricaoDieta(): ?string
    {
        return $this->descricao_dieta;
    }

    public function setDescricaoDieta(?string $descricao_dieta): void
    {
        $this->descricao_dieta = $descricao_dieta;
    }
}
?>
