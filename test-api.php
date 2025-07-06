<?php
session_start();

// Headers para debug
header('Content-Type: text/html; charset=utf-8');

echo "<h1>Teste da API de Dados Antropométricos</h1>";

// Verificar se a rota da API existe
echo "<h2>Teste das Rotas da API:</h2>";

// URLs para testar
$urls = [
    '/api/dados-antropometricos/listar',
    '/api/dados-antropometricos/buscar-por-paciente?id_paciente=1',
    '/api/dados-antropometricos/calcular-imc?altura=1.75&peso=70'
];

foreach ($urls as $url) {
    echo "<h3>Testando: {$url}</h3>";
    
    $full_url = "https://expert-happiness-r47jr75rrg4whp995-8000.app.github.dev" . $url;
    
    // Usar cURL para testar
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $full_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    echo "<p><strong>HTTP Code:</strong> {$httpCode}</p>";
    if ($error) {
        echo "<p style='color: red;'><strong>Erro cURL:</strong> {$error}</p>";
    }
    echo "<p><strong>Resposta:</strong></p>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>";
    echo htmlspecialchars($response);
    echo "</pre>";
    echo "<hr>";
}

// Testar roteamento local
echo "<h2>Teste de Roteamento Local:</h2>";

try {
    require_once 'vendor/autoload.php';
    
    $router = new \Htdocs\Src\Routes\Routes();
    $routes = $router->getRoutes();
    
    echo "<p>Total de rotas registradas: " . count($routes) . "</p>";
    
    echo "<h3>Rotas da API de Dados Antropométricos:</h3>";
    foreach ($routes as $route) {
        if (strpos($route['path'], 'dados-antropometricos') !== false) {
            echo "<p><strong>{$route['method']}</strong> {$route['path']}</p>";
        }
    }
    
    // Testar se podemos criar o controller diretamente
    echo "<h3>Teste Direto do Controller:</h3>";
    
    $repository = new \Htdocs\Src\Models\Repository\DadosAntropometricosRepository();
    $service = new \Htdocs\Src\Services\DadosAntropometricosService($repository);
    $controller = new \Htdocs\Src\Controllers\DadosAntropometricosController($service);
    
    echo "<p style='color: green;'>✅ Controller criado com sucesso!</p>";
    
    // Simular uma chamada para listar
    ob_start();
    $controller->listar();
    $output = ob_get_clean();
    
    echo "<p><strong>Saída do listar():</strong></p>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>";
    echo htmlspecialchars($output);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
