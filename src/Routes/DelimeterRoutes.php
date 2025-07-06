<?php
namespace Htdocs\Src\Routes;

use Htdocs\Src\Controllers\DelimeterController;

class DelimeterRoutes {
    public function __construct($route) {
        $delimeterController = new DelimeterController();
        $route->add('GET', '/', [$delimeterController, 'mostrarHome']);
        $route->add('GET', '/delimeter', [$delimeterController, 'mostrarHome']);
        $route->add('GET', '/delimeter/calculo', [$delimeterController, 'mostrarCalculo']);
        $route->add('GET', '/delimeter/sobre', [$delimeterController, 'mostrarSobre']);
    }
}
?>