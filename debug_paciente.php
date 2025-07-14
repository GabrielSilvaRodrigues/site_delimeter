<?php
// Teste para verificar dados na tabela paciente
require_once __DIR__ . '/vendor/autoload.php';

use Htdocs\Src\Config\Connection;

try {
    $database = new Connection();
    $conn = $database->getConnection();
    
    if (!$conn) {
        echo "Erro: Não foi possível conectar ao banco de dados.\n";
        exit(1);
    }
    
    // Verificar todos os registros da tabela paciente
    echo "<h2>Registros na tabela paciente:</h2>\n";
    $sql = "SELECT * FROM paciente";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($pacientes)) {
        echo "<p>Não há registros na tabela paciente.</p>\n";
    } else {
        echo "<table border='1'>\n";
        echo "<tr><th>ID Paciente</th><th>ID Usuario</th><th>CPF</th><th>NIS</th></tr>\n";
        foreach ($pacientes as $paciente) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($paciente['id_paciente']) . "</td>";
            echo "<td>" . htmlspecialchars($paciente['id_usuario']) . "</td>";
            echo "<td>" . htmlspecialchars($paciente['cpf']) . "</td>";
            echo "<td>" . htmlspecialchars($paciente['nis'] ?? 'NULL') . "</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
    }
    
    // Verificar todos os registros da tabela usuario
    echo "<h2>Registros na tabela usuario:</h2>\n";
    $sql = "SELECT * FROM usuario";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($usuarios)) {
        echo "<p>Não há registros na tabela usuario.</p>\n";
    } else {
        echo "<table border='1'>\n";
        echo "<tr><th>ID Usuario</th><th>Nome</th><th>Email</th></tr>\n";
        foreach ($usuarios as $usuario) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($usuario['id_usuario']) . "</td>";
            echo "<td>" . htmlspecialchars($usuario['nome_usuario']) . "</td>";
            echo "<td>" . htmlspecialchars($usuario['email_usuario']) . "</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
    }
    
    // Verificar a estrutura da tabela paciente
    echo "<h2>Estrutura da tabela paciente:</h2>\n";
    $sql = "DESCRIBE paciente";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $estrutura = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1'>\n";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>\n";
    foreach ($estrutura as $campo) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($campo['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($campo['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($campo['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($campo['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($campo['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($campo['Extra']) . "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    
    // Botão para limpar tabela paciente (use com cuidado)
    echo "<h2>Ações de Debug:</h2>\n";
    if (isset($_GET['limpar_pacientes']) && $_GET['limpar_pacientes'] === 'confirmar') {
        try {
            $sql = "DELETE FROM paciente";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            echo "<p style='color: green;'>Tabela paciente limpa com sucesso!</p>\n";
        } catch (Exception $e) {
            echo "<p style='color: red;'>Erro ao limpar tabela: " . $e->getMessage() . "</p>\n";
        }
    } else {
        echo "<p><a href='?limpar_pacientes=confirmar' onclick='return confirm(\"Tem certeza que deseja limpar a tabela paciente?\");' style='color: red;'>Limpar tabela paciente</a></p>\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
?>
