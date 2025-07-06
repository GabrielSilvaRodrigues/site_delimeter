<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:3000'); // React app URL
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/vendor/autoload.php';

use Htdocs\Src\Routes\Routes;
use Htdocs\Src\Routes\DelimeterRoutes;
use Htdocs\Src\Routes\MedicoRoutes;
use Htdocs\Src\Routes\NutricionistaRoutes;
use Htdocs\Src\Routes\PacienteRoutes;
use Htdocs\Src\Routes\UsuarioRoutes;

$routes = new Routes();

// API routes
$delimeterRoutes = new DelimeterRoutes($routes);
$medicoRoutes = new MedicoRoutes($routes);  
$nutricionistaRoutes = new NutricionistaRoutes($routes);
$pacienteRoutes = new PacienteRoutes($routes);
$usuarioRoutes = new UsuarioRoutes($routes);

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove /backend from path if present
if (strpos($path, '/backend') === 0) {
    $path = substr($path, 8);
}

// Handle the request
try {
    $routes->handle($method, $path);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
}
