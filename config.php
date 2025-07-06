<?php
// Configurações específicas para InfinityFree

// Configurações de erro (ocultar em produção)
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Configurações de sessão
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Mude para 1 se usar HTTPS

// Configurações de timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de banco de dados para InfinityFree
define('DB_HOST', 'sql304.infinityfree.com'); // Substitua pelo seu host
define('DB_NAME', 'if0_37912345_delimeter'); // Substitua pelo seu banco
define('DB_USER', 'if0_37912345'); // Substitua pelo seu usuário
define('DB_PASS', 'sua_senha_aqui'); // Substitua pela sua senha

// Configurações de URL base
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$domain = $_SERVER['HTTP_HOST'];
define('BASE_URL', $protocol . $domain);

// Configurações de upload (limitações do InfinityFree)
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('UPLOAD_PATH', __DIR__ . '/uploads/');

// Chave de segurança para tokens
define('SECRET_KEY', 'sua_chave_secreta_aqui_mude_em_producao');

// Configurações de email (se disponível)
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');

// Cache de arquivos estáticos
define('STATIC_CACHE_TIME', 86400); // 24 horas
?>
