<?php

namespace Htdocs\Src\Routes;

use Htdocs\Src\Models\Repository\UsuarioRepository;
use Htdocs\Src\Services\UsuarioService;
use Htdocs\Src\Controllers\UsuarioController;

class UsuarioRoutes {
    public function __construct($route) {
        $usuarioRepository = new UsuarioRepository();
        $usuarioService = new UsuarioService($usuarioRepository);
        $usuarioController = new UsuarioController($usuarioService);

        $route->add('POST', '/api/usuario', [$usuarioController, 'criar']);
        $route->add('GET', '/usuario/cadastro', [$usuarioController, 'mostrarFormulario']);
        $route->add('POST', '/login/usuario', [$usuarioController, 'entrar']);
        $route->add('GET', '/usuario/login', [$usuarioController, 'mostrarLogin']);
        $route->add('GET', '/conta', [$usuarioController, 'mostrarConta']);
        $route->add('POST', '/conta/atualizar', [$usuarioController, 'atualizarConta']);
        $route->add('POST', '/conta/deletar', [$usuarioController, 'deletarConta']);
        $route->add('GET', '/conta/sair', [$usuarioController, 'sairConta']);
        $route->add('GET', '/usuario', [$usuarioController, 'mostrarHome']);
        
        // Novas rotas para gerenciar perfis específicos
        $route->add('POST', '/conta/perfil/criar', [$usuarioController, 'criarPerfil']);
        $route->add('POST', '/conta/perfil/atualizar', [$usuarioController, 'atualizarPerfil']);
        $route->add('POST', '/conta/perfil/excluir', [$usuarioController, 'excluirPerfil']);
        $route->add('GET', '/conta/perfil/sair', [$usuarioController, 'sairPerfil']);
    }
}
?>
