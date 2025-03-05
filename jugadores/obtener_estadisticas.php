<?php
session_start();
require_once('../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);

// Obtener las estadísticas de los jugadores de la sala
$estadisticas = $con->query("
    SELECT usuarios.nom_usu, SUM(partidas_eventos.puntos) AS puntos
    FROM partidas_eventos
    INNER JOIN jugadores_salas ON partidas_eventos.id_jugador_sala = jugadores_salas.id
    INNER JOIN usuarios ON jugadores_salas.id_jugador = usuarios.doc
    WHERE jugadores_salas.id_sala = $id_sala AND partidas_eventos.id_tipo_evento = 1
    GROUP BY usuarios.nom_usu
    ORDER BY puntos DESC
")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($estadisticas);
?>
