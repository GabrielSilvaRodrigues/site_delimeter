<?php
use Htdocs\Src\Routes\Routes;
use Htdocs\Src\Models\Repository\UsuarioRepository;
use Htdocs\Src\Services\UsuarioService;
use Htdocs\Src\Controllers\UsuarioController;

$usuarioRepository = new UsuarioRepository();
$usuarioService = new UsuarioService($usuarioRepository);
$usuarioController = new UsuarioController($usuarioService);

$route = new Routes();

$route->add('GET', '/', [$usuarioController, 'mostrarHome']);

$route->add('POST', '/api/usuario', [$usuarioController, 'criar']);
$route->add('GET', '/usuario/cadastro', [$usuarioController, 'mostrarFormulario']);

$route->add('POST', '/login/usuario', [$usuarioController, 'entrar']);
$route->add('GET', '/usuario/login', [$usuarioController, 'mostrarLogin']);

$route->add('GET', '/conta', [$usuarioController, 'mostrarConta']);
$route->add('POST', '/conta/atualizar', [$usuarioController, 'atualizarConta']);

// $route->add('GET', '/conta/editar', [$usuarioController, 'mostrarFormularioEdicao']);
// $route->add('GET', '/conta/editar/senha', [$usuarioController, 'mostrarFormularioEdicaoSenha']);

$route->add('POST', '/conta/deletar', [$usuarioController, 'deletarConta']);
$route->add('GET', '/conta/sair', [$usuarioController, 'sairConta']);

if($_SERVER['REQUEST_URI'] === '/delimeter/sobre') {
    include_once __DIR__ . '/delimeter.php';
} elseif($_SERVER['REQUEST_URI'] === '/delimeter/calculo') {
    include_once __DIR__ . '/delimeter.php';
}
?>
