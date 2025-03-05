<?php
require_once('header.php');
$db = new Database();
$con = $db->conectar();

$id_mundo = intval($_GET['id_mundo']);
$salas = $con->query("SELECT salas.id_sala, salas.nom_sala, salas.jugadores_actuales, salas.max_jugadores, estados.nom_estado FROM salas INNER JOIN estados ON salas.id_estado_sala = estados.id_estado WHERE salas.id_mundo = $id_mundo AND (salas.id_estado_sala = 4 OR salas.id_estado_sala = 5)")->fetchAll(PDO::FETCH_ASSOC);
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
        .sala-en-juego {
            cursor: not-allowed;
            opacity: 0.6;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Salas Disponibles</h1>
        <div class="row" id="salas-container">
            <?php if (empty($salas)): ?>
                <div class="col-12 text-center">
                    <p>No hay salas disponibles.</p>
                    <button class="btn btn-primary" onclick="crearSala()">Crear Sala</button>
                </div>
            <?php else: ?>
                <?php foreach ($salas as $sala): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card sala-card <?php echo $sala['nom_estado'] == 'en juego' ? 'sala-en-juego' : ''; ?>" <?php echo $sala['nom_estado'] == 'en juego' ? 'style="pointer-events: none;"' : 'onclick="location.href=\'entrar_sala.php?id_sala=' . $sala['id_sala'] . '\'"'; ?>>
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($sala['nom_sala']); ?></h5>
                                <p>Jugadores: <?php echo $sala['jugadores_actuales']; ?>/<?php echo $sala['max_jugadores']; ?></p>
                                <p>Estado: <?php echo htmlspecialchars($sala['nom_estado']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (!array_filter($salas, fn($sala) => $sala['nom_estado'] == 'En espera')): ?>
                    <div class="col-12 text-center">
                        <button class="btn btn-primary" onclick="crearSala()">Crear Sala</button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        function crearSala() {
            $.ajax({
                url: 'crear_sala.php',
                type: 'POST',
                data: { id_mundo: <?php echo $id_mundo; ?> },
                success: function(response) {
                    alert('Sala creada exitosamente.');
                    actualizarSalas();
                },
                error: function() {
                    alert('Error al crear la sala.');
                }
            });
        }

        function actualizarSalas() {
            $.ajax({
                url: 'obtener_salas.php',
                type: 'GET',
                data: { id_mundo: <?php echo $id_mundo; ?> },
                success: function(data) {
                    $('#salas-container').html(data);
                }
            });
        }

        setInterval(actualizarSalas, 1000); // Actualizar salas cada 1 segundos
    </script>
</body>
</html>
