<?php
// ROOT_PATH: Ruta al directorio raíz del servidor
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/battle_royale');

// BASE_URL: URL base para los recursos públicos
define('BASE_URL', 'http://localhost/battle_royale');

define('DB_CONFIG', ROOT_PATH . '/config/db_config.php');

// Incluir configuración de la base de datos
require_once 'config/db_config.php';

// Configuración de PHPMailer
if (!defined('PHPMAILER_PATH')) {
    define('PHPMAILER_PATH', 'phpmailer/');
}
if (!defined('PHPMAILER_HOST')) {
    define('PHPMAILER_HOST', 'smtp.gmail.com'); // Cambia esto por tu servidor SMTP
}
if (!defined('PHPMAILER_SMTP_AUTH')) {
    define('PHPMAILER_SMTP_AUTH', true);
}
if (!defined('PHPMAILER_USERNAME')) {
    define('PHPMAILER_USERNAME', 'valoranteappp@gmail.com'); 
}
if (!defined('PHPMAILER_PASSWORD')) {
    define('PHPMAILER_PASSWORD', 'jorr hbun inil lcpz');
}
if (!defined('PHPMAILER_SMTP_SECURE')) {
    define('PHPMAILER_SMTP_SECURE', 'tls'); // o 'ssl'
}
if (!defined('PHPMAILER_PORT')) {
    define('PHPMAILER_PORT', 587); // o 465 para 'ssl'
}
?>