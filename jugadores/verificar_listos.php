<?php
require_once('../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);

$sala = $con->query("SELECT jugadores_actuales FROM salas WHERE id_sala = $id_sala")->fetch(PDO::FETCH_ASSOC);
$jugadores_listos = $con->query("SELECT COUNT(*) as total FROM jugadores_salas WHERE id_sala = $id_sala AND listo = 1")->fetch(PDO::FETCH_ASSOC)['total'];

if ($jugadores_listos > 1 && $jugadores_listos == $sala['jugadores_actuales']) {
    echo 'todos_listos';
} else {
    echo 'no_listos';
}
?>
