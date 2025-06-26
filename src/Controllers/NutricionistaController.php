<?php
namespace Htdocs\Src\Controllers;

use Htdocs\Src\Services\NutricionistaService;

class NutricionistaController {
    private $service;

    public function __construct(NutricionistaService $service) {
        $this->service = $service;
    }

    public function criar() {
        $data = json_decode(file_get_contents("php://input"));
        if (!$data) $data = (object)$_POST;

        $nutricionista = new \Htdocs\Src\Models\Entity\Nutricionista(
            $data->id_usuario,
            $data->crm_nutricionista
        );
        echo json_encode($this->service->criar($nutricionista));
    }

    public function listar() {
        echo json_encode($this->service->listar());
    }
    
    public function mostrarFormulario(){
        $formPath = dirname(__DIR__, 2) . '/view/nutricionista/form.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Formulário não encontrado em $formPath";
        }
    }
    public function mostrarHome(){
        $formPath = dirname(__DIR__, 2) . '/view/nutricionista/index.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Início não encontrado em $formPath";
        }
    }
}
?>