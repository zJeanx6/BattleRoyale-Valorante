<?php
require_once('../../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_POST['id_sala']);

$query = $con->prepare("
    SELECT 
        CASE p.id_tipo_evento
            WHEN 1 THEN 
                CONCAT(u1.nom_usu, ' ha atacado a ', u2.nom_usu, ' con ', IFNULL(a.nom_arma, 'sus manos'))
            WHEN 2 THEN 
                CONCAT(u1.nom_usu, ' ha matado a ', u2.nom_usu)
            WHEN 3 THEN 
                CONCAT(u1.nom_usu, ' ha sido eliminado')
            WHEN 4 THEN 
                CONCAT(u1.nom_usu, ' ha dado en la cabeza de ', u2.nom_usu, ' con ', IFNULL(a.nom_arma, 'sus manos'))
        END AS descripcion,
        p.timestamp
    FROM partidas_eventos p
    LEFT JOIN usuarios u1 ON u1.doc = p.id_jugador
    LEFT JOIN jugadores_salas js ON js.id = p.id_jugador_sala
    LEFT JOIN usuarios u2 ON u2.doc = js.id_jugador
    LEFT JOIN armas a ON a.id_arma = p.arma_id
    WHERE p.id_sala = ?
    ORDER BY p.timestamp DESC
    LIMIT 10
");
$query->execute([$id_sala]);
$eventos = $query->fetchAll(PDO::FETCH_ASSOC);

// Invertir el orden de los eventos para que los más recientes aparezcan al final
$eventos = array_reverse($eventos);

echo json_encode($eventos);
?>
