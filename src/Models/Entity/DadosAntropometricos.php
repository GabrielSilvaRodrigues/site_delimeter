<?php
namespace Htdocs\Src\Models\Entity;

class DadosAntropometricos
{
    private ?int $id_medida;
    private int $id_paciente;
    private ?int $sexo_paciente; // 0=feminino, 1=masculino
    private ?float $altura_paciente; // em metros (ex: 1.75)
    private ?float $peso_paciente;
    private ?int $status_paciente;
    private ?string $data_medida;

    public function __construct(
        ?int $id_medida,
        int $id_paciente,
        ?int $sexo_paciente = null,
        ?float $altura_paciente = null,
        ?float $peso_paciente = null,
        ?int $status_paciente = null,
        ?string $data_medida = null
    ) {
        $this->id_medida = $id_medida;
        $this->id_paciente = $id_paciente;
        $this->sexo_paciente = $sexo_paciente;
        $this->altura_paciente = $altura_paciente;
        $this->peso_paciente = $peso_paciente;
        $this->status_paciente = $status_paciente;
        $this->data_medida = $data_medida;
    }

    public function getIdMedida(): ?int
    {
        return $this->id_medida;
    }

    public function setIdMedida(?int $id_medida): void
    {
        $this->id_medida = $id_medida;
    }

    public function getIdPaciente(): int
    {
        return $this->id_paciente;
    }

    public function setIdPaciente(int $id_paciente): void
    {
        $this->id_paciente = $id_paciente;
    }

    public function getSexoPaciente(): ?int
    {
        return $this->sexo_paciente;
    }

    public function setSexoPaciente(?int $sexo_paciente): void
    {
        $this->sexo_paciente = $sexo_paciente;
    }

    public function getAlturaPaciente(): ?float
    {
        return $this->altura_paciente;
    }

    public function setAlturaPaciente(?float $altura_paciente): void
    {
        $this->altura_paciente = $altura_paciente;
    }

    public function getPesoPaciente(): ?float
    {
        return $this->peso_paciente;
    }

    public function setPesoPaciente(?float $peso_paciente): void
    {
        $this->peso_paciente = $peso_paciente;
    }

    public function getStatusPaciente(): ?int
    {
        return $this->status_paciente;
    }

    public function setStatusPaciente(?int $status_paciente): void
    {
        $this->status_paciente = $status_paciente;
    }

    public function getDataMedida(): ?string
    {
        return $this->data_medida;
    }

    public function setDataMedida(?string $data_medida): void
    {
        $this->data_medida = $data_medida;
    }

    public function calcularIMC(): ?float
    {
        if ($this->altura_paciente && $this->peso_paciente) {
            return $this->peso_paciente / ($this->altura_paciente * $this->altura_paciente);
        }
        return null;
    }
}
?>
