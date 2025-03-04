<?php
// ROOT_PATH: Ruta al directorio raíz del servidor
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/battle_royale');

// BASE_URL: URL base para los recursos públicos
define('BASE_URL', 'http://localhost/battle_royale');

// Si tienes otros archivos que necesiten configuraciones adicionales, puedes añadirlas aquí
// Por ejemplo, configuraciones de base de datos:
define('DB_CONFIG', ROOT_PATH . '/config/db_config.php');
?>  