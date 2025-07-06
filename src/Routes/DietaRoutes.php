<?php

namespace Htdocs\Src\Routes;

use Htdocs\Src\Controllers\DietaController;
use Htdocs\Src\Services\DietaService;
use Htdocs\Src\Models\Repository\DietaRepository;

class DietaRoutes {
    public function __construct($router) {
        $repository = new DietaRepository();
        $service = new DietaService($repository);
        $controller = new DietaController($service);

        // API Routes para dietas
        $router->add('POST', '/api/dietas/criar', function() use ($controller) {
            $controller->criar();
        });

        $router->add('GET', '/api/dietas/listar', function() use ($controller) {
            $controller->listar();
        });

        $router->add('GET', '/api/dietas/buscar-por-id', function() use ($controller) {
            $controller->buscarPorId();
        });

        $router->add('GET', '/api/dietas/buscar-por-paciente', function() use ($controller) {
            $controller->buscarPorPaciente();
        });

        $router->add('GET', '/api/dietas/buscar-por-nutricionista', function() use ($controller) {
            $controller->buscarPorNutricionista();
        });

        $router->add('PUT', '/api/dietas/atualizar', function() use ($controller) {
            $controller->atualizar();
        });

        $router->add('DELETE', '/api/dietas/deletar', function() use ($controller) {
            $controller->deletar();
        });

        $router->add('POST', '/api/dietas/associar-paciente', function() use ($controller) {
            $controller->associarPaciente();
        });

        $router->add('POST', '/api/dietas/associar-nutricionista', function() use ($controller) {
            $controller->associarNutricionista();
        });

        // View Routes
        $router->add('GET', '/nutricionista/dietas', function() {
            session_start();
            include __DIR__ . '/../../view/includes/header.php';
            include __DIR__ . '/../../view/nutricionista/dietas.php';
            include __DIR__ . '/../../view/includes/footer.php';
        });

        $router->add('GET', '/paciente/dietas', function() {
            session_start();
            include __DIR__ . '/../../view/includes/header.php';
            include __DIR__ . '/../../view/paciente/dietas.php';
            include __DIR__ . '/../../view/includes/footer.php';
        });
    }
}
?>
