<?php

namespace Htdocs\Src\Routes;

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
        // Se não encontrar a rota, retorna 404
        http_response_code(404);
        echo "404 Not Found";
    }
}
?>