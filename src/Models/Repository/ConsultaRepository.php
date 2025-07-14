<?php

namespace Htdocs\Src\Models\Repository;

use Htdocs\Src\Config\Connection;
use Htdocs\Src\Models\Entity\Consulta;
use PDO;

class ConsultaRepository {
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

    public function save(Consulta $consulta) {
        $sql = "INSERT INTO consulta (data_consulta) VALUES (:data_consulta)";
        $stmt = $this->conn->prepare($sql);
        
        $data_consulta = $consulta->getDataConsulta();
        $stmt->bindParam(':data_consulta', $data_consulta);
        
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function findById($id_consulta) {
        $sql = "SELECT * FROM consulta WHERE id_consulta = :id_consulta";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_consulta', $id_consulta);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByPacienteId($id_paciente) {
        $sql = "SELECT c.* FROM consulta c 
                JOIN relacao_paciente_consulta rpc ON c.id_consulta = rpc.id_consulta 
                WHERE rpc.id_paciente = :id_paciente 
                ORDER BY c.data_consulta DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByMedicoId($id_medico) {
        $sql = "SELECT c.* FROM consulta c 
                JOIN relacao_consulta_medico rcm ON c.id_consulta = rcm.id_consulta 
                WHERE rcm.id_medico = :id_medico 
                ORDER BY c.data_consulta DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_medico', $id_medico);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByNutricionistaId($id_nutricionista) {
        $sql = "SELECT c.* FROM consulta c 
                JOIN relacao_consulta_nutricionista rcn ON c.id_consulta = rcn.id_consulta 
                WHERE rcn.id_nutricionista = :id_nutricionista 
                ORDER BY c.data_consulta DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_nutricionista', $id_nutricionista);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByDate($data_consulta) {
        $sql = "SELECT * FROM consulta WHERE data_consulta = :data_consulta ORDER BY id_consulta";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':data_consulta', $data_consulta);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByDateRange($data_inicio, $data_fim) {
        $sql = "SELECT * FROM consulta 
                WHERE data_consulta BETWEEN :data_inicio AND :data_fim 
                ORDER BY data_consulta DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $sql = "SELECT * FROM consulta ORDER BY data_consulta DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(Consulta $consulta) {
        $sql = "UPDATE consulta SET data_consulta = :data_consulta WHERE id_consulta = :id_consulta";
        
        $stmt = $this->conn->prepare($sql);
        
        $id_consulta = $consulta->getIdConsulta();
        $data_consulta = $consulta->getDataConsulta();
        
        $stmt->bindParam(':id_consulta', $id_consulta);
        $stmt->bindParam(':data_consulta', $data_consulta);
        
        $stmt->execute();
    }

    public function delete($id_consulta) {
        $sql = "DELETE FROM consulta WHERE id_consulta = :id_consulta";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_consulta', $id_consulta);
        $stmt->execute();
    }

    // Métodos para relações
    public function associarPaciente($id_consulta, $id_paciente) {
        $sql = "INSERT INTO relacao_paciente_consulta (id_consulta, id_paciente) VALUES (:id_consulta, :id_paciente)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_consulta', $id_consulta);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->execute();
    }

    public function associarMedico($id_consulta, $id_medico) {
        $sql = "INSERT INTO relacao_consulta_medico (id_consulta, id_medico) VALUES (:id_consulta, :id_medico)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_consulta', $id_consulta);
        $stmt->bindParam(':id_medico', $id_medico);
        $stmt->execute();
    }

    public function associarNutricionista($id_consulta, $id_nutricionista) {
        $sql = "INSERT INTO relacao_consulta_nutricionista (id_consulta, id_nutricionista) VALUES (:id_consulta, :id_nutricionista)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_consulta', $id_consulta);
        $stmt->bindParam(':id_nutricionista', $id_nutricionista);
        $stmt->execute();
    }
}
?>
