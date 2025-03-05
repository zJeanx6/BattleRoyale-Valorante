<?php
session_start();
require_once('../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);

try {
    $con->beginTransaction();
    $con->prepare("UPDATE jugadores_salas SET id_estado_sala = 2 WHERE id_sala = ?")->execute([$id_sala]);
    $con->prepare("UPDATE salas SET id_estado_sala = 2 WHERE id_sala = ?")->execute([$id_sala]);
    $con->commit();
} catch (Exception $e) {
    $con->rollBack();
    echo 'Error en el servidor: ' . $e->getMessage();
}
?>
