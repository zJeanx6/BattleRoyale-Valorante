<?php
session_start();
require_once('../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);

// Obtener los jugadores de la sala
$jugadores = $con->query("SELECT id_jugador FROM jugadores_salas WHERE id_sala = $id_sala")->fetchAll(PDO::FETCH_ASSOC);

try {
    $con->beginTransaction();

    $max_puntos = 0;
    $ganador = null;

    foreach ($jugadores as $jugador) {
        $id_jugador = $jugador['id_jugador'];

        // Obtener estadísticas actuales del jugador
        $estadisticas = $con->query("SELECT * FROM estadisticas_juego WHERE id_jugador = $id_jugador")->fetch(PDO::FETCH_ASSOC);

        // Calcular puntos obtenidos en la partida
        $puntos_partida = $con->query("SELECT SUM(puntos) AS puntos FROM partidas_eventos WHERE id_jugador = $id_jugador AND id_tipo_evento = 1")->fetch(PDO::FETCH_ASSOC)['puntos'];
        $puntos_partida = $puntos_partida ? $puntos_partida : 0;

        // Calcular muertes totales en la partida
        $muertes_partida = $con->query("SELECT COUNT(*) AS muertes FROM partidas_eventos WHERE id_jugador = $id_jugador AND id_tipo_evento = 2")->fetch(PDO::FETCH_ASSOC)['muertes'];
        $muertes_partida = $muertes_partida ? $muertes_partida : 0;

        if ($estadisticas) {
            // Actualizar estadísticas
            $con->prepare("UPDATE estadisticas_juego SET juegos_jugados = juegos_jugados + 1, puntos_totales = puntos_totales + ?, muertes_totales = muertes_totales + ?, dano_total = dano_total + ?, ultima_partida = CURRENT_TIMESTAMP WHERE id_jugador = ?")
                ->execute([$puntos_partida, $muertes_partida, $puntos_partida, $id_jugador]);
        } else {
            // Insertar nuevas estadísticas
            $con->prepare("INSERT INTO estadisticas_juego (id_jugador, juegos_jugados, puntos_totales, muertes_totales, dano_total, ultima_partida) VALUES (?, 1, ?, ?, ?, CURRENT_TIMESTAMP)")
                ->execute([$id_jugador, $puntos_partida, $muertes_partida, $puntos_partida]);
        }

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
