<?php

namespace Htdocs\Src\Routes;

use Htdocs\Src\Controllers\DiarioDeAlimentosController;
use Htdocs\Src\Services\DiarioDeAlimentosService;
use Htdocs\Src\Models\Repository\DiarioDeAlimentosRepository;

class DiarioDeAlimentosRoutes {
    public function __construct($router) {
        $repository = new DiarioDeAlimentosRepository();
        $service = new DiarioDeAlimentosService($repository);
        $controller = new DiarioDeAlimentosController($service);

        // API Routes para diário de alimentos
        $router->add('POST', '/api/diario-alimentos/criar', function() use ($controller) {
            $controller->criar();
        });

        $router->add('GET', '/api/diario-alimentos/listar', function() use ($controller) {
            $controller->listar();
        });

        $router->add('GET', '/api/diario-alimentos/buscar-por-id', function() use ($controller) {
            $controller->buscarPorId();
        });

        $router->add('GET', '/api/diario-alimentos/buscar-por-paciente', function() use ($controller) {
            $controller->buscarPorPaciente();
        });

        $router->add('GET', '/api/diario-alimentos/buscar-por-data', function() use ($controller) {
            $controller->buscarPorPacienteEData();
        });

        $router->add('GET', '/api/diario-alimentos/buscar-por-periodo', function() use ($controller) {
            $controller->buscarPorPeriodo();
        });

        $router->add('PUT', '/api/diario-alimentos/atualizar', function() use ($controller) {
            $controller->atualizar();
        });

        $router->add('DELETE', '/api/diario-alimentos/deletar', function() use ($controller) {
            $controller->deletar();
        });

        $router->add('POST', '/api/diario-alimentos/associar-alimento', function() use ($controller) {
            $controller->associarAlimento();
        });

        $router->add('DELETE', '/api/diario-alimentos/remover-alimento', function() use ($controller) {
            $controller->removerAlimento();
        });

        // View Routes
        $router->add('GET', '/paciente/diario-alimentos', function() {
            session_start();
            include __DIR__ . '/../../view/includes/header.php';
            include __DIR__ . '/../../view/paciente/diario-alimentos.php';
            include __DIR__ . '/../../view/includes/footer.php';
        });
    }
}
?>
