<?php

namespace Htdocs\Src\Routes;

use Htdocs\Src\Models\Repository\PacienteRepository;
use Htdocs\Src\Services\PacienteService;
use Htdocs\Src\Controllers\PacienteController;

class PacienteRoutes {
    public function __construct($route) {
        $pacienteRepository = new PacienteRepository();
        $pacienteService = new PacienteService($pacienteRepository);
        $pacienteController = new PacienteController($pacienteService);

        // Painel do paciente
        $route->add('GET', '/paciente', [$pacienteController, 'mostrarHome']);
        $route->add('POST', '/api/paciente', [$pacienteController, 'criar']);
        $route->add('GET', '/paciente/cadastro', [$pacienteController, 'mostrarFormulario']);
        $route->add('POST', '/paciente/conta/atualizar', [$pacienteController, 'atualizarConta']);
        $route->add('POST', '/paciente/conta/deletar', [$pacienteController, 'deletarConta']);
        $route->add('GET', '/paciente/conta/sair', [$pacienteController, 'sairConta']);
    }
}
?>