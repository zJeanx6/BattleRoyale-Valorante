<?php
session_start();
require_once('../../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);

// Obtener las estadísticas finales de los jugadores de la sala
$estadisticas = $con->query("SELECT id_jugador, nom_usu,
    SUM(CASE WHEN id_tipo_evento IN (1, 2) THEN puntos ELSE 0 END) AS puntos,
    COUNT(CASE WHEN id_tipo_evento = 2 THEN 1 END) AS muertes
    FROM partidas_eventos
    INNER JOIN usuarios ON usuarios.doc = partidas_eventos.id_jugador
    WHERE id_sala = $id_sala
    GROUP BY id_jugador
    ORDER BY puntos DESC;")->fetchAll(PDO::FETCH_ASSOC);

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
