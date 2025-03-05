<?php
// Evitar error "A session is already active"
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar archivos de configuración
require_once(__DIR__ . '/../config.php');
require_once(ROOT_PATH . '/config/db_config.php');
require_once(ROOT_PATH . '/include/consultas.php');
include(ROOT_PATH . '/include/validarSession.php');

// Conectar a la base de datos
$conex = new Database;  
$con = $conex->conectar();

// Verificar si el usuario está autenticado
$id = $_SESSION['doc'] ?? null; // Usa null si no está definido
$fila = null;

if ($id) {
    $sql = $con->prepare("
        SELECT usuarios.doc AS usuario_id, 
               usuarios.nom_usu AS nom_usu, 
               roles.id_rol AS rol_id, 
               roles.nom_rol AS nom_rol, 
               usuarios.id_estado AS id_estado 
        FROM usuarios 
        INNER JOIN roles ON usuarios.id_rol = roles.id_rol 
        WHERE usuarios.doc = ? 
    ");
    $sql->execute([$id]);
    $fila = $sql->fetch(PDO::FETCH_ASSOC);
}

// Definir el título de la página si no está definido
$page_title = $page_title ?? "Sin Título";
?>
