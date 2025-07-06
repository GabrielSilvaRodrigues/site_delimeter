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
        $route->add('GET', '/paciente', function() use ($pacienteController) {
            $pacienteController->mostrarHome();
        });
        $route->add('GET', '/paciente/dados-antropometricos', function() use ($pacienteController) {
            $pacienteController->mostrarDadosAntropometricos();
        });
        $route->add('GET', '/paciente/diario-alimentos', function() use ($pacienteController) {
            $pacienteController->mostrarDiarioAlimentos();
        });
        $route->add('POST', '/api/paciente', function() use ($pacienteController) {
            $pacienteController->criar();
        });
        $route->add('GET', '/paciente/cadastro', function() use ($pacienteController) {
            $pacienteController->mostrarFormulario();
        });
        $route->add('POST', '/paciente/conta/atualizar', function() use ($pacienteController) {
            $pacienteController->atualizarConta();
        });
        $route->add('POST', '/paciente/conta/deletar', function() use ($pacienteController) {
            $pacienteController->deletarConta();
        });
        $route->add('GET', '/paciente/conta/sair', function() use ($pacienteController) {
            $pacienteController->sairConta();
        });
    }
}
?>