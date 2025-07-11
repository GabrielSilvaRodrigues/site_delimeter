<?php
// Script de debug para testar login
require_once __DIR__ . '/vendor/autoload.php';

use Htdocs\Src\Config\Connection;
use Htdocs\Src\Models\Repository\UsuarioRepository;
use Htdocs\Src\Services\UsuarioService;

echo "<h1>Debug do Sistema de Login</h1>";

// Testar conexão com banco
try {
    $database = new Connection();
    $conn = $database->getConnection();
    
    if (!$conn) {
        echo "<p style='color: red;'>❌ Erro: Não foi possível conectar ao banco de dados.</p>";
        exit(1);
    }
    
    echo "<p style='color: green;'>✅ Conexão com banco funcionando</p>";
    
    // Testar repositório
    $usuarioRepository = new UsuarioRepository();
    echo "<p style='color: green;'>✅ UsuarioRepository criado</p>";
    
    // Testar service
    $usuarioService = new UsuarioService($usuarioRepository);
    echo "<p style='color: green;'>✅ UsuarioService criado</p>";
    
    // Listar usuários no banco
    echo "<h2>Usuários no banco:</h2>";
    $usuarios = $usuarioRepository->findAll();
    
    if (empty($usuarios)) {
        echo "<p style='color: orange;'>⚠️ Nenhum usuário encontrado no banco</p>";
    } else {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Nome</th><th>Email</th><th>Status</th><th>Ações</th></tr>";
        foreach ($usuarios as $usuario) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($usuario['id_usuario']) . "</td>";
            echo "<td>" . htmlspecialchars($usuario['nome_usuario']) . "</td>";
            echo "<td>" . htmlspecialchars($usuario['email_usuario']) . "</td>";
            echo "<td>" . htmlspecialchars($usuario['status_usuario']) . "</td>";
            echo "<td><a href='?test_login=" . urlencode($usuario['email_usuario']) . "'>Testar Login</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Teste de login se solicitado
    if (isset($_GET['test_login'])) {
        $email = $_GET['test_login'];
        echo "<h2>Testando login para: " . htmlspecialchars($email) . "</h2>";
        
        // Buscar usuário
        $usuario = $usuarioRepository->findByEmail($email);
        if ($usuario) {
            echo "<p style='color: green;'>✅ Usuário encontrado</p>";
            echo "<p>ID: " . $usuario['id_usuario'] . "</p>";
            echo "<p>Nome: " . htmlspecialchars($usuario['nome_usuario']) . "</p>";
            echo "<p>Status: " . $usuario['status_usuario'] . "</p>";
            echo "<p>Hash da senha: " . substr($usuario['senha_usuario'], 0, 20) . "...</p>";
        } else {
            echo "<p style='color: red;'>❌ Usuário não encontrado</p>";
        }
    }
    
    // Formulário para criar usuário de teste
    echo "<h2>Criar Usuário de Teste</h2>";
    if (isset($_POST['criar_usuario'])) {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        
        if ($nome && $email && $senha) {
            try {
                $usuario = new \Htdocs\Src\Models\Entity\Usuario(
                    null,
                    $nome,
                    $email,
                    password_hash($senha, PASSWORD_DEFAULT)
                );
                
                $id = $usuarioService->criar($usuario);
                echo "<p style='color: green;'>✅ Usuário criado com ID: $id</p>";
                echo "<p>Email: $email</p>";
                echo "<p>Senha: $senha</p>";
            } catch (Exception $e) {
                echo "<p style='color: red;'>❌ Erro ao criar usuário: " . $e->getMessage() . "</p>";
            }
        }
    }
    
    echo '<form method="POST">';
    echo '<p><label>Nome: <input type="text" name="nome" value="Teste Usuario" required></label></p>';
    echo '<p><label>Email: <input type="email" name="email" value="gabriel11@gmail.com" required></label></p>';
    echo '<p><label>Senha: <input type="password" name="senha" value="123456" required></label></p>';
    echo '<p><button type="submit" name="criar_usuario">Criar Usuário</button></p>';
    echo '</form>';
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p><pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>Informações do Sistema</h2>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Session Status: " . session_status() . "</p>";
echo "<p>Error Log: " . ini_get('error_log') . "</p>";
?>

<style>
table { border-collapse: collapse; margin: 10px 0; }
th, td { padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
</style>
