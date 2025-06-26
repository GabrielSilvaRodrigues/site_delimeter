<?php
// Adicionando um cabeçalho para indicar que o conteúdo é HTML
header('Content-Type: text/html; charset=utf-8');

$publicIndexPath = __DIR__ . '/public/index.php';
if (file_exists($publicIndexPath)) {
    include_once $publicIndexPath;
} else {
    echo "Erro: Arquivo public/index.php não encontrado em $publicIndexPath";
    exit(1);
}
?>