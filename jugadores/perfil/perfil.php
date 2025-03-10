<?php
require_once('../header.php');
$id_jugador = $usuario['doc'];

$estadisticas = $con->query("SELECT id_jugador,
        SUM(CASE WHEN id_tipo_evento IN (1, 2) THEN puntos ELSE 0 END) AS total_puntos,
        COUNT(CASE WHEN id_tipo_evento = 2 THEN 1 END) AS total_kills,
        COUNT(DISTINCT id_sala) AS total_salas_jugadas

    FROM partidas_eventos
    WHERE id_jugador = $id_jugador
    GROUP BY id_jugador;")->fetch(PDO::FETCH_ASSOC);

// Si no hay estadísticas, inicializar con ceros
if (!$estadisticas) {
    $estadisticas = [
        'total_puntos' => 0,
        'total_kills' => 0,
        'total_salas_jugadas' => 0
    ];
}
?>

<head>
    <title>Perfil de Jugador - Battle Royale</title>
    <style>
        :root {
            --primary-color: #ff4655;
            --secondary-color: #00eaff;
            --dark-bg: rgba(0, 0, 0, 0.8);
            --card-bg: rgba(20, 20, 30, 0.85);
            --text-color: #ffffff;
        }

        body {
            font-family: 'Rajdhani', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            background-color: #000;
            min-height: 100vh;
        }

        #background-video {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -100;
            background-size: cover;
            overflow: hidden;
        }

        .video-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.9) 0%, rgba(20,20,40,0.7) 100%);
            z-index: -99;
        }

        .profile-container {
            background-color: var(--card-bg);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 0 20px rgba(0, 234, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .avatar-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid var(--secondary-color);
            box-shadow: 0 0 15px var(--secondary-color);
        }

        .player-name {
            font-size: 2.5rem;
            font-weight: 700;
            margin-top: 10px;
            text-shadow: 0 0 10px rgba(0, 234, 255, 0.5);
        }

        .level-badge {
            background-color: var(--primary-color);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }

        .stats-card {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .stats-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--secondary-color);
        }

        .achievement {
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .achievement i {
            font-size: 2rem;
            margin-right: 15px;
            color: gold;
        }

        .weapon-item {
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .weapon-item img {
            width: 50px;
            height: 50px;
            margin-right: 15px;
        }

        .match-history {
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .friend-item {
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .friend-item img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .btn-custom {
            background: linear-gradient(to right, var(--primary-color), #ff6b78);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background: linear-gradient(to right, #ff6b78, var(--primary-color));
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(255, 70, 85, 0.3);
        }

        @media (max-width: 768px) {
            .profile-container {
                padding: 10px;
            }

            .player-name {
                font-size: 2rem;
            }

            .avatar-img {
                width: 100px;
                height: 100px;
            }
        }
    </style>
</head>
<body>
    <video autoplay muted loop id="background-video">
        <source src="../img/Videos/indexJu.mp4" type="video/mp4">
        Tu navegador no soporta el elemento de video.
    </video>
    <div class="video-overlay"></div>

    <div class="container">
        <div class="profile-container">
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="../<?php echo $avatar['img']; ?>" alt="Avatar" class="avatar-img">
                    <h1 class="player-name"><?php echo $usuario['nom_usu']; ?></h1>
                </div>
                <div class="col-md-8">
                    <div class="stats-card">
                        <h3 class="stats-title">Estadísticas Principales</h3>
                        <div class="row">
                            <div class="col-4">
                                <p>Puntos: <?php echo $estadisticas['total_puntos']; ?></p>
                            </div>
                            <div class="col-4">
                                <p>Kills: <?php echo $estadisticas['total_kills']; ?></p>
                            </div>
                            <div class="col-4">
                                <p>Partidas: <?php echo $estadisticas['total_salas_jugadas']; ?></p>
                            </div>
                            <div class="col-4">
                                <p>Nivel: <?php echo $nivel['nom_nivel']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <a href="<?php echo '../index.php'; ?>" class="btn btn-custom w-100">Volver al Menú Principal</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>