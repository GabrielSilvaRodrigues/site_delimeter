<?php
use Htdocs\Src\Routes\Routes;
use Htdocs\Src\Controllers\DelimeterController;

$route = new Routes();
$delimeterController = new DelimeterController();

$route->add('GET', '/', [$delimeterController, 'mostrarHome']);
$route->add('GET', '/delimeter/calculo', [$delimeterController, 'mostrarCalculo']);
$route->add('GET', '/delimeter/sobre', [$delimeterController, 'mostrarSobre']);

if($_SERVER['REQUEST_URI'] === '/usuario/cadastro') {
    include_once __DIR__ . '/usuario.php';
} elseif($_SERVER['REQUEST_URI'] === '/usuario/login') {
    include_once __DIR__ . '/usuario.php';
} elseif($_SERVER['REQUEST_URI'] === '/api/usuario') {
    include_once __DIR__ . '/usuario.php';
} elseif($_SERVER['REQUEST_URI'] === '/login/usuario') {
    include_once __DIR__ . '/usuario.php';
}

?>