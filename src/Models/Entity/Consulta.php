<?php
namespace Htdocs\Src\Models\Entity;

class Consulta
{
    private ?int $id_consulta;
    private ?string $data_consulta;
    private ?int $id_agenda;
    private ?int $id_paciente;
    private ?int $id_medico;
    private ?int $id_nutricionista;
    private ?string $tipo_consulta;
    private ?string $status_agenda;
    private ?string $observacoes;

    public function __construct(
        ?int $id_consulta,
        ?string $data_consulta = null,
        ?int $id_agenda = null,
        ?int $id_paciente = null,
        ?int $id_medico = null,
        ?int $id_nutricionista = null,
        ?string $tipo_consulta = null,
        ?string $status_agenda = 'agendado',
        ?string $observacoes = null
    ) {
        $this->id_consulta = $id_consulta;
        $this->data_consulta = $data_consulta;
        $this->id_agenda = $id_agenda;
        $this->id_paciente = $id_paciente;
        $this->id_medico = $id_medico;
        $this->id_nutricionista = $id_nutricionista;
        $this->tipo_consulta = $tipo_consulta;
        $this->status_agenda = $status_agenda;
        $this->observacoes = $observacoes;
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

    public function getIdAgenda(): ?int
    {
        return $this->id_agenda;
    }

    public function setIdAgenda(?int $id_agenda): void
    {
        $this->id_agenda = $id_agenda;
    }

    public function getIdPaciente(): ?int
    {
        return $this->id_paciente;
    }

    public function setIdPaciente(?int $id_paciente): void
    {
        $this->id_paciente = $id_paciente;
    }

    public function getIdMedico(): ?int
    {
        return $this->id_medico;
    }

    public function setIdMedico(?int $id_medico): void
    {
        $this->id_medico = $id_medico;
    }

    public function getIdNutricionista(): ?int
    {
        return $this->id_nutricionista;
    }

    public function setIdNutricionista(?int $id_nutricionista): void
    {
        $this->id_nutricionista = $id_nutricionista;
    }

    public function getTipoConsulta(): ?string
    {
        return $this->tipo_consulta;
    }

    public function setTipoConsulta(?string $tipo_consulta): void
    {
        $this->tipo_consulta = $tipo_consulta;
    }

    public function getStatusAgenda(): ?string
    {
        return $this->status_agenda;
    }

    public function setStatusAgenda(?string $status_agenda): void
    {
        $this->status_agenda = $status_agenda;
    }

    public function getObservacoes(): ?string
    {
        return $this->observacoes;
    }

    public function setObservacoes(?string $observacoes): void
    {
        $this->observacoes = $observacoes;
    }
}
?>
