<?php
require_once('../header.php');

$id_sala = intval($_POST['id_sala']);
$id_usuario = intval($_POST['id_usuario']);

try {
    $con->beginTransaction();
    $con->prepare("DELETE FROM jugadores_salas WHERE id_jugador = ? AND id_sala = ?")->execute([$id_usuario, $id_sala]);
    $con->prepare("UPDATE salas SET jugadores_actuales = jugadores_actuales - 1 WHERE id_sala = ?")->execute([$id_sala]);
    $con->commit();
    echo 'success';
} catch (Exception $e) {
    echo 'Error en el servidor: ' . $e->getMessage();
}
?>
