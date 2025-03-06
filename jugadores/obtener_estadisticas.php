<?php
session_start();
require_once('../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);

$estadisticas = $con->query("
SELECT 
        u.nom_usu, 
        COALESCE(SUM(CASE WHEN pe.id_tipo_evento IN (1, 2) THEN pe.puntos ELSE 0 END), 0) AS puntos,
        COALESCE(SUM(CASE WHEN pe.id_tipo_evento = 2 THEN 1 ELSE 0 END), 0) AS muertes
    FROM partidas_eventos pe
    INNER JOIN jugadores_salas js ON pe.id_jugador = js.id_jugador 
    INNER JOIN usuarios u ON js.id_jugador = u.doc
    WHERE js.id_sala = $id_sala
    GROUP BY u.nom_usu
    ORDER BY puntos DESC;
")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($estadisticas);
?>