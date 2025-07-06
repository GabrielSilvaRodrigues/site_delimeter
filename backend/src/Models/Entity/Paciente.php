<?php
namespace Htdocs\Src\Models\Entity;

class Paciente
{
    private int $id_usuario;
    private string $cpf;
    private ?string $nis;

    public function __construct(int $id_usuario, string $cpf, ?string $nis = null)
    {
        $this->id_usuario = $id_usuario;
        $this->cpf = $cpf;
        $this->nis = $nis;
    }

    public function getIdUsuario(): int
    {
        return $this->id_usuario;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function getNis(): ?string
    {
        return $this->nis;
    }
}
?>