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
        $route->add('GET', '/medico/conta/entrar', [$medicoController, 'procurarPorId']);
        
        // Rotas para funcionalidades do médico
        $route->add('GET', '/medico/pacientes', [$medicoController, 'mostrarPacientes']);
        $route->add('GET', '/medico/consultas', [$medicoController, 'mostrarConsultas']);
        $route->add('GET', '/medico/receitas', [$medicoController, 'mostrarReceitas']);
        $route->add('GET', '/medico/validar-dietas', [$medicoController, 'mostrarValidarDietas']);
        $route->add('GET', '/medico/prontuarios', [$medicoController, 'mostrarProntuarios']);
        
        // Rotas específicas para pacientes - ADICIONAR ESTAS ROTAS
        $route->add('GET', '/medico/paciente/{id}', [$medicoController, 'mostrarDetalhesPaciente']);
        $route->add('GET', '/medico/paciente/{id}/historico', [$medicoController, 'mostrarHistoricoPaciente']);
        
        // API routes para buscar dados de pacientes
        $route->add('GET', '/api/medico/pacientes', [$medicoController, 'listarPacientes']);
        $route->add('GET', '/api/medico/pacientes/buscar', [$medicoController, 'buscarPacientes']);
        $route->add('GET', '/api/medico/paciente', [$medicoController, 'obterPaciente']);
    }
}
?>