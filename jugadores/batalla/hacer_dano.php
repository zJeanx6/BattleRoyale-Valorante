<?php
session_start();
require_once('../../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);
$id_jugador = intval($_POST['id_jugador']);
$dano = intval($_POST['dano']);
$id_atacante = isset($_POST['atacante']) ? intval($_POST['atacante']) : null;
$id_arma = intval($_POST['id_arma']); // Recibir el id del arma

try {
    // Determinar si es un disparo al cuerpo o a la cabeza
    $esDisparoCabeza = rand(1, 100) <= 30; // 30% de probabilidad de disparo a la cabeza
    $danoFinal = $esDisparoCabeza ? $dano * 3 : $dano; // Triplicar el daño si es disparo a la cabeza
    $tipoEvento = $esDisparoCabeza ? 4 : 1; // Tipo de evento: 4 = disparo a la cabeza, 1 = disparo al cuerpo

    // Obtener la vida actual del jugador
    $stmt = $con->prepare("SELECT vida FROM jugadores_salas WHERE id_jugador = ? AND id_sala = ?");
    $stmt->execute([$id_jugador, $id_sala]);
    $jugador = $stmt->fetch(PDO::FETCH_ASSOC);
    $nuevaVida = max(0, $jugador['vida'] - $danoFinal);

    // Actualizar la vida del jugador
    $stmt = $con->prepare("UPDATE jugadores_salas SET vida = ? WHERE id_jugador = ? AND id_sala = ?");
    $stmt->execute([$nuevaVida, $id_jugador, $id_sala]);

    // Registrar el evento de disparo
    $stmt = $con->prepare("INSERT INTO partidas_eventos (id_jugador, id_jugador_sala, id_tipo_evento, puntos, id_sala, arma_id) VALUES (?, (SELECT id FROM jugadores_salas WHERE id_jugador = ? AND id_sala = ?), ?, ?, ?, ?)");
    $stmt->execute([$id_atacante, $id_jugador, $id_sala, $tipoEvento, $danoFinal, $id_sala, $id_arma]);

    if ($nuevaVida <= 0) {
        // Registrar el evento de muerte (kill)
        $stmt = $con->prepare("INSERT INTO partidas_eventos (id_jugador, id_jugador_sala, id_tipo_evento, puntos, id_sala, arma_id) VALUES (?, (SELECT id FROM jugadores_salas WHERE id_jugador = ? AND id_sala = ?), 2, 40, ?, ?)");
        $stmt->execute([$id_atacante, $id_jugador, $id_sala, $id_sala, $id_arma]);

        // Registrar el evento de muerte (me mataron)
        $stmt = $con->prepare("INSERT INTO partidas_eventos (id_jugador, id_jugador_sala, id_tipo_evento, puntos, id_sala, arma_id) VALUES (?, (SELECT id FROM jugadores_salas WHERE id_jugador = ? AND id_sala = ?), 3, 0, ?, NULL)");
        $stmt->execute([$id_jugador, $id_jugador, $id_sala, $id_sala]);
    }

    // Verificar si solo queda un jugador vivo
    $stmt = $con->prepare("SELECT COUNT(*) AS vivos FROM jugadores_salas WHERE id_sala = ? AND vida > 0");
    $stmt->execute([$id_sala]);
    $jugadores_vivos = $stmt->fetch(PDO::FETCH_ASSOC)['vivos'];

    if ($jugadores_vivos <= 1) {
        $stmt = $con->prepare("UPDATE salas SET id_estado_sala = 6 WHERE id_sala = ?");
        $stmt->execute([$id_sala]);
    }

    echo $nuevaVida;
} catch (Exception $e) {
    echo 'Error en el servidor: ' . $e->getMessage();
}
?>
