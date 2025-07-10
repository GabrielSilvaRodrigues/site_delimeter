<?php

namespace Htdocs\Src\Routes;

use Htdocs\Src\Models\Repository\DadosAntropometricosRepository;
use Htdocs\Src\Services\DadosAntropometricosService;
use Htdocs\Src\Controllers\DadosAntropometricosController;

class DadosAntropometricosRoutes {
    public function __construct($route) {
        $dadosAntropometricosRepository = new DadosAntropometricosRepository();
        $dadosAntropometricosService = new DadosAntropometricosService($dadosAntropometricosRepository);
        $dadosAntropometricosController = new DadosAntropometricosController($dadosAntropometricosService);

        // API Routes para dados antropométricos
        $route->add('POST', '/api/dados-antropometricos/criar', [$dadosAntropometricosController, 'criar']);
        $route->add('GET', '/api/dados-antropometricos/listar', [$dadosAntropometricosController, 'listar']);
        $route->add('GET', '/api/dados-antropometricos/buscar-por-paciente', [$dadosAntropometricosController, 'buscarPorPaciente']);
        $route->add('GET', '/api/dados-antropometricos/buscar/{id}', [$dadosAntropometricosController, 'buscarPorId']);
        $route->add('GET', '/api/dados-antropometricos/ultima-medida', [$dadosAntropometricosController, 'buscarUltimaMedida']);
        $route->add('PUT', '/api/dados-antropometricos/atualizar', [$dadosAntropometricosController, 'atualizar']);
        $route->add('POST', '/api/dados-antropometricos/deletar', [$dadosAntropometricosController, 'deletar']);
        $route->add('DELETE', '/api/dados-antropometricos/deletar', [$dadosAntropometricosController, 'deletar']);
        $route->add('GET', '/api/dados-antropometricos/calcular-imc', [$dadosAntropometricosController, 'calcularIMC']);
    }
}
?>