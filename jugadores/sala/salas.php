<?php
require_once('../header.php');
$id_mundo = intval($_GET['id_mundo']);
$salas = $con->query("SELECT salas.id_sala, salas.nom_sala, salas.jugadores_actuales, salas.max_jugadores, estados.nom_estado FROM salas INNER JOIN estados ON salas.id_estado_sala = estados.id_estado WHERE salas.id_mundo = $id_mundo AND (salas.id_estado_sala = 4 OR salas.id_estado_sala = 5)")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salas Disponibles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #00eaff;
            --dark-bg: #1a1a2e;
            --card-bg: rgba(0, 234, 255, 0.05);
            --text-color: #ffffff;
        }

        body {
            font-family: 'Orbitron', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-color);
            min-height: 100vh;
            position: relative;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Particle effect */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .particle {
            position: absolute;
            background-color: var(--primary-color);
            width: 3px;
            height: 3px;
            border-radius: 50%;
            opacity: 0.3;
            animation: float 15s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) translateX(0);
            }
            100% {
                transform: translateY(-100vh) translateX(20px);
            }
        }

        .container {
            position: relative;
            z-index: 1;
            padding-top: 50px;
            padding-bottom: 50px;
        }

        h1 {
            color: var(--primary-color);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 3px;
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem;
            text-shadow: 0 0 10px rgba(0, 234, 255, 0.5);
        }

        .sala-card {
            background-color: var(--card-bg);
            border: 2px solid transparent;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            backdrop-filter: blur(10px);
            box-shadow: 0 0 20px rgba(0, 234, 255, 0.1);
        }

        .sala-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
            box-shadow: 0 0 30px rgba(0, 234, 255, 0.2);
        }

        .sala-en-juego {
            cursor: not-allowed;
            opacity: 0.6;
        }

        .sala-en-juego:hover {
            transform: none;
            box-shadow: 0 0 20px rgba(0, 234, 255, 0.1);
        }

        .card-body {
            padding: 20px;
            text-align: center;
        }

        .card-title {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .card-body p {
            margin-bottom: 10px;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }

        .estado-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            background: rgba(0, 234, 255, 0.1);
            border: 1px solid var(--primary-color);
        }

        .btn-primary {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 10px 30px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            background-color: var(--primary-color);
            color: var(--dark-bg);
            border-color: var(--primary-color);
            box-shadow: 0 0 20px rgba(0, 234, 255, 0.4);
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to right,
                transparent,
                rgba(255, 255, 255, 0.3),
                transparent
            );
            transform: rotate(45deg);
            transition: all 0.5s ease;
        }

        .btn-primary:hover::after {
            animation: shine 1.5s ease;
        }

        @keyframes shine {
            0% {
                left: -50%;
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% {
                left: 150%;
                opacity: 0;
            }
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Particles -->
    <div class="particles" id="particles"></div>

    <div class="container mt-5">
        <h1 class="mb-4">Salas Disponibles</h1>
        <div class="row" id="salas-container">
            <?php if (empty($salas)): ?>
                <div class="col-12 text-center">
                    <p>No hay salas disponibles.</p>
                    <button class="btn btn-primary" onclick="crearSala()">Crear Sala</button>
                </div>
            <?php else: ?>
                <?php foreach ($salas as $sala): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card sala-card <?php echo $sala['nom_estado'] == 'en juego' ? 'sala-en-juego' : ''; ?>" 
                             <?php echo $sala['nom_estado'] == 'en juego' ? 'style="pointer-events: none;"' : 'onclick="location.href=\'entrar_sala.php?id_sala=' . $sala['id_sala'] . '\'"'; ?>>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($sala['nom_sala']); ?></h5>
                                <p>Jugadores: <?php echo $sala['jugadores_actuales']; ?>/<?php echo $sala['max_jugadores']; ?></p>
                                <p><span class="estado-badge"><?php echo htmlspecialchars($sala['nom_estado']); ?></span></p>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Create particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 50;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Random position
                particle.style.left = `${Math.random() * 100}vw`;
                particle.style.top = `${Math.random() * 100}vh`;
                
                // Random animation duration
                const duration = Math.random() * 15 + 10;
                particle.style.animationDuration = `${duration}s`;
                
                // Random delay
                const delay = Math.random() * -15;
                particle.style.animationDelay = `${delay}s`;
                
                particlesContainer.appendChild(particle);
            }
        }

        function crearSala() {
            $.ajax({
                url: 'crear_sala.php',
                type: 'POST',
                data: { id_mundo: <?