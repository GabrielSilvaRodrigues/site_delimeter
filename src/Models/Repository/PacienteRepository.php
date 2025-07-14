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

    public function __construct() {
        $database = new Connection();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            echo "Erro: Não foi possível conectar ao banco de dados.\n";
            exit(1);
        }
    }

    public function save(Paciente $paciente) {
        try {
            $sql = "INSERT INTO paciente (id_usuario, cpf, nis) VALUES (:id_usuario, :cpf, :nis)";
            $stmt = $this->conn->prepare($sql);
            $id_usuario = $paciente->getIdUsuario();
            $cpf = $paciente->getCpf();
            $nis = $paciente->getNis();
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':nis', $nis);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) { // Código de violação de integridade (duplicidade) MySQL
                // Verificar qual campo está causando a duplicidade
                $errorMessage = $e->getMessage();
                if (strpos($errorMessage, 'cpf') !== false) {
                    throw new \Exception("Já existe um paciente cadastrado com este CPF.");
                } elseif (strpos($errorMessage, 'id_usuario') !== false) {
                    throw new \Exception("Este usuário já possui um cadastro de paciente.");
                } else {
                    throw new \Exception("Violação de integridade: dados duplicados.");
                }
            }
            throw $e;
        }
    }

    public function findById($id_paciente) {
        $sql = "SELECT * FROM paciente WHERE id_paciente = :id_paciente";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByUsuarioId($id_usuario) {
        error_log("PacienteRepository: Buscando paciente por id_usuario: " . $id_usuario);
        $sql = "SELECT * FROM paciente WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log("PacienteRepository: Resultado da busca: " . print_r($result, true));
        return $result;
    }

    public function findAll() {
        $sql = "SELECT * FROM paciente";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(Paciente $paciente) {
        try {
            $sql = "UPDATE paciente SET cpf = :cpf, nis = :nis WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($sql);
            $id_usuario = $paciente->getIdUsuario();
            $cpf = $paciente->getCpf();
            $nis = $paciente->getNis();
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':nis', $nis);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) { // Código de violação de integridade (duplicidade) MySQL
                $errorMessage = $e->getMessage();
                if (strpos($errorMessage, 'cpf') !== false) {
                    throw new \Exception("Já existe um paciente cadastrado com este CPF.");
                } else {
                    throw new \Exception("Violação de integridade: dados duplicados.");
                }
            }
            throw $e;
        }
    }

    public function delete($id_usuario) {
        try {
            // Primeiro, buscar o ID do paciente
            $pacienteData = $this->findByUsuarioId($id_usuario);
            if (!$pacienteData) {
                throw new \Exception("Paciente não encontrado.");
            }
            
            $id_paciente = $pacienteData['id_paciente'];
            
            // Iniciar transação
            $this->conn->beginTransaction();
            
            // Deletar dados antropométricos associados
            $sql_dados = "DELETE FROM dados_antropometricos WHERE id_paciente = :id_paciente";
            $stmt_dados = $this->conn->prepare($sql_dados);
            $stmt_dados->bindParam(':id_paciente', $id_paciente);
            $stmt_dados->execute();
            
            // Deletar outros dados relacionados se existirem
            $this->deleteRelatedData($id_paciente);
            
            // Deletar o paciente
            $sql = "DELETE FROM paciente WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
            
            // Confirmar transação
            $this->conn->commit();
            
        } catch (\Exception $e) {
            // Reverter transação em caso de erro
            if ($this->conn->inTransaction()) {
                $this->conn->rollback();
            }
            throw $e;
        }
    }

    /**
     * Método auxiliar para deletar dados relacionados ao paciente
     */
    private function deleteRelatedData($id_paciente) {
        // Deletar diários de alimentos
        $sql_diario = "DELETE FROM diario_de_alimentos WHERE id_paciente = :id_paciente";
        $stmt_diario = $this->conn->prepare($sql_diario);
        $stmt_diario->bindParam(':id_paciente', $id_paciente);
        $stmt_diario->execute();
        
        // Deletar relações com receitas
        $sql_receita = "DELETE FROM relacao_paciente_receita WHERE id_paciente = :id_paciente";
        $stmt_receita = $this->conn->prepare($sql_receita);
        $stmt_receita->bindParam(':id_paciente', $id_paciente);
        $stmt_receita->execute();
        
        // Deletar relações com dietas
        $sql_dieta = "DELETE FROM relacao_paciente_dieta WHERE id_paciente = :id_paciente";
        $stmt_dieta = $this->conn->prepare($sql_dieta);
        $stmt_dieta->bindParam(':id_paciente', $id_paciente);
        $stmt_dieta->execute();
        
        // Deletar relações com consultas
        $sql_consulta = "DELETE FROM relacao_paciente_consulta WHERE id_paciente = :id_paciente";
        $stmt_consulta = $this->conn->prepare($sql_consulta);
        $stmt_consulta->bindParam(':id_paciente', $id_paciente);
        $stmt_consulta->execute();
        
        // Deletar histórico clínico
        $sql_historico = "DELETE FROM historico_clinico WHERE id_paciente = :id_paciente";
        $stmt_historico = $this->conn->prepare($sql_historico);
        $stmt_historico->bindParam(':id_paciente', $id_paciente);
        $stmt_historico->execute();
    }
}
?>