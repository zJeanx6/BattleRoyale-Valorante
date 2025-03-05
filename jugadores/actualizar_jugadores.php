<?php
session_start();
require_once('../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);

$jugadores = $con->query("SELECT usuarios.nom_usu, avatar.img AS avatar, jugadores_salas.listo FROM jugadores_salas INNER JOIN usuarios ON jugadores_salas.id_jugador = usuarios.doc INNER JOIN avatar ON usuarios.id_avatar = avatar.id_avatar WHERE jugadores_salas.id_sala = $id_sala")->fetchAll(PDO::FETCH_ASSOC);

for ($i = 0; $i < 5; $i++) {
    echo '<div class="jugador-cajon ' . (isset($jugadores[$i]) ? ($jugadores[$i]['listo'] ? 'listo' : 'no-listo') : 'esperando') . '" id="jugador-' . $i . '">';
    if (isset($jugadores[$i])) {
        echo '<img src="' . htmlspecialchars($jugadores[$i]['avatar']) . '" alt="Avatar" class="jugador-avatar">';
        echo '<div class="jugador-nombre">' . htmlspecialchars($jugadores[$i]['nom_usu']) . '</div>';
    } else {
        echo '<div class="jugador-nombre">Esperando...</div>';
    }
    echo '</div>';
}
?>
