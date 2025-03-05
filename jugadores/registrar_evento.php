<?php
session_start();
require_once('../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$tipo = $_POST['tipo'];
$id_jugador = intval($_POST['id_jugador']);
$id_sala = intval($_POST['id_sala']);
$puntos = ($tipo == 'muerte') ? 100 : 0;

try {
    $con->prepare("INSERT INTO partidas_eventos (id_jugador, id_jugador_sala, id_tipo_evento, puntos) VALUES (?, ?, ?, ?)")
        ->execute([$id_jugador, $id_sala, ($tipo == 'muerte') ? 2 : 1, $puntos]);
    echo 'success';
} catch (Exception $e) {
    echo 'Error en el servidor: ' . $e->getMessage();
}
?>
