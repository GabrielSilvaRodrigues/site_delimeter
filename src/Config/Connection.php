<?php
namespace Htdocs\Src\Config;

use PDO;
use PDOException;

class Connection {
    // Configurações para InfinityFree
    private $host = "sql304.infinityfree.com"; // Substitua pelo host fornecido pelo InfinityFree
    private $db_name = "if0_37912345_delimeter"; // Substitua pelo nome do banco fornecido
    private $username = "if0_37912345"; // Substitua pelo usuário fornecido
    private $password = "sua_senha_aqui"; // Substitua pela senha do banco
    private $conn;

    public function getConnection() {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->db_name;charset=utf8mb4", 
                $this->username, 
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );
            return $this->conn;
        } catch (PDOException $error) {
            // Em produção, não exibir erro detalhado
            error_log("Database connection error: " . $error->getMessage());
            echo "Erro de conexão com o banco de dados. Tente novamente mais tarde.";
            return null;
        }
    }
}
?>