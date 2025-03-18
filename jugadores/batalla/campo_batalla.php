<?php
require_once('../header.php');
$id_sala = intval($_GET['id_sala']);

$sala = $con->query("SELECT nom_sala, duracion_segundos FROM salas WHERE id_sala = $id_sala")->fetch(PDO::FETCH_ASSOC);
$jugadores = $con->query("SELECT usuarios.doc, usuarios.nom_usu, avatar.img AS avatar, jugadores_salas.vida FROM jugadores_salas INNER JOIN usuarios ON jugadores_salas.id_jugador = usuarios.doc INNER JOIN avatar ON usuarios.id_avatar = avatar.id_avatar WHERE jugadores_salas.id_sala = $id_sala AND usuarios.doc != $id_usuario")->fetchAll(PDO::FETCH_ASSOC);
$armas = $con->query("SELECT armas.id_arma, armas.nom_arma, armas.img, tipos_armas.dano FROM jugadores_armas INNER JOIN armas ON jugadores_armas.id_arma = armas.id_arma INNER JOIN tipos_armas ON armas.id_tipo_arma = tipos_armas.id_tip_arma WHERE jugadores_armas.id_jugador = $id_usuario")->fetchAll(PDO::FETCH_ASSOC);

// Cambiar el estado de la sala a "en juego"
$con->prepare("UPDATE salas SET id_estado_sala = 5 WHERE id_sala = ?")->execute([$id_sala]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campo de Batalla</title>
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
            background-image: url('../../img/mundos/67c0c6f16cf66_Breeze_loading_screen.jpg');
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

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-top: 10px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .hud {
            position: fixed;
            bottom: 10px;
            left: 10px;
            color: white;
        }

        .contador {
            position: fixed;
            top: 10px;
            right: 10px;
            color: white;
        }

        .arma {
            width: 50px;
            height: 50px;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .arma-seleccionada {
            border-color: blue;
        }

        .jugador-cajon {
            width: 150px;
            height: 200px;
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

        .jugador-muerto {
            position: relative;
        }

        .jugador-muerto::after {
            content: 'X';
            color: red;
            font-size: 50px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .estadisticas-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .estadisticas-modal h2 {
            margin-bottom: 20px;
        }

        .estadisticas-modal table {
            width: 100%;
            border-collapse: collapse;
        }

        .estadisticas-modal th, .estadisticas-modal td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            color: black; /* Cambiar el color del texto a negro */
        }

        .estadisticas-modal th {
            background-color:rgb(173, 174, 180);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4"><?php echo htmlspecialchars($sala['nom_sala']); ?></h1>
        <div class="d-flex justify-content-center">
            <?php foreach ($jugadores as $jugador): ?>
                <div class="jugador-cajon <?php echo $jugador['vida'] <= 0 ? 'jugador-muerto' : ''; ?>" id="jugador-<?php echo $jugador['doc']; ?>">
                    <img src="../<?php echo htmlspecialchars($jugador['avatar']); ?>" alt="<?php echo htmlspecialchars($jugador['nom_usu']); ?>" class="avatar" <?php echo $jugador['vida'] <= 0 ? 'style="pointer-events: none;"' : ''; ?>>
                    <div class="jugador-nombre"><?php echo htmlspecialchars($jugador['nom_usu']); ?></div>
                    <div class="jugador-vida">Vida: <span id="vida-<?php echo $jugador['doc']; ?>"><?php echo $jugador['vida']; ?></span> HP</div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Nombre del jugador actual -->
    <div class="hud">
        <div id="hud-vida">Yo : <?php echo ($usuario['nom_usu']); ?></div>
        <div id="hud-vida">Vida : <span id="vida"><?php echo isset($jugadores[0]) ? $jugadores[0]['vida'] : 100; ?></span> HP</div>
        <div id="hud-armas">Armas :
        <?php foreach ($armas as $arma): ?>
            <img src="../<?php echo htmlspecialchars($arma['img']); ?>" alt="<?php echo htmlspecialchars($arma['nom_arma']); ?>" class="arma" id="arma-<?php echo $arma['id_arma']; ?>" data-dano="<?php echo $arma['dano']; ?>">
        <?php endforeach; ?>
    </div>

    <!-- Botón para mostrar/ocultar el chat -->
    <button id="toggle-chat" class="btn btn-secondary" style="position: fixed; bottom: 10px; right: 10px; z-index: 1000;">
        <i class="fas fa-history"></i>
    </button>

    <!-- Chat de eventos -->
    <div class="chat-eventos" id="chat-eventos" style="position: fixed; bottom: 60px; right: 10px; width: 300px; height: 200px; overflow-y: auto; background-color: rgba(0, 0, 0, 0.7); color: white; padding: 10px; border-radius: 10px; display: none;">
        <h5>Eventos de la partida</h5>
        <ul id="lista-eventos" style="list-style: none; padding: 0; margin: 0;"></ul>
    </div>

    <div class="contador" id="contador"></div>
    <div class="estadisticas-modal" id="estadisticas-modal">
        <table>
            <thead>
                <tr>
                    <th>Jugador</th>
                    <th>Puntos</th>
                    <th>Muertes</th>
                </tr>
            </thead>
            <tbody id="estadisticas-contenido"></tbody>
        </table>
        <button onclick="cerrarModal()">Cerrar</button>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        var vida = <?php echo isset($jugadores[0]) ? $jugadores[0]['vida'] : 100; ?>;
        var duracionSala = <?php echo $sala['duracion_segundos']; ?>;
        var intervalo;
        var danoArmaSeleccionada = 0;
        var partidaTerminada = false;
        var idArmaSeleccionada = null; // Variable para almacenar el id del arma seleccionada

        function actualizarVida() {
            $.ajax({
                url: 'actualizar_vida.php',
                type: 'POST',
                data: { id_sala: <?php echo $id_sala; ?>, id_usuario: <?php echo $id_usuario; ?> },
                success: function(nuevaVida) {
                    vida = nuevaVida;
                    $('#vida').text(vida);
                    if (vida <= 0) {
                        $('#hud-vida').hide();
                        $('#hud-armas').hide();
                        $('.arma').hide();
                    }
                }
            });
        }

        function hacerDano(idJugador) {
            if (vida > 0 && idArmaSeleccionada !== null) {
                $.ajax({
                    url: 'hacer_dano.php',
                    type: 'POST',
                    data: { 
                        id_sala: <?php echo $id_sala; ?>, 
                        id_jugador: idJugador, 
                        dano: danoArmaSeleccionada, 
                        atacante: <?php echo $id_usuario; ?>, 
                        id_arma: idArmaSeleccionada // Enviar el id del arma seleccionada
                    },
                    success: function(nuevaVida) {
                        if (nuevaVida <= 0) {
                            $('#jugador-' + idJugador).addClass('jugador-muerto');
                            $('#jugador-' + idJugador + ' .avatar').css('pointer-events', 'none');
                        }
                        $('#vida-' + idJugador).text(nuevaVida);
                    }
                });
            } else {
                alert('Selecciona un arma antes de atacar.');
            }
        }

        function actualizarJugadores() {
            $.ajax({
                url: 'actualizar_jugadores_batalla.php',
                type: 'POST',
                data: { id_sala: <?php echo $id_sala; ?> },
                success: function(data) {
                    var jugadores = JSON.parse(data);
                    jugadores.forEach(function(jugador) {
                        $('#vida-' + jugador.doc).text(jugador.vida);
                        if (jugador.vida <= 0) {
                            $('#jugador-' + jugador.doc).addClass('jugador-muerto');
                            $('#jugador-' + jugador.doc + ' .avatar').css('pointer-events', 'none');
                        }
                    });
                }
            });
        }

        function registrarEstadisticas() {
            $.ajax({
                url: 'registrar_estadisticas.php',
                type: 'POST',
                data: { id_sala: <?php echo $id_sala; ?> },
                success: function(response) {
                    console.log('Estadísticas registradas: ' + response);
                    mostrarEstadisticas();
                }
            });
        }

        function mostrarEstadisticas() {
            $.ajax({
                url: 'obtener_estadisticas.php',
                type: 'POST',
                data: { id_sala: <?php echo $id_sala; ?> },
                success: function(data) {
                    var estadisticas = JSON.parse(data);
                    var contenido = '';
                    estadisticas.forEach(function(est) {
                        contenido += '<tr><td>' + est.nom_usu + '</td><td>' + est.puntos + '</td><td>' + est.muertes + '</td></tr>';
                    });
                    $('#estadisticas-contenido').html(contenido);
                    $('#estadisticas-modal').show();
                    $('.avatar').css('pointer-events', 'none'); // Deshabilitar clicks en los jugadores
                }
            });
        }

        function cerrarModal() {
            $('#estadisticas-modal').hide();
            window.location.href = '../index.php';
        }

        function iniciarContador() {
            intervalo = setInterval(function() {
                $.ajax({
                    url: 'obtener_tiempo_restante.php',
                    type: 'POST',
                    data: { id_sala: <?php echo $id_sala; ?>, id_usuario: <?php echo $id_usuario; ?> },
                    success: function(tiempoRestante) {
                        $('#contador').html("Tiempo restante: " + tiempoRestante + " segundos");
                        if (tiempoRestante <= 0) {
                            clearInterval(intervalo);
                            if (!partidaTerminada) {
                                partidaTerminada = true;
                                alert('El tiempo se ha agotado.');
                                registrarEstadisticas();
                            }
                        }
                    }
                });
            }, 1000);
        }   

        function actualizarJugadoresVivos() {
            $.ajax({
                url: 'actualizar_jugadores_vivos.php',
                type: 'POST',
                data: { id_sala: <?php echo $id_sala; ?> },
                success: function(jugadoresVivos) {
                    if (jugadoresVivos <= 1 && !partidaTerminada) {
                        partidaTerminada = true;
                        clearInterval(intervalo);
                        alert('La partida ha terminado.');
                        registrarEstadisticas();
                    }
                }
            });
        }

        function actualizarEventos() {
            $.ajax({
                url: 'obtener_eventos.php',
                type: 'POST',
                data: { id_sala: <?php echo $id_sala; ?> },
                success: function(data) {
                    var eventos = JSON.parse(data);
                    var listaEventos = $('#lista-eventos');
                    listaEventos.empty();
                    eventos.forEach(function(evento) {
                        listaEventos.append('<li>' + evento.descripcion + '</li>');
                    });
                    $('#chat-eventos').scrollTop($('#chat-eventos')[0].scrollHeight); // Desplazar al final
                }
            });
        }

        // Mostrar/ocultar el chat al hacer clic en el botón
        document.getElementById('toggle-chat').addEventListener('click', function() {
            const chat = document.getElementById('chat-eventos');
            chat.style.display = chat.style.display === 'none' ? 'block' : 'none';
        });

        $(document).ready(function() {
            $('.avatar').click(function() {
                var idJugador = $(this).parent().attr('id').split('-')[1];
                hacerDano(idJugador);
            });

            $('.arma').click(function() {
                $('.arma').removeClass('arma-seleccionada');
                $(this).addClass('arma-seleccionada');
                danoArmaSeleccionada = $(this).data('dano');
                idArmaSeleccionada = $(this).attr('id').split('-')[1]; // Obtener el id del arma seleccionada
            });

            setInterval(actualizarVida, 1000); // Actualizar vida cada segundo
            setInterval(actualizarJugadores, 1000); // Actualizar jugadores cada segundo
            setInterval(actualizarJugadoresVivos, 1000); // Verificar jugadores vivos cada segundo
            setInterval(actualizarEventos, 1000); // Actualizar eventos cada segundo
            iniciarContador();

            window.onbeforeunload = function() {
                return "No puedes salir del campo de batalla en este momento.";
            };
        });
    </script>
</body>
</html>
