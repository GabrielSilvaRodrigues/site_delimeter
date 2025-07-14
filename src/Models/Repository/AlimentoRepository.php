<?php

namespace Htdocs\Src\Models\Repository;

use Htdocs\Src\Config\Connection;
use Htdocs\Src\Models\Entity\Alimento;
use PDO;

class AlimentoRepository {
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

    public function save(Alimento $alimento) {
        $sql = "INSERT INTO alimento (descricao_alimento, dados_nutricionais) 
                VALUES (:descricao_alimento, :dados_nutricionais)";
        $stmt = $this->conn->prepare($sql);
        
        $descricao_alimento = $alimento->getDescricaoAlimento();
        $dados_nutricionais = $alimento->getDadosNutricionais();
        
        $stmt->bindParam(':descricao_alimento', $descricao_alimento);
        $stmt->bindParam(':dados_nutricionais', $dados_nutricionais);
        
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function findById($id_alimento) {
        $sql = "SELECT * FROM alimento WHERE id_alimento = :id_alimento";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_alimento', $id_alimento);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByDescricao($descricao) {
        $sql = "SELECT * FROM alimento WHERE descricao_alimento LIKE :descricao ORDER BY descricao_alimento";
        $stmt = $this->conn->prepare($sql);
        $descricao_like = '%' . $descricao . '%';
        $stmt->bindParam(':descricao', $descricao_like);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByDietaId($id_dieta) {
        $sql = "SELECT a.* FROM alimento a 
                JOIN relacao_alimento_dieta rad ON a.id_alimento = rad.id_alimento 
                WHERE rad.id_dieta = :id_dieta 
                ORDER BY a.descricao_alimento";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_dieta', $id_dieta);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByDiarioId($id_diario) {
        $sql = "SELECT a.* FROM alimento a 
                JOIN relacao_diario_alimento rda ON a.id_alimento = rda.id_alimento 
                WHERE rda.id_diario = :id_diario 
                ORDER BY a.descricao_alimento";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_diario', $id_diario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $sql = "SELECT * FROM alimento ORDER BY descricao_alimento";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(Alimento $alimento) {
        $sql = "UPDATE alimento SET 
                descricao_alimento = :descricao_alimento, 
                dados_nutricionais = :dados_nutricionais 
                WHERE id_alimento = :id_alimento";
        
        $stmt = $this->conn->prepare($sql);
        
        $id_alimento = $alimento->getIdAlimento();
        $descricao_alimento = $alimento->getDescricaoAlimento();
        $dados_nutricionais = $alimento->getDadosNutricionais();
        
        $stmt->bindParam(':id_alimento', $id_alimento);
        $stmt->bindParam(':descricao_alimento', $descricao_alimento);
        $stmt->bindParam(':dados_nutricionais', $dados_nutricionais);
        
        $stmt->execute();
    }

    public function delete($id_alimento) {
        $sql = "DELETE FROM alimento WHERE id_alimento = :id_alimento";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_alimento', $id_alimento);
        $stmt->execute();
    }
}
?>
