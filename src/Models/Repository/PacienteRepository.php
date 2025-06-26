<?php

namespace Htdocs\Src\Models\Repository;

use Htdocs\Src\Config\Connection;
use Htdocs\Src\Models\Entity\Paciente;
use PDO;

class PacienteRepository {
    public $conn;

    public function isConnected() {
        return $this->conn !== null;
    }

    public function isValid() {
        return $this->conn !== null;
    }

    public function isReady() {
        return $this->isConnected();
    }

    public function __construct() {
        $database = new Connection();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            echo "Erro: Não foi possível conectar ao banco de dados.\n";
            exit(1);
        }
    }

    public function save(Paciente $paciente) {
        $query = "INSERT INTO paciente (id_usuario, cpf, nis) VALUES (:id_usuario, :cpf, :nis)";
        $stmt = $this->conn->prepare($query);

        $id_usuario = $paciente->getIdUsuario();
        $cpf = $paciente->getCpf();
        $nis = $paciente->getNis();

        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':nis', $nis);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    // Retorna todos os pacientes cadastrados
    public function findAll() {
        $query = "SELECT * FROM paciente";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca um paciente pelo ID
    public function findById($id) {
        $query = "SELECT * FROM paciente WHERE id_paciente = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Atualiza os dados de um paciente existente
    public function update(Paciente $paciente) {
        $query = "UPDATE paciente SET id_usuario = :id_usuario, cpf = :cpf, nis = :nis WHERE id_paciente = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $paciente->getIdUsuario());
        $stmt->bindParam(':cpf', $paciente->getCpf());
        $stmt->bindParam(':nis', $paciente->getNis());
        $stmt->bindParam(':id', $paciente->getIdPaciente());
        $stmt->execute();
    }

    // Remove um paciente pelo ID
    public function delete($id) {
        $query = "DELETE FROM paciente WHERE id_paciente = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
?>