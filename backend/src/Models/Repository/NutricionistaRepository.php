<?php

namespace Htdocs\Src\Models\Repository;

use Htdocs\Src\Config\Connection;
use Htdocs\Src\Models\Entity\Nutricionista;
use PDO;

class NutricionistaRepository {
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

    public function save(Nutricionista $nutricionista) {
        try {
            $sql = "INSERT INTO nutricionista (id_usuario, crm_nutricionista, cpf) VALUES (:id_usuario, :crm_nutricionista, :cpf)";
            $stmt = $this->conn->prepare($sql);
            $id_usuario = $nutricionista->getIdUsuario();
            $crm_nutricionista = $nutricionista->getCrmNutricionista();
            $cpf = $nutricionista->getCpf();
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':crm_nutricionista', $crm_nutricionista);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception("Já existe um nutricionista cadastrado com este CRM ou CPF.");
            }
            throw $e;
        }
    }

    public function findById($id_usuario) {
        $sql = "SELECT * FROM nutricionista WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $sql = "SELECT * FROM nutricionista";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(Nutricionista $nutricionista) {
        $sql = "UPDATE nutricionista SET crm_nutricionista = :crm_nutricionista, cpf = :cpf WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($sql);
        $id_usuario = $nutricionista->getIdUsuario();
        $crm_nutricionista = $nutricionista->getCrmNutricionista();
        $cpf = $nutricionista->getCpf();
        $stmt->bindParam(':crm_nutricionista', $crm_nutricionista);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
    }

    public function delete($id_usuario) {
        $sql = "DELETE FROM nutricionista WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
    }

    public function procurarPorID($id_usuario) {
        $sql = "SELECT * FROM nutricionista WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>