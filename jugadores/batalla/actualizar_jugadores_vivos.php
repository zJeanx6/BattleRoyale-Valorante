<?php
require_once('../../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);

$jugadores_vivos = $con->query("SELECT COUNT(*) AS vivos FROM jugadores_salas WHERE id_sala = $id_sala AND vida > 0")->fetch(PDO::FETCH_ASSOC)['vivos'];

if ($jugadores_vivos <= 1) {
    $con->prepare("UPDATE salas SET id_estado_sala = 6 WHERE id_sala = ?")->execute([$id_sala]);
}

echo $jugadores_vivos;
?>
