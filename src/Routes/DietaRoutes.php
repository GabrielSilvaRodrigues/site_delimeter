<?php

namespace Htdocs\Src\Routes;

use Htdocs\Src\Models\Repository\DietaRepository;
use Htdocs\Src\Services\DietaService;
use Htdocs\Src\Controllers\DietaController;

class DietaRoutes {
    public function __construct($route) {
        $dietaRepository = new DietaRepository();
        $dietaService = new DietaService($dietaRepository);
        $dietaController = new DietaController($dietaService);

        // API Routes para dietas
        $route->add('POST', '/api/dietas/criar', [$dietaController, 'criar']);
        $route->add('GET', '/api/dietas/listar', [$dietaController, 'listar']);
        $route->add('GET', '/api/dietas/buscar-por-id', [$dietaController, 'buscarPorId']);
        $route->add('GET', '/api/dietas/buscar-por-paciente', [$dietaController, 'buscarPorPaciente']);
        $route->add('GET', '/api/dietas/buscar-por-nutricionista', [$dietaController, 'buscarPorNutricionista']);
        $route->add('PUT', '/api/dietas/atualizar', [$dietaController, 'atualizar']);
        $route->add('POST', '/api/dietas/deletar', [$dietaController, 'deletar']);
        $route->add('POST', '/api/dietas/associar-paciente', [$dietaController, 'associarPaciente']);
        $route->add('POST', '/api/dietas/associar-nutricionista', [$dietaController, 'associarNutricionista']);

        // View Routes
        $route->add('GET', '/nutricionista/dietas', [$dietaController, 'mostrarDietasNutricionista']);
        $route->add('GET', '/paciente/dietas', [$dietaController, 'mostrarDietasPaciente']);
    }
}
?>
