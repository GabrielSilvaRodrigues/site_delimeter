<?php

namespace Htdocs\Src\Routes;
use Htdocs\Src\Routes\DelimeterRoutes;
use Htdocs\Src\Routes\MedicoRoutes;
use Htdocs\Src\Routes\NutricionistaRoutes;
use Htdocs\Src\Routes\PacienteRoutes;
use Htdocs\Src\Routes\UsuarioRoutes;
use Htdocs\Src\Routes\DadosAntropometricosRoutes;
use Htdocs\Src\Routes\DietaRoutes;
use Htdocs\Src\Routes\AlimentoRoutes;
use Htdocs\Src\Routes\DiarioDeAlimentosRoutes;

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
        error_log("Router: Tentando despachar {$method} {$path}");
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                error_log("Router: Rota encontrada para {$method} {$path}");
                call_user_func($route['handler']);
                return;
            }
        }
        
        error_log("Router: Rota não encontrada para {$method} {$path}");
        error_log("Router: Rotas disponíveis:");
        foreach ($this->routes as $route) {
            error_log("  - {$route['method']} {$route['path']}");
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
        new DadosAntropometricosRoutes($this);
        new DietaRoutes($this);
        new AlimentoRoutes($this);
        new DiarioDeAlimentosRoutes($this);
        
        // Rota de debug temporária
        $this->add('GET', '/debug', function() {
            include_once dirname(__DIR__, 2) . '/debug.php';
        });
        
        // Rota de teste da API
        $this->add('GET', '/test-api', function() {
            include_once dirname(__DIR__, 2) . '/test-api.php';
        });
        
        // Rota de teste JavaScript
        $this->add('GET', '/test-api-js', function() {
            include_once dirname(__DIR__, 2) . '/test-api-js.html';
        });
    }
}
?>