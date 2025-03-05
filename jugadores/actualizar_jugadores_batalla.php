<?php
session_start();
require_once('../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);

$jugadores = $con->query("SELECT usuarios.doc, usuarios.nom_usu, avatar.img AS avatar, jugadores_salas.vida FROM jugadores_salas INNER JOIN usuarios ON jugadores_salas.id_jugador = usuarios.doc INNER JOIN avatar ON usuarios.id_avatar = avatar.id_avatar WHERE jugadores_salas.id_sala = $id_sala")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($jugadores);
?>
