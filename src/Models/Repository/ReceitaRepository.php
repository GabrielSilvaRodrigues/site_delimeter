<?php

namespace Htdocs\Src\Models\Repository;

use Htdocs\Src\Config\Connection;
use Htdocs\Src\Models\Entity\Receita;
use PDO;

class ReceitaRepository {
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

    public function save(Receita $receita) {
        $sql = "INSERT INTO receita (data_inicio_receita, data_termino_receita, descricao_receita) 
                VALUES (:data_inicio_receita, :data_termino_receita, :descricao_receita)";
        $stmt = $this->conn->prepare($sql);
        
        $data_inicio_receita = $receita->getDataInicioReceita();
        $data_termino_receita = $receita->getDataTerminoReceita();
        $descricao_receita = $receita->getDescricaoReceita();
        
        $stmt->bindParam(':data_inicio_receita', $data_inicio_receita);
        $stmt->bindParam(':data_termino_receita', $data_termino_receita);
        $stmt->bindParam(':descricao_receita', $descricao_receita);
        
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function findById($id_receita) {
        $sql = "SELECT * FROM receita WHERE id_receita = :id_receita";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_receita', $id_receita);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByPacienteId($id_paciente) {
        $sql = "SELECT r.* FROM receita r 
                JOIN relacao_paciente_receita rpr ON r.id_receita = rpr.id_receita 
                WHERE rpr.id_paciente = :id_paciente 
                ORDER BY r.data_inicio_receita DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByNutricionistaId($id_nutricionista) {
        $sql = "SELECT r.* FROM receita r 
                JOIN relacao_nutricionista_receita rnr ON r.id_receita = rnr.id_receita 
                WHERE rnr.id_nutricionista = :id_nutricionista 
                ORDER BY r.data_inicio_receita DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_nutricionista', $id_nutricionista);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findValidatedByMedicoId($id_medico) {
        $sql = "SELECT r.* FROM receita r 
                JOIN valida_receita vr ON r.id_receita = vr.id_receita 
                WHERE vr.id_medico = :id_medico 
                ORDER BY r.data_inicio_receita DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_medico', $id_medico);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $sql = "SELECT * FROM receita ORDER BY data_inicio_receita DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(Receita $receita) {
        $sql = "UPDATE receita SET 
                data_inicio_receita = :data_inicio_receita, 
                data_termino_receita = :data_termino_receita, 
                descricao_receita = :descricao_receita 
                WHERE id_receita = :id_receita";
        
        $stmt = $this->conn->prepare($sql);
        
        $id_receita = $receita->getIdReceita();
        $data_inicio_receita = $receita->getDataInicioReceita();
        $data_termino_receita = $receita->getDataTerminoReceita();
        $descricao_receita = $receita->getDescricaoReceita();
        
        $stmt->bindParam(':id_receita', $id_receita);
        $stmt->bindParam(':data_inicio_receita', $data_inicio_receita);
        $stmt->bindParam(':data_termino_receita', $data_termino_receita);
        $stmt->bindParam(':descricao_receita', $descricao_receita);
        
        $stmt->execute();
    }

    public function delete($id_receita) {
        $sql = "DELETE FROM receita WHERE id_receita = :id_receita";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_receita', $id_receita);
        $stmt->execute();
    }

    // Métodos para relações
    public function associarPaciente($id_receita, $id_paciente) {
        $sql = "INSERT INTO relacao_paciente_receita (id_receita, id_paciente) VALUES (:id_receita, :id_paciente)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_receita', $id_receita);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->execute();
    }

    public function associarNutricionista($id_receita, $id_nutricionista) {
        $sql = "INSERT INTO relacao_nutricionista_receita (id_receita, id_nutricionista) VALUES (:id_receita, :id_nutricionista)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_receita', $id_receita);
        $stmt->bindParam(':id_nutricionista', $id_nutricionista);
        $stmt->execute();
    }

    public function validarPorMedico($id_receita, $id_medico) {
        $sql = "INSERT INTO valida_receita (id_receita, id_medico) VALUES (:id_receita, :id_medico)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_receita', $id_receita);
        $stmt->bindParam(':id_medico', $id_medico);
        $stmt->execute();
    }
}
?>
