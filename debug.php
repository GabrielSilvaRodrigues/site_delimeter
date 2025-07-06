<?php
session_start();

echo "<h1>Debug da Sessão e Banco</h1>";

echo "<h2>Sessão:</h2>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";

echo "<h2>Teste de Conexão com Banco:</h2>";
try {
    require_once 'vendor/autoload.php';
    
    $connection = new \Htdocs\Src\Config\Connection();
    $conn = $connection->getConnection();
    
    if ($conn) {
        echo "<p style='color: green;'>✅ Conexão com banco estabelecida com sucesso!</p>";
        
        // Verificar usuários
        $stmt = $conn->prepare('SELECT COUNT(*) as total FROM usuario');
        $stmt->execute();
        $userCount = $stmt->fetch()['total'];
        echo "<p>Total de usuários: {$userCount}</p>";
        
        // Verificar pacientes
        $stmt = $conn->prepare('SELECT COUNT(*) as total FROM paciente');
        $stmt->execute();
        $pacienteCount = $stmt->fetch()['total'];
        echo "<p>Total de pacientes: {$pacienteCount}</p>";
        
        // Se há sessão de usuário, verificar se é paciente
        if (isset($_SESSION['usuario']['id_usuario'])) {
            $id_usuario = $_SESSION['usuario']['id_usuario'];
            echo "<p>ID do usuário na sessão: {$id_usuario}</p>";
            
            $stmt = $conn->prepare('SELECT * FROM paciente WHERE id_usuario = ?');
            $stmt->execute([$id_usuario]);
            $paciente = $stmt->fetch();
            
            if ($paciente) {
                echo "<p style='color: green;'>✅ Paciente encontrado no banco:</p>";
                echo "<pre>" . print_r($paciente, true) . "</pre>";
            } else {
                echo "<p style='color: red;'>❌ Paciente NÃO encontrado para este usuário</p>";
            }
        } else {
            echo "<p style='color: orange;'>⚠️ Não há usuário logado na sessão</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Erro ao conectar com o banco</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
}

echo "<h2>Informações do Servidor:</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";
?>
