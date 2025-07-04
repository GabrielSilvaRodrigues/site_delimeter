<?php

require __DIR__ . '/../vendor/autoload.php';

// Inicia a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use Htdocs\Src\Routes\Routes;
use Htdocs\Src\Controllers\DelimeterController;

$route = new Routes();

// Inclui o header antes do dispatch
$delimeterController = new DelimeterController();
$delimeterController->mostrarHeader();

// Obtém a URL e método da requisição
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Despacha a rota
$route->dispatch($method, $uri);

// Inclui o footer após o dispatch
$delimeterController->mostrarFooter();
?>