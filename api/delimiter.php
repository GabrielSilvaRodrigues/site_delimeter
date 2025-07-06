<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['text']) || !isset($input['delimiter'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados inválidos']);
    exit;
}

$text = trim($input['text']);
$delimiter = $input['delimiter'];

if (empty($text)) {
    http_response_code(400);
    echo json_encode(['error' => 'Texto não pode estar vazio']);
    exit;
}

// Processa o texto baseado no delimitador
try {
    // Remove quebras de linha e espaços extras
    $text = preg_replace('/\s+/', ' ', $text);
    
    // Divide o texto em palavras ou frases
    $items = preg_split('/[,;\|\t\n\r]+/', $text);
    
    // Remove itens vazios e aplica trim
    $items = array_filter(array_map('trim', $items));
    
    // Junta com o delimitador escolhido
    $result = implode($delimiter, $items);
    
    echo json_encode(['result' => $result]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno do servidor']);
}
?>