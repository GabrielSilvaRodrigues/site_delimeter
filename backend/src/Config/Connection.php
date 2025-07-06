<?php
namespace Htdocs\Src\Config;

use PDO;
use PDOException;

class Connection {
    private $host = "db"; // nome do serviço do banco no docker-compose
    private $db_name = "delimeter"; // alterado para o nome correto do banco de dados
    private $username = "root"; // alterado para o usuário correto
    private $password = "root"; // alterado para a senha correta
    private $conn;

    public function getConnection() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);
            $this->conn->exec('set names utf8');
            return $this->conn;
        } catch (PDOException $error) {
            echo "Error: " . $error->getMessage();
            return null;
        }
        return $this->conn;
    }
}
?>