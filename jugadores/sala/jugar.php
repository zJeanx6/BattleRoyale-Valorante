<?php
require_once('../header.php');
$nivel = $con->query(obtenerNivelPorUsuario2($id_usuario))->fetch(PDO::FETCH_ASSOC)['id_nivel'];
$mundos = $con->query(obtenerMundosPorNivel($nivel))->fetchAll(PDO::FETCH_ASSOC);
?>
<head>
    <title>Seleccionar Mundo</title>
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

        .mundo-card {
            background-color: var(--card-bg);
            border: 2px solid transparent;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .mundo-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 234, 255, 0.3);
            border-color: var(--secondary-color);
        }

        .mundo-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent 50%, rgba(0, 0, 0, 0.7) 100%);
            z-index: 1;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .mundo-card:hover .card-img-top {
            transform: scale(1.1);
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

        @media (max-width: 768px) {
            .container {
                padding-top: 30px;
                padding-bottom: 30px;
            }

            h1 {
                font-size: 2rem;
            }

            .card-img-top {
                height: 150px;
            }
        }

        /* Efecto de partículas flotantes */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            background-color: var(--secondary-color);
            border-radius: 50%;
            opacity: 0.3;
            animation: float 15s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <div class="particles">
        <!-- Partículas generadas dinámicamente con JavaScript -->
    </div>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Seleccionar Mundo</h1>
        <div class="row">
            <?php foreach ($mundos as $mundo): ?>
                <div class="col-md-4 mb-3">
                    <div class="card mundo-card" onclick="location.href='salas.php?id_mundo=<?php echo $mundo['id_mundo']; ?>'">
                        <img src="../<?php echo $mundo['img'];?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo ($mundo['nom_mundo']); ?></h5>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Generar partículas flotantes
        function createParticles() {
            const particlesContainer = document.querySelector('.particles');
            const particleCount = 50;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;
                particle.style.width = `${Math.random() * 5 + 1}px`;
                particle.style.height = particle.style.width;
                particle.style.animationDuration = `${Math.random() * 10 + 5}s`;
                particle.style.animationDelay = `${Math.random() * 5}s`;
                particlesContainer.appendChild(particle);
            }
        }

        document.addEventListener('DOMContentLoaded', createParticles);
    </script>
</body>
</html>