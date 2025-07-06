<?php
namespace Htdocs\Src\Models\Entity;

class Consulta
{
    private ?int $id_consulta;
    private ?string $data_consulta;

    public function __construct(
        ?int $id_consulta,
        ?string $data_consulta = null
    ) {
        $this->id_consulta = $id_consulta;
        $this->data_consulta = $data_consulta;
    }

    public function getIdConsulta(): ?int
    {
        return $this->id_consulta;
    }

    public function setIdConsulta(?int $id_consulta): void
    {
        $this->id_consulta = $id_consulta;
    }

    public function getDataConsulta(): ?string
    {
        return $this->data_consulta;
    }

    public function setDataConsulta(?string $data_consulta): void
    {
        $this->data_consulta = $data_consulta;
    }
}
?>
