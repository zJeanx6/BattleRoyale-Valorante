<?php
session_start();
require_once('../../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);

// Obtener las estadísticas finales de los jugadores de la sala
$estadisticas = $con->query("SELECT 
    u.nom_usu, 
    u.doc AS id_jugador,
    COALESCE(SUM(CASE WHEN pe.id_tipo_evento IN (1, 2) THEN pe.puntos ELSE 0 END), 0) AS puntos,
    COALESCE(COUNT(CASE WHEN pe.id_tipo_evento = 2 THEN 1 END), 0) AS muertes
FROM 
    partidas_eventos pe
INNER JOIN 
    jugadores_salas js ON pe.id_jugador = js.id_jugador 
INNER JOIN 
    usuarios u ON js.id_jugador = u.doc
WHERE 
    js.id_sala = $id_sala
GROUP BY 
    u.nom_usu, u.doc    
ORDER BY 
    puntos DESC;

")->fetchAll(PDO::FETCH_ASSOC);

try {
    $con->beginTransaction();

    $max_puntos = 0;
    $ganador = null;

    foreach ($estadisticas as $estadistica) {
        $id_jugador = $estadistica['id_jugador'];
        $puntos_partida = $estadistica['puntos'];

        // Determinar el ganador
        if ($puntos_partida > $max_puntos) {
            $max_puntos = $puntos_partida;
            $ganador = $id_jugador;
        }
    }

    // Establecer el ganador de la sala y cambiar el estado de la sala a finalizada
    $con->prepare("UPDATE salas SET id_estado_sala = 6, id_ganador = ? WHERE id_sala = ?")->execute([$ganador, $id_sala]);

    $con->commit();
    echo 'success';
} catch (Exception $e) {
    $con->rollBack();
    echo 'Error en el servidor: ' . $e->getMessage();
}
?>
