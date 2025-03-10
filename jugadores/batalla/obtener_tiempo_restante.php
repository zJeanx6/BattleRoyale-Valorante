<?php
require_once('../../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);
$duracion_segundos = $con->query("SELECT duracion_segundos FROM salas WHERE id_sala = $id_sala")->fetch(PDO::FETCH_ASSOC)['duracion_segundos'];

if ($duracion_segundos > 0) {
    $duracion_segundos--;
    $con->prepare("UPDATE salas SET duracion_segundos = ? WHERE id_sala = ?")->execute([$duracion_segundos, $id_sala]);
}

echo $duracion_segundos;    
?>
