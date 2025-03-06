<?php
require_once('../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);

// Obtener el tiempo restante de la sala
$duracion_segundos = $con->query("SELECT duracion_segundos FROM salas WHERE id_sala = $id_sala")->fetch(PDO::FETCH_ASSOC)['duracion_segundos'];

// Restar un segundo
$duracion_segundos--;

// Actualizar el tiempo restante en la base de datos
$con->prepare("UPDATE salas SET duracion_segundos = ? WHERE id_sala = ?")->execute([$duracion_segundos, $id_sala]);

echo $duracion_segundos;
?>
