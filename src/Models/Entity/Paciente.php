<?php
namespace Htdocs\Src\Models\Entity;

class Paciente
{
    private ?int $id_paciente;
    private int $id_usuario;
    private string $cpf;
    private ?string $nis;

    public function __construct(?int $id_paciente, $id_usuario, string $cpf, ?string $nis = null)
    {
        $this->id_paciente = $id_paciente;
        $this->id_usuario = is_string($id_usuario) ? (int)$id_usuario : $id_usuario;
        $this->cpf = $cpf;
        $this->nis = $nis;
    }

    public function getIdPaciente(): ?int
    {
        return $this->id_paciente;
    }

    public function setIdPaciente(?int $id_paciente): void
    {
        $this->id_paciente = $id_paciente;
    }

    public function getIdUsuario(): int
    {
        return $this->id_usuario;
    }

    public function setIdUsuario(int $id_usuario): void
    {
        $this->id_usuario = $id_usuario;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): void
    {
        $this->cpf = $cpf;
    }

    public function getNis(): ?string
    {
        return $this->nis;
    }

    public function setNis(?string $nis): void
    {
        $this->nis = $nis;
    }
}
?>