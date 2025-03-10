<?php
session_start();

// Cargar archivos de configuración
require_once(__DIR__ . '/../config.php');
require_once(ROOT_PATH . '/config/db_config.php');
require_once(ROOT_PATH . '/include/consultas.php');
include(ROOT_PATH . '/include/validarSession.php');

// Conectar a la base de datos
$conex = new Database;  
$con = $conex->conectar();

// Obtener el ID del usuario autenticado
$id_usuario = $_SESSION['doc']; 

//  Si hay algo en la variable $id_usuario, Obtener el rol del usuario para el header.php
if ($id_usuario) {
    $sql = $con->prepare(ObtenerRolPorId());
    $sql->execute([$id_usuario]);
    $fila = $sql->fetch(PDO::FETCH_ASSOC);
}

// Obtener el usuario, nivel y avatar del usuario a traves de su ID para el index.php
$usuario = $con->query(obtenerUsuarioPorId($id_usuario))->fetch(PDO::FETCH_ASSOC);
$nivel = $con->query(obtenerNivelPorUsuario($id_usuario))->fetch(PDO::FETCH_ASSOC);
$avatar = $con->query(obtenerAvatarPorUsuario($id_usuario))->fetch(PDO::FETCH_ASSOC);

// Obtener el nivel del usuario y la información del nivel
$nivel_usuario = $con->query("SELECT * FROM usuarios_niveles WHERE id_usuario = $id_usuario")->fetch(PDO::FETCH_ASSOC);
$nivel = $con->query("SELECT * FROM niveles WHERE id_nivel = " . $nivel_usuario['id_nivel'])->fetch(PDO::FETCH_ASSOC);

// Verificar y actualizar el nivel del usuario si tiene más de 500 puntos
$puntos_totales = $con->query("SELECT SUM(puntos) AS total_puntos FROM partidas_eventos WHERE id_jugador = $id_usuario")->fetch(PDO::FETCH_ASSOC)['total_puntos'];
if ($puntos_totales > 500 && $nivel_usuario['id_nivel'] < 2) {
    $con->prepare("UPDATE usuarios_niveles SET id_nivel = 2, fecha = NOW() WHERE id_usuario = ?")->execute([$id_usuario]);
    // Actualizar el nivel en la variable $nivel
    $nivel = $con->query(obtenerNivelPorUsuario($id_usuario))->fetch(PDO::FETCH_ASSOC);
}

// Actualizar la última sesión del usuario
$con->prepare("UPDATE usuarios SET ultima_sesion = CURRENT_TIMESTAMP WHERE doc = ?")->execute([$id_usuario]);

// Establecer el título de la página
$page_title = $page_title ?? "Sin Título";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
