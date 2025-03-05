<?php
session_start();
require_once('../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_mundo = intval($_POST['id_mundo']);
$id_usuario = $_SESSION['doc'];

// Verificar que el id_mundo exista en la tabla mundos
$mundo = $con->query("SELECT id_mundo FROM mundos WHERE id_mundo = $id_mundo")->fetch(PDO::FETCH_ASSOC);
if (!$mundo) {
    echo 'Error: El mundo no existe.';
    exit;
}

// Obtener el nivel del jugador
$id_nivel = $con->query("SELECT id_nivel FROM usuarios_niveles WHERE id_usuario = $id_usuario")->fetch(PDO::FETCH_ASSOC)['id_nivel'];

$max_jugadores = 5; // Máximo de jugadores predeterminado
$duracion_segundos = 300; // Duración predeterminada de la sala (5 minutos)

try {
    $con->beginTransaction();
    $con->prepare("INSERT INTO salas (nom_sala, jugadores_actuales, id_mundo, id_nivel, max_jugadores, id_estado_sala, duracion_segundos) VALUES ('Sala Automática', 0, ?, ?, ?, 4, ?)")
        ->execute([$id_mundo, $id_nivel, $max_jugadores, $duracion_segundos]);
    $con->commit();
    echo 'success';
} catch (Exception $e) {
    $con->rollBack();
    echo 'Error en el servidor: ' . $e->getMessage();
}
?>
