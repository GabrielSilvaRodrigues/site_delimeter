<?php

namespace Htdocs\Src\Routes;

use Htdocs\Src\Models\Repository\MedicoRepository;
use Htdocs\Src\Services\MedicoService;
use Htdocs\Src\Controllers\MedicoController;

class MedicoRoutes {
    public function __construct($route) {
        $medicoRepository = new MedicoRepository();
        $medicoService = new MedicoService($medicoRepository);
        $medicoController = new MedicoController($medicoService);

        $route->add('POST', '/api/medico', [$medicoController, 'criar']);
        $route->add('GET', '/medico/cadastro', [$medicoController, 'mostrarFormulario']);
        $route->add('GET', '/medico/login', [$medicoController, 'mostrarLogin']);
        $route->add('POST', '/medico/conta/atualizar', [$medicoController, 'atualizarConta']);
        $route->add('POST', '/medico/conta/deletar', [$medicoController, 'deletarConta']);
        $route->add('GET', '/medico/conta/sair', [$medicoController, 'sairConta']);
        $route->add('GET', '/medico', [$medicoController, 'mostrarHome']);
    }
}
?>