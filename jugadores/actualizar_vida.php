<?php
session_start();
require_once('../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);
$id_usuario = intval($_POST['id_usuario']);

$jugador = $con->query("SELECT vida FROM jugadores_salas WHERE id_jugador = $id_usuario AND id_sala = $id_sala")->fetch(PDO::FETCH_ASSOC);
echo $jugador['vida'];
?>
