<?php
require_once('header.php');
$db = new Database();
$con = $db->conectar();

$id_mundo = intval($_GET['id_mundo']);
$salas = $con->query("SELECT id_sala, nom_sala, jugadores_actuales, max_jugadores FROM salas WHERE id_mundo = $id_mundo AND id_estado_sala = 1")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salas Disponibles</title>
    <style>
        .sala-card {
            cursor: pointer;
            border: 2px solid transparent;
        }
        .sala-card:hover {
            border-color: #00eaff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Salas Disponibles</h1>
        <div class="row">
            <?php foreach ($salas as $sala): ?>
                <div class="col-md-4 mb-3">
                    <div class="card sala-card" onclick="location.href='entrar_sala.php?id_sala=<?php echo $sala['id_sala']; ?>'">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($sala['nom_sala']); ?></h5>
                            <p>Jugadores: <?php echo $sala['jugadores_actuales']; ?>/<?php echo $sala['max_jugadores']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
