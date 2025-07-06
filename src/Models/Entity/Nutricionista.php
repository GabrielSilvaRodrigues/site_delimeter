<?php
namespace Htdocs\Src\Models\Entity;

class Nutricionista {
    private ?int $id_nutricionista;
    private int $id_usuario;
    private ?string $crm_nutricionista;
    private ?string $cpf;

    public function __construct(?int $id_nutricionista, int $id_usuario, ?string $crm_nutricionista, ?string $cpf = null) {
        $this->id_nutricionista = $id_nutricionista;
        $this->id_usuario = $id_usuario;
        $this->crm_nutricionista = $crm_nutricionista;
        $this->cpf = $cpf;
    }

    public function getIdNutricionista(): ?int {
        return $this->id_nutricionista;
    }

    public function setIdNutricionista(?int $id_nutricionista): void {
        $this->id_nutricionista = $id_nutricionista;
    }

    public function getIdUsuario(): int {
        return $this->id_usuario;
    }

    public function setIdUsuario(int $id_usuario): void {
        $this->id_usuario = $id_usuario;
    }

    public function getCrmNutricionista(): ?string {
        return $this->crm_nutricionista;
    }

    public function setCrmNutricionista(?string $crm_nutricionista): void {
        $this->crm_nutricionista = $crm_nutricionista;
    }

    public function getCpf(): ?string {
        return $this->cpf;
    }

    public function setCpf(?string $cpf): void {
        $this->cpf = $cpf;
    }
}
?>