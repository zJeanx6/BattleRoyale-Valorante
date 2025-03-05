<?php
session_start();
require_once('../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);
$id_jugador = intval($_POST['id_jugador']);
$dano = intval($_POST['dano']);
$id_atacante = isset($_POST['atacante']) ? intval($_POST['atacante']) : null;

try {
    $con->beginTransaction();
    $jugador = $con->query("SELECT vida FROM jugadores_salas WHERE id_jugador = $id_jugador AND id_sala = $id_sala")->fetch(PDO::FETCH_ASSOC);
    $nuevaVida = max(0, $jugador['vida'] - $dano);
    $con->prepare("UPDATE jugadores_salas SET vida = ? WHERE id_jugador = ? AND id_sala = ?")->execute([$nuevaVida, $id_jugador, $id_sala]);

    // Obtener el id_jugador_sala
    $id_jugador_sala = $con->query("SELECT id FROM jugadores_salas WHERE id_jugador = $id_jugador AND id_sala = $id_sala")->fetch(PDO::FETCH_ASSOC)['id'];

    if ($id_jugador_sala) {
        // Registrar el evento de disparo
        $con->prepare("INSERT INTO partidas_eventos (id_jugador, id_jugador_sala, id_tipo_evento, puntos) VALUES (?, ?, 1, ?)")->execute([$id_atacante, $id_jugador_sala, $dano]);

        if ($nuevaVida <= 0) {
            // Registrar el evento de muerte (kill)
            $con->prepare("INSERT INTO partidas_eventos (id_jugador, id_jugador_sala, id_tipo_evento, puntos) VALUES (?, ?, 2, 40)")->execute([$id_atacante, $id_jugador_sala]);

            // Registrar el evento de muerte (me mataron)
            $con->prepare("INSERT INTO partidas_eventos (id_jugador, id_jugador_sala, id_tipo_evento, puntos) VALUES (?, ?, 3, 0)")->execute([$id_jugador, $id_jugador_sala]);
        }
    } else {
        throw new Exception("El id_jugador_sala no existe.");
    }

    $con->commit();
    echo $nuevaVida;
} catch (Exception $e) {
    $con->rollBack();
    echo 'Error en el servidor: ' . $e->getMessage();
}
?>
