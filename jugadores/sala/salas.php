<?php
require_once('../header.php');
$id_mundo = intval($_GET['id_mundo']);

// Obtener el nivel del jugador
$id_nivel = $con->query("SELECT id_nivel FROM usuarios_niveles WHERE id_usuario = $id_usuario")->fetch(PDO::FETCH_ASSOC)['id_nivel'];

$salas = $con->query("SELECT salas.id_sala, salas.nom_sala, salas.jugadores_actuales, salas.max_jugadores, estados.nom_estado FROM salas INNER JOIN estados ON salas.id_estado_sala = estados.id_estado WHERE salas.id_mundo = $id_mundo AND salas.id_nivel = $id_nivel AND (salas.id_estado_sala = 4 OR salas.id_estado_sala = 5)")->fetchAll(PDO::FETCH_ASSOC);
?>
<head>
    <title>Salas Disponibles</title>
    <style>
        :root {
            --primary-color: #ff4655;
            --secondary-color: #00eaff;
            --dark-bg: #1a1a2e;
            --card-bg: rgba(255, 255, 255, 0.1);
            --text-color: #ffffff;
        }

        body {
            font-family: 'Orbitron', 'Rajdhani', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-size: cover;
            color: var(--text-color);
            min-height: 100vh;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(26, 26, 46, 0.9) 0%, rgba(22, 33, 62, 0.9) 100%);
            z-index: -1;
        }

        .container {
            padding-top: 50px;
            padding-bottom: 50px;
        }

        h1 {
            color: var(--secondary-color);
            text-shadow: 0 0 10px rgba(0, 234, 255, 0.5);
            font-weight: 700;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .sala-card {
            background-color: var(--card-bg);
            border: 2px solid transparent;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .sala-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 234, 255, 0.3);
            border-color: var(--secondary-color);
        }

        .sala-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent 50%, rgba(0, 0, 0, 0.7) 100%);
            z-index: 1;
        }

        .card-body {
            position: relative;
            z-index: 2;
        }

        .card-title {
            color: var(--text-color);
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .sala-en-juego {
            cursor: not-allowed;
            opacity: 0.6;
        }

        @media (max-width: 768px) {
            .container {
                padding-top: 30px;
                padding-bottom: 30px;
            }

            h1 {
                font-size: 2rem;
            }
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
                                <h6 class="card-title">Jugadores: <?php echo $sala['jugadores_actuales']; ?>/<?php echo $sala['max_jugadores']; ?></h6>
                                <h6 class="card-title">Estado: <?php echo htmlspecialchars($sala['nom_estado']); ?></h6>
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
        setInterval(actualizarSalas, 1000);
    </script>
</body>
</html>