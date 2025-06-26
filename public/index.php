<?php 
$autoloadPath = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    include_once $autoloadPath;
} else {
    echo "Erro: Autoload do Composer não encontrado em $autoloadPath";
    exit(1);
}
use Htdocs\Src\Routes\Routes;
use Htdocs\Src\Controllers\DelimeterController;

$route = new Routes();
$delimeterController = new DelimeterController();

try{
    $delimeterController->mostrarHeader();
} catch (Exception $e) {
    echo "Erro ao incluir o header: " . $e->getMessage();
    exit(1);
}

try{
    if (isset($_SESSION['usuario'])) {
        include_once dirname(__DIR__) . '/public/' . $_SESSION['usuario']['tipo'] . '.php';
    } else {
        include_once dirname(__DIR__) . '/public/delimeter.php';
    }
} catch (Exception $e) {
    echo "Erro ao incluir o usuario: " . $e->getMessage();
    exit(1);
}

// Descobre método e path da requisição
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Faz o dispatch da rota
$route->dispatch($method, $path);

try{
    $delimeterController->mostrarFooter();
} catch (Exception $e) {
    echo "Erro ao incluir o footer: " . $e->getMessage();
    exit(1);
}
?>