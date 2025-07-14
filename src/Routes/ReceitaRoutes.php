<?php

namespace Htdocs\Src\Routes;

use Htdocs\Src\Models\Repository\ReceitaRepository;
use Htdocs\Src\Services\ReceitaService;
use Htdocs\Src\Controllers\ReceitaController;

class ReceitaRoutes {
    public function __construct($route) {
        $receitaRepository = new ReceitaRepository();
        $receitaService = new ReceitaService($receitaRepository);
        $receitaController = new ReceitaController($receitaService);

        // API Routes para receitas
        $route->add('POST', '/api/receitas/criar', [$receitaController, 'criar']);
        $route->add('GET', '/api/receitas/buscar-por-paciente', [$receitaController, 'buscarPorPaciente']);
        $route->add('POST', '/api/receitas/validar-por-medico', [$receitaController, 'validarPorMedico']);
    }
}
?>
