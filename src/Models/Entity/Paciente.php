<?php
namespace Htdocs\Src\Models\Entity;

class Paciente {
    private $id_paciente;
    private $id_usuario;
    private $cpf;
    private $nis;

    public function __construct($id_usuario, $cpf, $nis) {
        $this->id_usuario = $id_usuario;
        $this->cpf = $cpf;
        $this->nis = $nis;
    }

    public function getIdPaciente() {
        return $this->id_paciente;
    }

    public function setIdPaciente($id_paciente) {
        $this->id_paciente = $id_paciente;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    public function getNis() {
        return $this->nis;
    }

    public function setNis($nis) {
        $this->nis = $nis;
    }
}
?>