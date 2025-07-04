<?php

namespace Htdocs\Src\Routes;
use Htdocs\Src\Routes\DelimeterRoutes;
use Htdocs\Src\Routes\MedicoRoutes;
use Htdocs\Src\Routes\NutricionistaRoutes;
use Htdocs\Src\Routes\PacienteRoutes;
use Htdocs\Src\Routes\UsuarioRoutes;

class Routes {
    private $routes = [];
    
    // Adiciona uma rota ao array de rotas
    public function add(string $method, string $path, callable $handler) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    // Busca uma rota pelo método e caminho
    public function match(string $method, string $path) {
        foreach ($this->routes as $route) {
            if ($route['method'] === strtoupper($method) && $route['path'] === $path) {
                return $route['handler'];
            }
        }
        return null;
    }

    // Retorna todas as rotas registradas
    public function getRoutes(): array {
        return $this->routes;
    }
    public function dispatch($method, $path) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                call_user_func($route['handler']);
                return;
            }
        }
        http_response_code(404);
        echo "404 Not Found";
    }
    public function __construct() {
        // Carrega todas as rotas do sistema
        new DelimeterRoutes($this);
        new UsuarioRoutes($this);
        new PacienteRoutes($this);
        new NutricionistaRoutes($this);
        new MedicoRoutes($this);
    }
}
?>