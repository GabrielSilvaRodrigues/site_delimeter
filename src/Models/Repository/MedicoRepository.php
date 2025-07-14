<?php

namespace Htdocs\Src\Models\Repository;

use Htdocs\Src\Config\Connection;
use Htdocs\Src\Models\Entity\Medico;
use PDO;

class MedicoRepository {
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

    public function save(Medico $medico) {
        try {
            $sql = "INSERT INTO medico (id_usuario, crm_medico, cpf) VALUES (:id_usuario, :crm_medico, :cpf)";
            $stmt = $this->conn->prepare($sql);
            $id_usuario = $medico->getIdUsuario();
            $crm_medico = $medico->getCrmMedico();
            $cpf = $medico->getCpf();
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':crm_medico', $crm_medico);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \Exception("Já existe um médico cadastrado com este CRM ou CPF.");
            }
            throw $e;
        }
    }

    public function findById($id_usuario) {
        $sql = "SELECT * FROM medico WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByUsuarioId($id_usuario) {
        $sql = "SELECT * FROM medico WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $sql = "SELECT * FROM medico";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(Medico $medico) {
        try {
            $sql = "UPDATE medico SET crm_medico = :crm_medico, cpf = :cpf WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($sql);
            $id_usuario = $medico->getIdUsuario();
            $crm_medico = $medico->getCrmMedico();
            $cpf = $medico->getCpf();
            $stmt->bindParam(':crm_medico', $crm_medico);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) { // Código de violação de integridade (duplicidade) MySQL
                $errorMessage = $e->getMessage();
                if (strpos($errorMessage, 'crm_medico') !== false) {
                    throw new \Exception("Já existe um médico cadastrado com este CRM.");
                } elseif (strpos($errorMessage, 'cpf') !== false) {
                    throw new \Exception("Já existe um médico cadastrado com este CPF.");
                } else {
                    throw new \Exception("Violação de integridade: dados duplicados.");
                }
            }
            throw $e;
        }
    }

    public function delete($id_usuario) {
        $sql = "DELETE FROM medico WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
    }

    public function procurarPorID($id_usuario) {
        $sql = "SELECT * FROM medico WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Listar todos os pacientes com informações básicas
     */
    public function listarTodosPacientes() {
        $sql = "SELECT 
                    p.id_paciente,
                    p.cpf,
                    p.nis,
                    u.nome_usuario,
                    u.email_usuario,
                    u.status_usuario,
                    da.peso_paciente,
                    da.altura_paciente,
                    da.data_medida,
                    da.sexo_paciente
                FROM paciente p
                INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                LEFT JOIN (
                    SELECT id_paciente, peso_paciente, altura_paciente, data_medida, sexo_paciente,
                           ROW_NUMBER() OVER (PARTITION BY id_paciente ORDER BY data_medida DESC) as rn
                    FROM dados_antropometricos
                ) da ON p.id_paciente = da.id_paciente AND da.rn = 1
                ORDER BY u.nome_usuario";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Processar dados para incluir última medida
        foreach ($pacientes as &$paciente) {
            if ($paciente['peso_paciente'] || $paciente['altura_paciente']) {
                $paciente['ultima_medida'] = [
                    'peso_paciente' => $paciente['peso_paciente'],
                    'altura_paciente' => $paciente['altura_paciente'],
                    'data_medida' => $paciente['data_medida'],
                    'sexo_paciente' => $paciente['sexo_paciente']
                ];
            } else {
                $paciente['ultima_medida'] = null;
            }
        }
        
        return $pacientes;
    }

    /**
     * Buscar pacientes por termo (nome ou CPF)
     */
    public function buscarPacientes($termo) {
        $sql = "SELECT 
                    p.id_paciente,
                    p.cpf,
                    p.nis,
                    u.nome_usuario,
                    u.email_usuario,
                    u.status_usuario,
                    da.peso_paciente,
                    da.altura_paciente,
                    da.data_medida,
                    da.sexo_paciente
                FROM paciente p
                INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                LEFT JOIN (
                    SELECT id_paciente, peso_paciente, altura_paciente, data_medida, sexo_paciente,
                           ROW_NUMBER() OVER (PARTITION BY id_paciente ORDER BY data_medida DESC) as rn
                    FROM dados_antropometricos
                ) da ON p.id_paciente = da.id_paciente AND da.rn = 1
                WHERE u.nome_usuario LIKE :termo 
                   OR p.cpf LIKE :termo
                   OR u.email_usuario LIKE :termo
                ORDER BY u.nome_usuario";
        
        $stmt = $this->conn->prepare($sql);
        $termoLike = '%' . $termo . '%';
        $stmt->bindParam(':termo', $termoLike);
        $stmt->execute();
        $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Processar dados para incluir última medida
        foreach ($pacientes as &$paciente) {
            if ($paciente['peso_paciente'] || $paciente['altura_paciente']) {
                $paciente['ultima_medida'] = [
                    'peso_paciente' => $paciente['peso_paciente'],
                    'altura_paciente' => $paciente['altura_paciente'],
                    'data_medida' => $paciente['data_medida'],
                    'sexo_paciente' => $paciente['sexo_paciente']
                ];
            } else {
                $paciente['ultima_medida'] = null;
            }
        }
        
        return $pacientes;
    }

    /**
     * Obter dados detalhados de um paciente específico
     */
    public function obterPacientePorId($idPaciente) {
        $sql = "SELECT 
                    p.id_paciente,
                    p.cpf,
                    p.nis,
                    u.nome_usuario,
                    u.email_usuario,
                    u.status_usuario,
                    eu.endereco,
                    tu.telefone
                FROM paciente p
                INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                LEFT JOIN endereco_usuario eu ON u.id_usuario = eu.id_usuario
                LEFT JOIN telefone_usuario tu ON u.id_usuario = tu.id_usuario
                WHERE p.id_paciente = :id_paciente";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_paciente', $idPaciente);
        $stmt->execute();
        $paciente = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($paciente) {
            // Buscar medidas antropométricas
            $sqlMedidas = "SELECT * FROM dados_antropometricos 
                          WHERE id_paciente = :id_paciente 
                          ORDER BY data_medida DESC";
            $stmtMedidas = $this->conn->prepare($sqlMedidas);
            $stmtMedidas->bindParam(':id_paciente', $idPaciente);
            $stmtMedidas->execute();
            $paciente['medidas'] = $stmtMedidas->fetchAll(PDO::FETCH_ASSOC);
            
            // Buscar diários alimentares recentes
            $sqlDiarios = "SELECT * FROM diario_de_alimentos 
                          WHERE id_paciente = :id_paciente 
                          ORDER BY data_diario DESC 
                          LIMIT 10";
            $stmtDiarios = $this->conn->prepare($sqlDiarios);
            $stmtDiarios->bindParam(':id_paciente', $idPaciente);
            $stmtDiarios->execute();
            $paciente['diarios'] = $stmtDiarios->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $paciente;
    }

    /**
     * Obter histórico completo de um paciente
     */
    public function obterHistoricoPaciente($idPaciente) {
        $paciente = $this->obterPacientePorId($idPaciente);
        
        if ($paciente) {
            // Buscar consultas
            $sqlConsultas = "SELECT c.*, a.status_agenda, a.observacoes, a.tipo_consulta,
                                   m.crm_medico, um.nome_usuario as nome_medico,
                                   n.crm_nutricionista, un.nome_usuario as nome_nutricionista
                            FROM consulta c
                            INNER JOIN agenda a ON c.id_consulta = a.id_consulta
                            LEFT JOIN medico m ON a.id_medico = m.id_medico
                            LEFT JOIN usuario um ON m.id_usuario = um.id_usuario
                            LEFT JOIN nutricionista n ON a.id_nutricionista = n.id_nutricionista
                            LEFT JOIN usuario un ON n.id_usuario = un.id_usuario
                            WHERE a.id_paciente = :id_paciente
                            ORDER BY c.data_consulta DESC";
            $stmtConsultas = $this->conn->prepare($sqlConsultas);
            $stmtConsultas->bindParam(':id_paciente', $idPaciente);
            $stmtConsultas->execute();
            $paciente['consultas'] = $stmtConsultas->fetchAll(PDO::FETCH_ASSOC);
            
            // Buscar receitas
            $sqlReceitas = "SELECT r.*, rpr.id_paciente
                           FROM receita r
                           INNER JOIN relacao_paciente_receita rpr ON r.id_receita = rpr.id_receita
                           WHERE rpr.id_paciente = :id_paciente
                           ORDER BY r.data_inicio_receita DESC";
            $stmtReceitas = $this->conn->prepare($sqlReceitas);
            $stmtReceitas->bindParam(':id_paciente', $idPaciente);
            $stmtReceitas->execute();
            $paciente['receitas'] = $stmtReceitas->fetchAll(PDO::FETCH_ASSOC);
            
            // Buscar dietas
            $sqlDietas = "SELECT d.*, rpd.id_paciente
                         FROM dieta d
                         INNER JOIN relacao_paciente_dieta rpd ON d.id_dieta = rpd.id_dieta
                         WHERE rpd.id_paciente = :id_paciente
                         ORDER BY d.data_inicio_dieta DESC";
            $stmtDietas = $this->conn->prepare($sqlDietas);
            $stmtDietas->bindParam(':id_paciente', $idPaciente);
            $stmtDietas->execute();
            $paciente['dietas'] = $stmtDietas->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $paciente;
    }
}
?>