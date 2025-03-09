<?php
require_once('../header.php');

$id_usuario = intval($_POST['id_usuario']);
$id_sala = intval($_POST['id_sala']);
$listo = intval($_POST['listo']);

try {
    $con->prepare("UPDATE jugadores_salas SET listo = ? WHERE id_jugador = ? AND id_sala = ?")->execute([$listo, $id_usuario, $id_sala]);
} catch (Exception $e) {
    echo 'Error en el servidor: ' . $e->getMessage();
}
?>
