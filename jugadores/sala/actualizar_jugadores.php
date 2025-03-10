<?php
require_once('../header.php');

$id_sala = intval($_POST['id_sala']);

$jugadores = $con->query("SELECT usuarios.nom_usu, avatar.img AS avatar, jugadores_salas.listo FROM jugadores_salas INNER JOIN usuarios ON jugadores_salas.id_jugador = usuarios.doc INNER JOIN avatar ON usuarios.id_avatar = avatar.id_avatar WHERE jugadores_salas.id_sala = $id_sala")->fetchAll(PDO::FETCH_ASSOC);

for ($i = 0; $i < 5; $i++) {
    echo '<div class="jugador-cajon ' . (isset($jugadores[$i]) ? ($jugadores[$i]['listo'] ? 'listo' : 'no-listo') : 'esperando') . '" id="jugador-' . $i . '">';
    if (isset($jugadores[$i])) {
        echo '<img src="../' . ($jugadores[$i]['avatar']) . '" alt="Avatar" class="jugador-avatar">';
        echo '<div class="jugador-nombre">' . ($jugadores[$i]['nom_usu']) . '</div>';
    } else {
        echo '<div class="jugador-nombre">Esperando...</div>';
    }
    echo '</div>';
}
?>
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

        .jugador-cajon {
            width: 150px;
            height: 150px;
            border: 2px solid #ccc;
            border-radius: 10px;
            margin: 10px;
            display: inline-block;
            text-align: center;
            vertical-align: top;
            background-color: var(--card-bg);
            transition: all 0.3s ease;
            position: relative;
        }

        .jugador-cajon:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 234, 255, 0.3);
            border-color: var(--secondary-color);
        }

        .jugador-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-top: 10px;
        }

        .jugador-nombre {
            margin-top: 10px;
            color: var(--text-color);
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .listo {
            border-color: green;
        }

        .no-listo {
            border-color: red;
        }

        .esperando {
            border-color: gray;
        }

        @media (max-width: 768px) {
            .container {
                padding-top: 30px;
                padding-bottom: 30px;
            }

            h1 {
                font-size: 2rem;
            }

            .jugador-cajon {
                width: 120px;
                height: 120px;
            }

            .jugador-avatar {
                width: 80px;
                height: 80px;
            }
        }
    </style>
