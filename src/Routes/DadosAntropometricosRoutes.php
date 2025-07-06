<?php

namespace Htdocs\Src\Routes;

use Htdocs\Src\Controllers\DadosAntropometricosController;
use Htdocs\Src\Services\DadosAntropometricosService;
use Htdocs\Src\Models\Repository\DadosAntropometricosRepository;

class DadosAntropometricosRoutes {
    public function __construct($router) {
        $repository = new DadosAntropometricosRepository();
        $service = new DadosAntropometricosService($repository);
        $controller = new DadosAntropometricosController($service);

        // API Routes para dados antropométricos
        $router->add('POST', '/api/dados-antropometricos/criar', function() use ($controller) {
            $controller->criar();
        });

        $router->add('GET', '/api/dados-antropometricos/listar', function() use ($controller) {
            $controller->listar();
        });

        $router->add('GET', '/api/dados-antropometricos/buscar-por-paciente', function() use ($controller) {
            $controller->buscarPorPaciente();
        });

        $router->add('GET', '/api/dados-antropometricos/ultima-medida', function() use ($controller) {
            $controller->buscarUltimaMedida();
        });

        $router->add('PUT', '/api/dados-antropometricos/atualizar', function() use ($controller) {
            $controller->atualizar();
        });

        $router->add('DELETE', '/api/dados-antropometricos/deletar', function() use ($controller) {
            $controller->deletar();
        });

        $router->add('GET', '/api/dados-antropometricos/calcular-imc', function() use ($controller) {
            $controller->calcularIMC();
        });

        // Nota: A rota '/paciente/dados-antropometricos' está definida em PacienteRoutes.php
        // para garantir que os dados do paciente estejam carregados na sessão
    }
}
?>
