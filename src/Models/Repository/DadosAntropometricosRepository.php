<?php

namespace Htdocs\Src\Models\Repository;

use Htdocs\Src\Config\Connection;
use Htdocs\Src\Models\Entity\DadosAntropometricos;
use PDO;

class DadosAntropometricosRepository {
    public $conn;

    public function isConnected() {
        return $this->conn !== null;
    }

    public function __construct() {
        $database = new Connection();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            error_log("DadosAntropometricosRepository: Erro ao conectar ao banco");
            throw new \Exception("Não foi possível conectar ao banco de dados");
        } else {
            error_log("DadosAntropometricosRepository: Conectado ao banco com sucesso");
        }
    }

    public function save(DadosAntropometricos $dados) {
        $sql = "INSERT INTO dados_antropometricos (id_paciente, sexo_paciente, altura_paciente, peso_paciente, status_paciente, data_medida) 
                VALUES (:id_paciente, :sexo_paciente, :altura_paciente, :peso_paciente, :status_paciente, :data_medida)";
        $stmt = $this->conn->prepare($sql);
        
        $id_paciente = $dados->getIdPaciente();
        $sexo_paciente = $dados->getSexoPaciente();
        $altura_paciente = $dados->getAlturaPaciente();
        $peso_paciente = $dados->getPesoPaciente();
        $status_paciente = $dados->getStatusPaciente();
        $data_medida = $dados->getDataMedida();
        
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->bindParam(':sexo_paciente', $sexo_paciente);
        $stmt->bindParam(':altura_paciente', $altura_paciente);
        $stmt->bindParam(':peso_paciente', $peso_paciente);
        $stmt->bindParam(':status_paciente', $status_paciente);
        $stmt->bindParam(':data_medida', $data_medida);
        
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function findById($id_medida) {
        $sql = "SELECT * FROM dados_antropometricos WHERE id_medida = :id_medida";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_medida', $id_medida);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByPacienteId($id_paciente) {
        error_log("DadosAntropometricosRepository: Buscando dados para paciente ID: " . $id_paciente);
        $sql = "SELECT * FROM dados_antropometricos WHERE id_paciente = :id_paciente ORDER BY data_medida DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("DadosAntropometricosRepository: Encontrados " . count($result) . " registros");
        return $result;
    }

    public function findLatestByPacienteId($id_paciente) {
        $sql = "SELECT * FROM dados_antropometricos WHERE id_paciente = :id_paciente ORDER BY data_medida DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        error_log("DadosAntropometricosRepository: findAll() chamado");
        $sql = "SELECT * FROM dados_antropometricos ORDER BY data_medida DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("DadosAntropometricosRepository: findAll() retornou " . count($result) . " registros");
        return $result;
    }

    public function update(DadosAntropometricos $dados) {
        $sql = "UPDATE dados_antropometricos SET 
                sexo_paciente = :sexo_paciente, 
                altura_paciente = :altura_paciente, 
                peso_paciente = :peso_paciente, 
                status_paciente = :status_paciente, 
                data_medida = :data_medida 
                WHERE id_medida = :id_medida";
        
        $stmt = $this->conn->prepare($sql);
        
        $id_medida = $dados->getIdMedida();
        $sexo_paciente = $dados->getSexoPaciente();
        $altura_paciente = $dados->getAlturaPaciente();
        $peso_paciente = $dados->getPesoPaciente();
        $status_paciente = $dados->getStatusPaciente();
        $data_medida = $dados->getDataMedida();
        
        $stmt->bindParam(':id_medida', $id_medida);
        $stmt->bindParam(':sexo_paciente', $sexo_paciente);
        $stmt->bindParam(':altura_paciente', $altura_paciente);
        $stmt->bindParam(':peso_paciente', $peso_paciente);
        $stmt->bindParam(':status_paciente', $status_paciente);
        $stmt->bindParam(':data_medida', $data_medida);
        
        $stmt->execute();
    }

    public function delete($id_medida) {
        $sql = "DELETE FROM dados_antropometricos WHERE id_dados_antropometricos = :id_medida OR id_medida = :id_medida";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_medida', $id_medida);
        $stmt->execute();
    }

    public function deleteByPacienteId($id_paciente) {
        $sql = "DELETE FROM dados_antropometricos WHERE id_paciente = :id_paciente";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->execute();
    }
}
?>
