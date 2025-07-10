<?php

namespace Htdocs\Src\Models\Repository;

use Htdocs\Src\Config\Connection;
use Htdocs\Src\Models\Entity\Usuario;
use PDO;

class UsuarioRepository {
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

    public function save(Usuario $usuario) {
        $sql = "INSERT INTO usuario (nome_usuario, email_usuario, senha_usuario, status_usuario) VALUES (:nome, :email, :senha, :status)";
        $stmt = $this->conn->prepare($sql);
        $nome = $usuario->getNome();
        $email = $usuario->getEmail();
        $senha = $usuario->getSenha();
        $status = $usuario->getStatus();
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function findById($id) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $sql = "SELECT * FROM usuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(Usuario $usuario) {
        $nome = $usuario->getNome();
        $email = $usuario->getEmail();
        $senha = $usuario->getSenha();
        $status = $usuario->getStatus();
        $id = $usuario->getId();

        if ($senha) {
            $sql = "UPDATE usuario SET nome_usuario = :nome, email_usuario = :email, senha_usuario = :senha, status_usuario = :status WHERE id_usuario = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);
        } else {
            $sql = "UPDATE usuario SET nome_usuario = :nome, email_usuario = :email, status_usuario = :status WHERE id_usuario = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);
        }
        $stmt->execute();
    }

    public function delete($id) {
        try {
            // Iniciar transação
            $this->conn->beginTransaction();
            
            // Deletar dados de endereço
            $sql_endereco = "DELETE FROM endereco_usuario WHERE id_usuario = :id";
            $stmt_endereco = $this->conn->prepare($sql_endereco);
            $stmt_endereco->bindParam(':id', $id);
            $stmt_endereco->execute();
            
            // Deletar dados de telefone
            $sql_telefone = "DELETE FROM telefone_usuario WHERE id_usuario = :id";
            $stmt_telefone = $this->conn->prepare($sql_telefone);
            $stmt_telefone->bindParam(':id', $id);
            $stmt_telefone->execute();
            
            // Verificar se é paciente e deletar dados relacionados
            $this->deleteIfPaciente($id);
            
            // Verificar se é nutricionista e deletar dados relacionados
            $this->deleteIfNutricionista($id);
            
            // Verificar se é médico e deletar dados relacionados
            $this->deleteIfMedico($id);
            
            // Deletar o usuário
            $sql = "DELETE FROM usuario WHERE id_usuario = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
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

    private function deleteIfPaciente($id_usuario) {
        $sql_check = "SELECT id_paciente FROM paciente WHERE id_usuario = :id_usuario";
        $stmt_check = $this->conn->prepare($sql_check);
        $stmt_check->bindParam(':id_usuario', $id_usuario);
        $stmt_check->execute();
        $paciente = $stmt_check->fetch(\PDO::FETCH_ASSOC);
        
        if ($paciente) {
            $id_paciente = $paciente['id_paciente'];
            
            // Deletar dados antropométricos
            $sql_dados = "DELETE FROM dados_antropometricos WHERE id_paciente = :id_paciente";
            $stmt_dados = $this->conn->prepare($sql_dados);
            $stmt_dados->bindParam(':id_paciente', $id_paciente);
            $stmt_dados->execute();
            
            // Deletar diários de alimentos
            $sql_diario = "DELETE FROM diario_de_alimentos WHERE id_paciente = :id_paciente";
            $stmt_diario = $this->conn->prepare($sql_diario);
            $stmt_diario->bindParam(':id_paciente', $id_paciente);
            $stmt_diario->execute();
            
            // Deletar relações
            $this->deletePatientRelations($id_paciente);
            
            // Deletar paciente
            $sql_paciente = "DELETE FROM paciente WHERE id_usuario = :id_usuario";
            $stmt_paciente = $this->conn->prepare($sql_paciente);
            $stmt_paciente->bindParam(':id_usuario', $id_usuario);
            $stmt_paciente->execute();
        }
    }

    private function deleteIfNutricionista($id_usuario) {
        $sql_check = "SELECT id_nutricionista FROM nutricionista WHERE id_usuario = :id_usuario";
        $stmt_check = $this->conn->prepare($sql_check);
        $stmt_check->bindParam(':id_usuario', $id_usuario);
        $stmt_check->execute();
        $nutricionista = $stmt_check->fetch(\PDO::FETCH_ASSOC);
        
        if ($nutricionista) {
            $id_nutricionista = $nutricionista['id_nutricionista'];
            
            // Deletar relações com dietas
            $sql_dieta = "DELETE FROM relacao_nutricionista_dieta WHERE id_nutricionista = :id_nutricionista";
            $stmt_dieta = $this->conn->prepare($sql_dieta);
            $stmt_dieta->bindParam(':id_nutricionista', $id_nutricionista);
            $stmt_dieta->execute();
            
            // Deletar validações
            $sql_valid_medidas = "DELETE FROM valida_medidas_nutricionista WHERE id_nutricionista = :id_nutricionista";
            $stmt_valid_medidas = $this->conn->prepare($sql_valid_medidas);
            $stmt_valid_medidas->bindParam(':id_nutricionista', $id_nutricionista);
            $stmt_valid_medidas->execute();
            
            $sql_valid_diario = "DELETE FROM valida_diario WHERE id_nutricionista = :id_nutricionista";
            $stmt_valid_diario = $this->conn->prepare($sql_valid_diario);
            $stmt_valid_diario->bindParam(':id_nutricionista', $id_nutricionista);
            $stmt_valid_diario->execute();
            
            // Deletar nutricionista
            $sql_nutricionista = "DELETE FROM nutricionista WHERE id_usuario = :id_usuario";
            $stmt_nutricionista = $this->conn->prepare($sql_nutricionista);
            $stmt_nutricionista->bindParam(':id_usuario', $id_usuario);
            $stmt_nutricionista->execute();
        }
    }

    private function deleteIfMedico($id_usuario) {
        $sql_check = "SELECT id_medico FROM medico WHERE id_usuario = :id_usuario";
        $stmt_check = $this->conn->prepare($sql_check);
        $stmt_check->bindParam(':id_usuario', $id_usuario);
        $stmt_check->execute();
        $medico = $stmt_check->fetch(\PDO::FETCH_ASSOC);
        
        if ($medico) {
            $id_medico = $medico['id_medico'];
            
            // Deletar validações
            $sql_valid_dados = "DELETE FROM valida_dados_antropometricos WHERE id_medico = :id_medico";
            $stmt_valid_dados = $this->conn->prepare($sql_valid_dados);
            $stmt_valid_dados->bindParam(':id_medico', $id_medico);
            $stmt_valid_dados->execute();
            
            $sql_valid_dieta = "DELETE FROM valida_dieta WHERE id_medico = :id_medico";
            $stmt_valid_dieta = $this->conn->prepare($sql_valid_dieta);
            $stmt_valid_dieta->bindParam(':id_medico', $id_medico);
            $stmt_valid_dieta->execute();
            
            $sql_valid_receita = "DELETE FROM valida_receita WHERE id_medico = :id_medico";
            $stmt_valid_receita = $this->conn->prepare($sql_valid_receita);
            $stmt_valid_receita->bindParam(':id_medico', $id_medico);
            $stmt_valid_receita->execute();
            
            // Deletar médico
            $sql_medico = "DELETE FROM medico WHERE id_usuario = :id_usuario";
            $stmt_medico = $this->conn->prepare($sql_medico);
            $stmt_medico->bindParam(':id_usuario', $id_usuario);
            $stmt_medico->execute();
        }
    }

    private function deletePatientRelations($id_paciente) {
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