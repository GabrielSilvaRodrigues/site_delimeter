<?php
// Teste básico do sistema
echo "<h1>Sistema Delimeter - Teste de Funcionamento</h1>";

// Testar autoload
try {
    require_once __DIR__ . '/../vendor/autoload.php';
    echo "<p style='color: green;'>✅ Autoload funcionando</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro no autoload: " . $e->getMessage() . "</p>";
}

// Testar sessão
session_start();
echo "<p style='color: green;'>✅ Sessão iniciada</p>";

// Testar login
echo "<h2>Teste da View de Login</h2>";
echo "<p><a href='/usuario/login' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ir para Login</a></p>";

// Testar banco de dados
try {
    require_once __DIR__ . '/../src/Config/Connection.php';
    $pdo = \Htdocs\Src\Config\Connection::getInstance();
    echo "<p style='color: green;'>✅ Conexão com banco de dados funcionando</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro na conexão com banco: " . $e->getMessage() . "</p>";
}

phpinfo();
?>
