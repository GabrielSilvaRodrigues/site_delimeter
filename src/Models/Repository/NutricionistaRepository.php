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
        $sql = "INSERT INTO nutricionista (id_usuario, crm_nutricionista) VALUES (:id_usuario, :crm_nutricionista)";
        $stmt = $this->conn->prepare($sql);
        $id_usuario = $nutricionista->getIdUsuario();
        $crm_nutricionista = $nutricionista->getCrmNutricionista();
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':crm_nutricionista', $crm_nutricionista);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function findById($id) {
        $sql = "SELECT * FROM nutricionista WHERE id_nutricionista = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
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
        $sql = "UPDATE nutricionista SET id_usuario = :id_usuario, crm_nutricionista = :crm_nutricionista WHERE id_nutricionista = :id";
        $stmt = $this->conn->prepare($sql);
        $id_usuario = $nutricionista->getIdUsuario();
        $crm_nutricionista = $nutricionista->getCrmNutricionista();
        $id = $nutricionista->getIdNutricionista();
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':crm_nutricionista', $crm_nutricionista);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM nutricionista WHERE id_nutricionista = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
?>