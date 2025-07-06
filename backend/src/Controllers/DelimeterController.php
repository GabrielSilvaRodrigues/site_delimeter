<?php
namespace Htdocs\Src\Controllers;

class DelimeterController {
    
    public function getHome() {
        try {
            $data = [
                'title' => 'Bem-vindo ao Deliméter',
                'description' => 'Seu portal para uma vida mais saudável, inteligente e conectada!',
                'features' => [
                    'Cadastro e Login de Usuários',
                    'Painel do Usuário com informações e histórico',
                    'Cálculo Nutricional (IMC, GET, macros, etc)',
                    'Acessibilidade completa',
                    'Responsivo para todos os dispositivos',
                    'Segurança com criptografia'
                ]
            ];
            
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    public function getSobre() {
        try {
            $data = [
                'title' => 'Sobre o Deliméter',
                'description' => 'O Deliméter é um sistema web para gerenciamento de usuários e cálculo nutricional, pensado para ser acessível, bonito e fácil de usar.',
                'mission' => 'Ideal para quem quer cuidar da saúde, acompanhar dados e ter controle sobre sua experiência.',
                'technologies' => [
                    'React + TypeScript (Frontend)',
                    'PHP + Composer (Backend)',
                    'MySQL (Banco de dados)',
                    'API RESTful',
                    'Responsivo e acessível'
                ]
            ];
            
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    public function calcularNutricional() {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                throw new Exception('Dados inválidos');
            }
            
            $peso = $input['peso'] ?? 0;
            $altura = $input['altura'] ?? 0;
            $idade = $input['idade'] ?? 0;
            $sexo = $input['sexo'] ?? '';
            $atividade = $input['atividade'] ?? 1.2;
            
            if ($peso <= 0 || $altura <= 0 || $idade <= 0) {
                throw new Exception('Peso, altura e idade devem ser maiores que zero');
            }
            
            // Cálculo do IMC
            $imc = $peso / (($altura / 100) * ($altura / 100));
            
            // Classificação do IMC
            $classificacaoIMC = $this->classificarIMC($imc);
            
            // Cálculo do GET (Gasto Energético Total)
            $tmb = $this->calcularTMB($peso, $altura, $idade, $sexo);
            $get = $tmb * $atividade;
            
            // Cálculo dos macronutrientes
            $macros = $this->calcularMacronutrientes($get);
            
            $resultado = [
                'imc' => round($imc, 2),
                'classificacao_imc' => $classificacaoIMC,
                'tmb' => round($tmb, 0),
                'get' => round($get, 0),
                'macronutrientes' => $macros
            ];
            
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $resultado]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    // Métodos privados auxiliares
    private function classificarIMC($imc) {
        if ($imc < 18.5) return 'Abaixo do peso';
        if ($imc < 25) return 'Peso normal';
        if ($imc < 30) return 'Sobrepeso';
        if ($imc < 35) return 'Obesidade grau I';
        if ($imc < 40) return 'Obesidade grau II';
        return 'Obesidade grau III';
    }
    
    private function calcularTMB($peso, $altura, $idade, $sexo) {
        if (strtolower($sexo) === 'masculino' || strtolower($sexo) === 'm') {
            return 88.362 + (13.397 * $peso) + (4.799 * $altura) - (5.677 * $idade);
        } else {
            return 447.593 + (9.247 * $peso) + (3.098 * $altura) - (4.330 * $idade);
        }
    }
    
    private function calcularMacronutrientes($get) {
        $proteinas = ($get * 0.15) / 4; // 15% das calorias, 4 kcal/g
        $lipidios = ($get * 0.25) / 9;  // 25% das calorias, 9 kcal/g
        $carboidratos = ($get * 0.60) / 4; // 60% das calorias, 4 kcal/g
        
        return [
            'proteinas' => round($proteinas, 1),
            'lipidios' => round($lipidios, 1),
            'carboidratos' => round($carboidratos, 1)
        ];
    }
    
    // Métodos legados para compatibilidade (caso necessário)
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