<?php
namespace Htdocs\Src\Controllers;

use Htdocs\Src\Services\MedicoService;

class MedicoController {
    private $service;

    public function __construct(MedicoService $service) {
        $this->service = $service;
    }

    public function criar() {
        $data = json_decode(file_get_contents("php://input"));
        if (!$data) $data = (object)$_POST;

        $medico = new \Htdocs\Src\Models\Entity\Medico(
            $data->id_usuario,
            $data->crm_medico
        );
        echo json_encode($this->service->criar($medico));
    }

    public function listar() {
        echo json_encode($this->service->listar());
    }
    
    public function mostrarFormulario(){
        $formPath = dirname(__DIR__, 2) . '/view/medico/form.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Formulário não encontrado em $formPath";
        }
    }
    public function mostrarHome(){
        $formPath = dirname(__DIR__, 2) . '/view/medico/index.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Início não encontrado em $formPath";
        }
    }
}
?>