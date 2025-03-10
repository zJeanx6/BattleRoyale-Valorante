<?php
require_once('../header.php');
$id_sala = intval($_GET['id_sala']);

$sala = $con->query("SELECT nom_sala FROM salas WHERE id_sala = $id_sala")->fetch(PDO::FETCH_ASSOC);
$jugadores = $con->query("SELECT usuarios.nom_usu, avatar.img AS avatar, jugadores_salas.listo FROM jugadores_salas INNER JOIN usuarios ON jugadores_salas.id_jugador = usuarios.doc INNER JOIN avatar ON usuarios.id_avatar = avatar.id_avatar WHERE jugadores_salas.id_sala = $id_sala")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sala de Espera</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4"><?php echo $sala['nom_sala']; ?></h1>
        <div class="d-flex justify-content-center">
            <div id="jugadores-list">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="jugador-cajon <?php echo isset($jugadores[$i]) ? ($jugadores[$i]['listo'] ? 'listo' : 'no-listo') : 'esperando'; ?>" id="jugador-<?php echo $i; ?>">
                        <?php if (isset($jugadores[$i])): ?>
                            <img src="../<?php echo $jugadores[$i]['avatar']; ?>" alt="Avatar" class="jugador-avatar">
                            <div class="jugador-nombre"><?php echo $jugadores[$i]['nom_usu']; ?></div>
                        <?php else: ?>
                            <div class="jugador-nombre">Esperando...</div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
        <div class="text-center mt-3">
            <button id="listo-btn" class="btn btn-secondary">Listo</button>
            <button id="abandonar" class="btn btn-danger">Abandonar Sala</button>
        </div>
        <div id="contador" class="mt-3 text-center"></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        var listo = false;
        var intervalo;
        var contadorEnEjecucion = false;

        $('#listo-btn').click(function() {
            listo = !listo;
            $(this).toggleClass('btn-success btn-secondary');
            $(this).text(listo ? 'Cancelar' : 'Listo');
            actualizarEstadoListo();
        });

        $('#abandonar').click(function() {
            if (confirm("¿Estás seguro de que quieres abandonar la sala?")) {
                $.ajax({
                    url: 'abandonar_sala.php',
                    type: 'POST',
                    data: { id_sala: <?php echo $id_sala; ?>, id_usuario: <?php echo $id_usuario; ?> },
                    success: function() {
                        window.location.href = '../index.php';
                    }
                });
            }
        });

        function actualizarEstadoListo() {
            $.ajax({
                url: 'marcar_listo.php',
                type: 'POST',
                data: { id_sala: <?php echo $id_sala; ?>, id_usuario: <?php echo $id_usuario; ?>, listo: listo ? 1 : 0 },
                success: function() {
                    actualizarJugadores();
                    verificarListos();
                }
            });
        }

        function actualizarJugadores() {
            if (!contadorEnEjecucion) {
                $.ajax({
                    url: 'actualizar_jugadores.php',
                    type: 'POST',
                    data: { id_sala: <?php echo $id_sala; ?> },
                    success: function(data) {
                        $('#jugadores-list').html(data);
                    }
                });
            }
        }

        function verificarListos() {
            if (!contadorEnEjecucion) {
                $.ajax({
                    url: 'verificar_listos.php',
                    type: 'POST',
                    data: { id_sala: <?php echo $id_sala; ?> },
                    success: function(data) {
                        if (data === 'todos_listos') {
                            iniciarContador();
                        } else {
                            clearInterval(intervalo);
                            $('#contador').html('');
                            contadorEnEjecucion = false;
                        }
                    }
                });
            }
        }

        function iniciarContador() {
            contadorEnEjecucion = true;
            var contador = 10;
            $('#listo-btn').hide();
            $('#abandonar').hide();
            intervalo = setInterval(function() {
                $('#contador').html("Iniciando en " + contador + " segundos...");
                contador--;
                if (contador < 0) {
                    clearInterval(intervalo);
                    $.ajax({
                        url: 'iniciar_partida.php',
                        type: 'POST',
                        data: { id_sala: <?php echo $id_sala; ?> },
                        success: function() {
                            window.location.href = "../batalla/campo_batalla.php?id_sala=<?php echo $id_sala; ?>";
                        }
                    });
                }
            }, 1000);
        }

        setInterval(actualizarJugadores, 1000); // Actualizar cada segundo
        setInterval(verificarListos, 1000); // Verificar listos cada segundo
    </script>
</body>
</html>