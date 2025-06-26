<?php
use Htdocs\Src\Routes\Routes;
use Htdocs\Src\Models\Repository\PacienteRepository;
use Htdocs\Src\Services\PacienteService;
use Htdocs\Src\Controllers\PacienteController;

$pacienteRepository = new PacienteRepository();
$pacienteService = new PacienteService($pacienteRepository);
$pacienteController = new PacienteController($pacienteService);


$route = new Routes();
$route->add('POST', '/api/paciente', [$pacienteController, 'criar']);
$route->add('GET', '/home', [$pacienteController, 'mostrarHome']);
$route->add('GET', '/', [$pacienteController, 'mostrarFormulario']);
$route->add('GET', '/paciente/cadastro', [$pacienteController, 'mostrarFormulario']);
if($_SERVER['REQUEST_URI'] === '/delimeter/sobre') {
    include_once __DIR__ . '/delimeter.php';
} elseif($_SERVER['REQUEST_URI'] === '/delimeter/calculo') {
    include_once __DIR__ . '/delimeter.php';
}

?>