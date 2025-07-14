<?php
namespace Htdocs\Src\Models\Entity;

class DiarioDeAlimentos
{
    private ?int $id_diario;
    private ?int $id_paciente;
    private ?string $data_diario;
    private ?string $descricao_diario;

    public function __construct(
        ?int $id_diario,
        ?int $id_paciente = null,
        ?string $data_diario = null,
        ?string $descricao_diario = null
    ) {
        $this->id_diario = $id_diario;
        $this->id_paciente = $id_paciente;
        $this->data_diario = $data_diario;
        $this->descricao_diario = $descricao_diario;
    }

    public function getIdDiario(): ?int
    {
        return $this->id_diario;
    }

    public function setIdDiario(?int $id_diario): void
    {
        $this->id_diario = $id_diario;
    }

    public function getIdPaciente(): ?int
    {
        return $this->id_paciente;
    }

    public function setIdPaciente(?int $id_paciente): void
    {
        $this->id_paciente = $id_paciente;
    }

    public function getDataDiario(): ?string
    {
        return $this->data_diario;
    }

    public function setDataDiario(?string $data_diario): void
    {
        $this->data_diario = $data_diario;
    }

    public function getDescricaoDiario(): ?string
    {
        return $this->descricao_diario;
    }

    public function setDescricaoDiario(?string $descricao_diario): void
    {
        $this->descricao_diario = $descricao_diario;
    }
}
?>
