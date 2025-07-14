<?php

namespace Htdocs\Src\Routes;

use Htdocs\Src\Models\Repository\ConsultaRepository;
use Htdocs\Src\Services\ConsultaService;
use Htdocs\Src\Controllers\ConsultaController;

// Adicionar imports para dados antropométricos
use Htdocs\Src\Models\Repository\DadosAntropometricosRepository;
use Htdocs\Src\Services\DadosAntropometricosService;
use Htdocs\Src\Controllers\DadosAntropometricosController;

class ConsultaRoutes {
    public function __construct($route) {
        $consultaRepository = new ConsultaRepository();
        $consultaService = new ConsultaService($consultaRepository);
        $consultaController = new ConsultaController($consultaService);

        // API Routes para consultas
        $route->add('POST', '/api/consultas/criar', [$consultaController, 'criar']);
        $route->add('GET', '/api/consultas/listar', [$consultaController, 'listar']);
        $route->add('GET', '/api/consultas/buscar-por-paciente', [$consultaController, 'buscarPorPaciente']);
        $route->add('GET', '/api/consultas/buscar-por-medico', [$consultaController, 'buscarPorMedico']);
        $route->add('GET', '/api/consultas/buscar-por-nutricionista', [$consultaController, 'buscarPorNutricionista']);
        $route->add('POST', '/api/consultas/deletar', [$consultaController, 'deletar']);
        $route->add('DELETE', '/api/consultas/deletar', [$consultaController, 'deletar']);

        // API Routes para dados antropométricos
        $dadosRepository = new DadosAntropometricosRepository();
        $dadosService = new DadosAntropometricosService($dadosRepository);
        $dadosController = new DadosAntropometricosController($dadosService);

        $route->add('POST', '/api/dados-antropometricos/criar', [$dadosController, 'criar']);
        $route->add('GET', '/api/dados-antropometricos/listar', [$dadosController, 'listar']);
        $route->add('GET', '/api/dados-antropometricos/buscar-por-paciente', [$dadosController, 'buscarPorPaciente']);
        $route->add('GET', '/api/dados-antropometricos/buscar-ultima-medida', [$dadosController, 'buscarUltimaMedida']);
        $route->add('GET', '/api/dados-antropometricos/calcular-imc', [$dadosController, 'calcularIMC']);
        $route->add('POST', '/api/dados-antropometricos/atualizar', [$dadosController, 'atualizar']);
        $route->add('POST', '/api/dados-antropometricos/deletar', [$dadosController, 'deletar']);
        $route->add('DELETE', '/api/dados-antropometricos/deletar', [$dadosController, 'deletar']);
    }
}
?>