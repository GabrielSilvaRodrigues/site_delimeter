<?php

namespace Htdocs\Src\Models\Repository;

use Htdocs\Src\Config\Connection;
use Htdocs\Src\Models\Entity\Dieta;
use PDO;

class DietaRepository {
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

    public function save(Dieta $dieta) {
        $sql = "INSERT INTO dieta (data_inicio_dieta, data_termino_dieta, descricao_dieta) 
                VALUES (:data_inicio_dieta, :data_termino_dieta, :descricao_dieta)";
        $stmt = $this->conn->prepare($sql);
        
        $data_inicio_dieta = $dieta->getDataInicioDieta();
        $data_termino_dieta = $dieta->getDataTerminoDieta();
        $descricao_dieta = $dieta->getDescricaoDieta();
        
        $stmt->bindParam(':data_inicio_dieta', $data_inicio_dieta);
        $stmt->bindParam(':data_termino_dieta', $data_termino_dieta);
        $stmt->bindParam(':descricao_dieta', $descricao_dieta);
        
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function findById($id_dieta) {
        $sql = "SELECT * FROM dieta WHERE id_dieta = :id_dieta";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_dieta', $id_dieta);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByPacienteId($id_paciente) {
        $sql = "SELECT d.* FROM dieta d 
                JOIN relacao_paciente_dieta rpd ON d.id_dieta = rpd.id_dieta 
                WHERE rpd.id_paciente = :id_paciente 
                ORDER BY d.data_inicio_dieta DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByNutricionistaId($id_nutricionista) {
        $sql = "SELECT d.* FROM dieta d 
                JOIN relacao_nutricionista_dieta rnd ON d.id_dieta = rnd.id_dieta 
                WHERE rnd.id_nutricionista = :id_nutricionista 
                ORDER BY d.data_inicio_dieta DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_nutricionista', $id_nutricionista);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $sql = "SELECT * FROM dieta ORDER BY data_inicio_dieta DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(Dieta $dieta) {
        $sql = "UPDATE dieta SET 
                data_inicio_dieta = :data_inicio_dieta, 
                data_termino_dieta = :data_termino_dieta, 
                descricao_dieta = :descricao_dieta 
                WHERE id_dieta = :id_dieta";
        
        $stmt = $this->conn->prepare($sql);
        
        $id_dieta = $dieta->getIdDieta();
        $data_inicio_dieta = $dieta->getDataInicioDieta();
        $data_termino_dieta = $dieta->getDataTerminoDieta();
        $descricao_dieta = $dieta->getDescricaoDieta();
        
        $stmt->bindParam(':id_dieta', $id_dieta);
        $stmt->bindParam(':data_inicio_dieta', $data_inicio_dieta);
        $stmt->bindParam(':data_termino_dieta', $data_termino_dieta);
        $stmt->bindParam(':descricao_dieta', $descricao_dieta);
        
        $stmt->execute();
    }

    public function delete($id_dieta) {
        $sql = "DELETE FROM dieta WHERE id_dieta = :id_dieta";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_dieta', $id_dieta);
        $stmt->execute();
    }

    // Métodos para relações
    public function associarPaciente($id_dieta, $id_paciente) {
        $sql = "INSERT INTO relacao_paciente_dieta (id_dieta, id_paciente) VALUES (:id_dieta, :id_paciente)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_dieta', $id_dieta);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->execute();
    }

    public function associarNutricionista($id_dieta, $id_nutricionista) {
        $sql = "INSERT INTO relacao_nutricionista_dieta (id_dieta, id_nutricionista) VALUES (:id_dieta, :id_nutricionista)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_dieta', $id_dieta);
        $stmt->bindParam(':id_nutricionista', $id_nutricionista);
        $stmt->execute();
    }
}
?>
