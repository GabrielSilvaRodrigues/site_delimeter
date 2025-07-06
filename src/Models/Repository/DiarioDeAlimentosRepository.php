<?php

namespace Htdocs\Src\Models\Repository;

use Htdocs\Src\Config\Connection;
use Htdocs\Src\Models\Entity\DiarioDeAlimentos;
use PDO;

class DiarioDeAlimentosRepository {
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

    public function save(DiarioDeAlimentos $diario) {
        $sql = "INSERT INTO diario_de_alimentos (id_paciente, data_diario, descricao_diario) 
                VALUES (:id_paciente, :data_diario, :descricao_diario)";
        $stmt = $this->conn->prepare($sql);
        
        $id_paciente = $diario->getIdPaciente();
        $data_diario = $diario->getDataDiario();
        $descricao_diario = $diario->getDescricaoDiario();
        
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->bindParam(':data_diario', $data_diario);
        $stmt->bindParam(':descricao_diario', $descricao_diario);
        
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function findById($id_diario) {
        $sql = "SELECT * FROM diario_de_alimentos WHERE id_diario = :id_diario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_diario', $id_diario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByPacienteId($id_paciente) {
        $sql = "SELECT * FROM diario_de_alimentos WHERE id_paciente = :id_paciente ORDER BY data_diario DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByPacienteAndDate($id_paciente, $data_diario) {
        $sql = "SELECT * FROM diario_de_alimentos WHERE id_paciente = :id_paciente AND data_diario = :data_diario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->bindParam(':data_diario', $data_diario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByDateRange($id_paciente, $data_inicio, $data_fim) {
        $sql = "SELECT * FROM diario_de_alimentos 
                WHERE id_paciente = :id_paciente 
                AND data_diario BETWEEN :data_inicio AND :data_fim 
                ORDER BY data_diario DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $sql = "SELECT * FROM diario_de_alimentos ORDER BY data_diario DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(DiarioDeAlimentos $diario) {
        $sql = "UPDATE diario_de_alimentos SET 
                data_diario = :data_diario, 
                descricao_diario = :descricao_diario 
                WHERE id_diario = :id_diario";
        
        $stmt = $this->conn->prepare($sql);
        
        $id_diario = $diario->getIdDiario();
        $data_diario = $diario->getDataDiario();
        $descricao_diario = $diario->getDescricaoDiario();
        
        $stmt->bindParam(':id_diario', $id_diario);
        $stmt->bindParam(':data_diario', $data_diario);
        $stmt->bindParam(':descricao_diario', $descricao_diario);
        
        $stmt->execute();
    }

    public function delete($id_diario) {
        $sql = "DELETE FROM diario_de_alimentos WHERE id_diario = :id_diario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_diario', $id_diario);
        $stmt->execute();
    }

    // Método para associar alimentos ao diário
    public function associarAlimento($id_diario, $id_alimento) {
        $sql = "INSERT INTO relacao_diario_alimento (id_diario, id_alimento) VALUES (:id_diario, :id_alimento)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_diario', $id_diario);
        $stmt->bindParam(':id_alimento', $id_alimento);
        $stmt->execute();
    }

    public function removerAlimento($id_diario, $id_alimento) {
        $sql = "DELETE FROM relacao_diario_alimento WHERE id_diario = :id_diario AND id_alimento = :id_alimento";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_diario', $id_diario);
        $stmt->bindParam(':id_alimento', $id_alimento);
        $stmt->execute();
    }
}
?>
