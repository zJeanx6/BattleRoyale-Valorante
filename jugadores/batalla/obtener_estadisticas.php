<?php
session_start();
require_once('../../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);

$estadisticas = $con->query("SELECT id_jugador, nom_usu,
    SUM(CASE WHEN id_tipo_evento IN (1, 2) THEN puntos ELSE 0 END) AS puntos,
    COUNT(CASE WHEN id_tipo_evento = 2 THEN 1 END) AS muertes
    FROM partidas_eventos
    INNER JOIN usuarios ON usuarios.doc = partidas_eventos.id_jugador
    WHERE id_sala = $id_sala
    GROUP BY id_jugador
    ORDER BY puntos DESC;")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($estadisticas);
?>