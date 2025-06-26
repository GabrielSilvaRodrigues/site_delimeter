<?php
namespace Htdocs\Src\Controllers;

class DelimeterController {
    public function mostrarHeader(){
        $formPath = dirname(__DIR__, 2) . '/view/includes/header.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Header não encontrado em $formPath";
        }
    }
    public function mostrarFooter(){
        $formPath = dirname(__DIR__, 2) . '/view/includes/footer.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Footer não encontrado em $formPath";
        }
    }
    public function mostrarCalculo(){
        $formPath = dirname(__DIR__, 2) . '/view/delimeter/calculoForm.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Cálculo não encontrado em $formPath";
        }
    }
    public function mostrarHome(){
        $formPath = dirname(__DIR__, 2) . '/view/delimeter/index.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Início não encontrado em $formPath";
        }
    }
    public function mostrarSobre(){
        $formPath = dirname(__DIR__, 2) . '/view/delimeter/sobre.php';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Sobre não encontrado em $formPath";
        }
    }
}
?>