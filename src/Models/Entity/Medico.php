<?php
namespace Htdocs\Src\Models\Entity;

class Medico {
    private ?int $id_medico;
    private int $id_usuario;
    private ?string $crm_medico;
    private ?string $cpf;

    public function __construct(?int $id_medico, int $id_usuario, ?string $crm_medico, ?string $cpf = null) {
        $this->id_medico = $id_medico;
        $this->id_usuario = $id_usuario;
        $this->crm_medico = $crm_medico;
        $this->cpf = $cpf;
    }

    public function getIdMedico(): ?int {
        return $this->id_medico;
    }

    public function setIdMedico(?int $id_medico): void {
        $this->id_medico = $id_medico;
    }

    public function getIdUsuario(): int {
        return $this->id_usuario;
    }

    public function setIdUsuario(int $id_usuario): void {
        $this->id_usuario = $id_usuario;
    }

    public function getCrmMedico(): ?string {
        return $this->crm_medico;
    }

    public function setCrmMedico(?string $crm_medico): void {
        $this->crm_medico = $crm_medico;
    }

    public function getCpf(): ?string {
        return $this->cpf;
    }

    public function setCpf(?string $cpf): void {
        $this->cpf = $cpf;
    }
}
?>