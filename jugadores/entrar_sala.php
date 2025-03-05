<?php
session_start();
require_once('../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_usuario = $_SESSION['doc'];
$id_sala = intval($_GET['id_sala']);

try {
    // Verificar si la sala tiene espacio disponible
    $sala = $con->query("SELECT jugadores_actuales, max_jugadores FROM salas WHERE id_sala = $id_sala")->fetch(PDO::FETCH_ASSOC);
    if ($sala['jugadores_actuales'] >= $sala['max_jugadores']) {
        echo '<script>alert("La sala está llena."); window.location.href = "salas.php?id_mundo=' . $_GET['id_mundo'] . '";</script>';
        exit;
    }

    // Verificar si el jugador ya está en la sala
    $jugador_en_sala = $con->query("SELECT COUNT(*) FROM jugadores_salas WHERE id_jugador = $id_usuario AND id_sala = $id_sala")->fetchColumn();
    if ($jugador_en_sala > 0) {
        echo '<script>alert("Ya estás en esta sala."); window.location.href = "salas.php?id_mundo=' . $_GET['id_mundo'] . '";</script>';
        exit;
    }

    // Agregar el jugador a la sala
    $con->beginTransaction();
    $con->prepare("INSERT INTO jugadores_salas (id_jugador, id_sala, id_estado_sala) VALUES (?, ?, 1)")->execute([$id_usuario, $id_sala]);
    $con->prepare("UPDATE salas SET jugadores_actuales = jugadores_actuales + 1 WHERE id_sala = ?")->execute([$id_sala]);
    $con->commit();

    echo '<script>alert("Has entrado a la sala."); window.location.href = "sala.php?id_sala=' . $id_sala . '";</script>';
} catch (Exception $e) {
    $con->rollBack();
    echo '<script>alert("Error en el servidor: ' . $e->getMessage() . '");</script>';
}
?>
