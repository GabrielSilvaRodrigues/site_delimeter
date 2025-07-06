<?php
namespace Htdocs\Src\Routes;

use Htdocs\Src\Controllers\DelimeterController;

class DelimeterRoutes {
    public function __construct($route) {
        $delimeterController = new DelimeterController();
        
        // API routes
        $route->add('GET', '/api/', [$delimeterController, 'getHome']);
        $route->add('GET', '/api/home', [$delimeterController, 'getHome']);
        $route->add('GET', '/api/sobre', [$delimeterController, 'getSobre']);
        $route->add('POST', '/api/calculo', [$delimeterController, 'calcularNutricional']);
    }
}