<?php

namespace Htdocs\Src\Routes;

use Htdocs\Src\Models\Repository\DiarioDeAlimentosRepository;
use Htdocs\Src\Services\DiarioDeAlimentosService;
use Htdocs\Src\Controllers\DiarioDeAlimentosController;

class DiarioDeAlimentosRoutes {
    public function __construct($route) {
        $diarioDeAlimentosRepository = new DiarioDeAlimentosRepository();
        $diarioDeAlimentosService = new DiarioDeAlimentosService($diarioDeAlimentosRepository);
        $diarioDeAlimentosController = new DiarioDeAlimentosController($diarioDeAlimentosService);

        // API Routes para diário de alimentos - garantir que não incluam HTML
        $route->add('POST', '/api/diario-alimentos/criar', [$diarioDeAlimentosController, 'criar']);
        $route->add('GET', '/api/diario-alimentos/listar', [$diarioDeAlimentosController, 'listar']);
        $route->add('GET', '/api/diario-alimentos/buscar-por-id', [$diarioDeAlimentosController, 'buscarPorId']);
        $route->add('GET', '/api/diario-alimentos/buscar-por-paciente', [$diarioDeAlimentosController, 'buscarPorPaciente']);
        $route->add('GET', '/api/diario-alimentos/buscar-por-data', [$diarioDeAlimentosController, 'buscarPorPacienteEData']);
        $route->add('GET', '/api/diario-alimentos/buscar-por-periodo', [$diarioDeAlimentosController, 'buscarPorPeriodo']);
        $route->add('PUT', '/api/diario-alimentos/atualizar', [$diarioDeAlimentosController, 'atualizar']);
        $route->add('POST', '/api/diario-alimentos/deletar', [$diarioDeAlimentosController, 'deletar']);
        $route->add('DELETE', '/api/diario-alimentos/deletar', [$diarioDeAlimentosController, 'deletar']);
        $route->add('POST', '/api/diario-alimentos/associar-alimento', [$diarioDeAlimentosController, 'associarAlimento']);
        $route->add('POST', '/api/diario-alimentos/remover-alimento', [$diarioDeAlimentosController, 'removerAlimento']);

        // View Routes
        $route->add('GET', '/paciente/diario-alimentos', [$diarioDeAlimentosController, 'mostrarDiarioAlimentos']);
    }
}
?>