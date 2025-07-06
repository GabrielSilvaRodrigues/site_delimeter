<?php
session_start();

echo "<h1>Debug da Sessão e Banco</h1>";

echo "<h2>Sessão:</h2>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";

echo "<h2>Dados Antropométricos na Sessão:</h2>";
if (isset($_SESSION['dados_antropometricos'])) {
    echo "<pre>" . print_r($_SESSION['dados_antropometricos'], true) . "</pre>";
} else {
    echo "<p style='color: orange;'>Nenhum dado antropométrico na sessão</p>";
}

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
                
                // Verificar dados antropométricos
                $stmt = $conn->prepare('SELECT * FROM dados_antropometricos WHERE id_paciente = ? ORDER BY data_medida DESC LIMIT 1');
                $stmt->execute([$paciente['id_paciente']]);
                $dadosAntropometricos = $stmt->fetch();
                
                if ($dadosAntropometricos) {
                    echo "<p style='color: green;'>✅ Dados antropométricos encontrados no banco:</p>";
                    echo "<pre>" . print_r($dadosAntropometricos, true) . "</pre>";
                } else {
                    echo "<p style='color: orange;'>⚠️ Nenhum dado antropométrico encontrado no banco</p>";
                }
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
