<?php

namespace Htdocs\Src\Routes;

use Htdocs\Src\Controllers\AlimentoController;
use Htdocs\Src\Services\AlimentoService;
use Htdocs\Src\Models\Repository\AlimentoRepository;

class AlimentoRoutes {
    public function __construct($router) {
        $repository = new AlimentoRepository();
        $service = new AlimentoService($repository);
        $controller = new AlimentoController($service);

        // API Routes para alimentos
        $router->add('POST', '/api/alimentos/criar', function() use ($controller) {
            $controller->criar();
        });

        $router->add('GET', '/api/alimentos/listar', function() use ($controller) {
            $controller->listar();
        });

        $router->add('GET', '/api/alimentos/buscar-por-id', function() use ($controller) {
            $controller->buscarPorId();
        });

        $router->add('GET', '/api/alimentos/buscar-por-descricao', function() use ($controller) {
            $controller->buscarPorDescricao();
        });

        $router->add('GET', '/api/alimentos/buscar-por-dieta', function() use ($controller) {
            $controller->buscarPorDieta();
        });

        $router->add('GET', '/api/alimentos/buscar-por-diario', function() use ($controller) {
            $controller->buscarPorDiario();
        });

        $router->add('PUT', '/api/alimentos/atualizar', function() use ($controller) {
            $controller->atualizar();
        });

        $router->add('DELETE', '/api/alimentos/deletar', function() use ($controller) {
            $controller->deletar();
        });

        $router->add('GET', '/api/alimentos/buscar-avancado', function() use ($controller) {
            $controller->buscarAvancado();
        });

        // View Routes
        $router->add('GET', '/nutricionista/alimentos', function() {
            session_start();
            include __DIR__ . '/../../view/includes/header.php';
            include __DIR__ . '/../../view/nutricionista/alimentos.php';
            include __DIR__ . '/../../view/includes/footer.php';
        });
    }
}
?>
