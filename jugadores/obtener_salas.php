<?php
require_once('../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_mundo = intval($_GET['id_mundo']);
$salas = $con->query("SELECT salas.id_sala, salas.nom_sala, salas.jugadores_actuales, salas.max_jugadores, estados.nom_estado FROM salas INNER JOIN estados ON salas.id_estado_sala = estados.id_estado WHERE salas.id_mundo = $id_mundo AND (salas.id_estado_sala = 4 OR salas.id_estado_sala = 5)")->fetchAll(PDO::FETCH_ASSOC);

if (empty($salas)) {
    echo '<div class="col-12 text-center">
            <p>No hay salas disponibles.</p>
            <button class="btn btn-primary" onclick="crearSala()">Crear Sala</button>
          </div>';
} else {
    foreach ($salas as $sala) {
        echo '<div class="col-md-4 mb-3">
                <div class="card sala-card ' . ($sala['nom_estado'] == 'en juego' ? 'sala-en-juego' : '') . '" ' . ($sala['nom_estado'] == 'en juego' ? 'style="pointer-events: none;"' : 'onclick="location.href=\'entrar_sala.php?id_sala=' . $sala['id_sala'] . '\'"') . '>
                    <div class="card-body text-center">
                        <h5 class="card-title">' . htmlspecialchars($sala['nom_sala']) . '</h5>
                        <p>Jugadores: ' . $sala['jugadores_actuales'] . '/' . $sala['max_jugadores'] . '</p>
                        <p>Estado: ' . htmlspecialchars($sala['nom_estado']) . '</p>
                    </div>
                </div>
              </div>';
    }
}
?>
