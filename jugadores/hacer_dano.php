<?php
session_start();
require_once('../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);
$id_jugador = intval($_POST['id_jugador']);
$dano = intval($_POST['dano']);

try {
    $con->beginTransaction();
    $jugador = $con->query("SELECT vida FROM jugadores_salas WHERE id_jugador = $id_jugador AND id_sala = $id_sala")->fetch(PDO::FETCH_ASSOC);
    $nuevaVida = $jugador['vida'] - $dano;
    $con->prepare("UPDATE jugadores_salas SET vida = ? WHERE id_jugador = ? AND id_sala = ?")->execute([$nuevaVida, $id_jugador, $id_sala]);
    $con->commit();
    echo $nuevaVida;
} catch (Exception $e) {
    $con->rollBack();
    echo 'Error en el servidor: ' . $e->getMessage();
}
?>
