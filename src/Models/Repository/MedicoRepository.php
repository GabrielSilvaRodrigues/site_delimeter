<?php

namespace Htdocs\Src\Models\Repository;

use Htdocs\Src\Config\Connection;
use Htdocs\Src\Models\Entity\Medico;
use PDO;

class MedicoRepository {
    public $conn;

    public function isConnected() {
        return $this->conn !== null;
    }

    public function __construct() {
        $database = new Connection();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            echo "Erro: Não foi possível conectar ao banco de dados.\n";
            exit(1);
        }
    }

    public function save(Medico $medico) {
        try {
            $sql = "INSERT INTO medico (id_usuario, crm_medico, cpf) VALUES (:id_usuario, :crm_medico, :cpf)";
            $stmt = $this->conn->prepare($sql);
            $id_usuario = $medico->getIdUsuario();
            $crm_medico = $medico->getCrmMedico();
            $cpf = $medico->getCpf();
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':crm_medico', $crm_medico);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception("Já existe um médico cadastrado com este CRM ou CPF.");
            }
            throw $e;
        }
    }

    public function findById($id_usuario) {
        $sql = "SELECT * FROM medico WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $sql = "SELECT * FROM medico";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(Medico $medico) {
        $sql = "UPDATE medico SET crm_medico = :crm_medico, cpf = :cpf WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($sql);
        $id_usuario = $medico->getIdUsuario();
        $crm_medico = $medico->getCrmMedico();
        $cpf = $medico->getCpf();
        $stmt->bindParam(':crm_medico', $crm_medico);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
    }

    public function delete($id_usuario) {
        $sql = "DELETE FROM medico WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
    }

    public function procurarPorID($id_usuario) {
        $sql = "SELECT * FROM medico WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>