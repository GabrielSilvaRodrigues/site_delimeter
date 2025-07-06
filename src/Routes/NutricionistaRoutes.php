<?php

namespace Htdocs\Src\Routes;

use Htdocs\Src\Models\Repository\NutricionistaRepository;
use Htdocs\Src\Services\NutricionistaService;
use Htdocs\Src\Controllers\NutricionistaController;

class NutricionistaRoutes {
    public function __construct($route) {
        $nutricionistaRepository = new NutricionistaRepository();
        $nutricionistaService = new NutricionistaService($nutricionistaRepository);
        $nutricionistaController = new NutricionistaController($nutricionistaService);

        $route->add('POST', '/api/nutricionista', [$nutricionistaController, 'criar']);
        $route->add('GET', '/nutricionista/cadastro', [$nutricionistaController, 'mostrarFormulario']);
        $route->add('GET', '/nutricionista/login', [$nutricionistaController, 'mostrarLogin']);
        $route->add('POST', '/nutricionista/conta/atualizar', [$nutricionistaController, 'atualizarConta']);
        $route->add('POST', '/nutricionista/conta/deletar', [$nutricionistaController, 'deletarConta']);
        $route->add('GET', '/nutricionista/conta/sair', [$nutricionistaController, 'sairConta']);
        $route->add('GET', '/nutricionista', [$nutricionistaController, 'mostrarHome']);
    }
}
?>