<?php
namespace Htdocs\Src\Controllers;

class DelimeterController {
    public function mostrarHeader(){
        $formPath = dirname(__DIR__, 2) . '/view/includes/Header.jsx';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Header não encontrado em $formPath";
        }
    }
    public function mostrarFooter(){
        $formPath = dirname(__DIR__, 2) . '/view/includes/Footer.jsx';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Footer não encontrado em $formPath";
        }
    }
    public function mostrarCalculo(){
        $formPath = dirname(__DIR__, 2) . '/view/delimeter/CalculoForm.jsx';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Cálculo não encontrado em $formPath";
        }
    }
    public function mostrarHome(){
        $formPath = dirname(__DIR__, 2) . '/view/delimeter/IndexDelimeter.jsx';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Início não encontrado em $formPath";
        }
    }
    public function mostrarSobre(){
        $formPath = dirname(__DIR__, 2) . '/view/delimeter/SobreDelimeter.jsx';
        if (file_exists($formPath)) {
            include_once $formPath;
        } else {
            echo "Erro: Sobre não encontrado em $formPath";
        }
    }
}
?>