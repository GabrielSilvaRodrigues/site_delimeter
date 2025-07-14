<?php

namespace Htdocs\Src\Routes;

use Htdocs\Src\Models\Repository\AlimentoRepository;
use Htdocs\Src\Services\AlimentoService;
use Htdocs\Src\Controllers\AlimentoController;

class AlimentoRoutes {
    public function __construct($route) {
        $alimentoRepository = new AlimentoRepository();
        $alimentoService = new AlimentoService($alimentoRepository);
        $alimentoController = new AlimentoController($alimentoService);
        
        // API Routes para alimentos
        $route->add('POST', '/api/alimentos/criar', [$alimentoController, 'criar']);
        $route->add('GET', '/api/alimentos/listar', [$alimentoController, 'listar']);
        $route->add('GET', '/api/alimentos/buscar-por-id', [$alimentoController, 'buscarPorId']);
        $route->add('GET', '/api/alimentos/buscar-por-descricao', [$alimentoController, 'buscarPorDescricao']);
        $route->add('GET', '/api/alimentos/buscar-por-dieta', [$alimentoController, 'buscarPorDieta']);
        $route->add('GET', '/api/alimentos/buscar-por-diario', [$alimentoController, 'buscarPorDiario']);
        $route->add('PUT', '/api/alimentos/atualizar', [$alimentoController, 'atualizar']);
        $route->add('POST', '/api/alimentos/deletar', [$alimentoController, 'deletar']);
        $route->add('GET', '/api/alimentos/buscar-avancado', [$alimentoController, 'buscarAvancado']);

        // View Routes
        $route->add('GET', '/nutricionista/alimentos', [$alimentoController, 'mostrarAlimentosNutricionista']);
    }
}
?>