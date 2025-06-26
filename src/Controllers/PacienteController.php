<?php
namespace Htdocs\Src\Controllers;

use Htdocs\Src\Services\PacienteService;

class PacienteController {
    private $service;

    public function __construct(PacienteService $service) {
        $this->service = $service;
    }

    public function criar() {
        $data = json_decode(file_get_contents("php://input"));
        if (!$data) {
            $data = (object)$_POST;
        }

        $paciente = new \Htdocs\Src\Models\Entity\Paciente(
            $data->id_usuario,
            $data->cpf,
            $data->nis
        );

        echo json_encode(
            $this->service->criar($paciente)
        );
    }

    public function mostrarFormulario(){
        $formPath = dirname(__DIR__, 2) . '/view/paciente/form.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Formulário não encontrado em $formPath";
        }
    }
    public function mostrarHome(){
        $formPath = dirname(__DIR__, 2) . '/view/paciente/index.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Início não encontrado em $formPath";
        }
    }
}
?>