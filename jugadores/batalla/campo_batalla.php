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
    <style>
        body {
            background-image: url('../../img/mundos/67c0c6f16cf66_Breeze_loading_screen.jpg');
            background-size: cover;
        }
        .avatar {
            position: absolute;
            width: 50px;
            height: 50px;
            cursor: pointer;
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
            height: 150px;
            border: 2px solid #ccc;
            border-radius: 10px;
            margin: 10px;
            display: inline-block;
            text-align: center;
            vertical-align: top;
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
        }
        .estadisticas-modal th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4"><?php echo ($sala['nom_sala']); ?></h1>
        <div class="d-flex justify-content-center">
            <?php foreach ($jugadores as $jugador): ?>
                <div class="jugador-cajon <?php echo $jugador['vida'] <= 0 ? 'jugador-muerto' : ''; ?>" id="jugador-<?php echo $jugador['doc']; ?>">
                    <img src="../<?php echo ($jugador['avatar']); ?>" alt="<?php echo ($jugador['nom_usu']); ?>" class="avatar" <?php echo $jugador['vida'] <= 0 ? 'style="pointer-events: none;"' : ''; ?>>
                    <div class="jugador-nombre"><?php echo ($jugador['nom_usu']); ?></div>
                    <div class="jugador-vida">Vida: <span id="vida-<?php echo $jugador['doc']; ?>"><?php echo $jugador['vida']; ?></span> HP</div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="hud">
        <div id="hud-vida">Vida: <span id="vida"><?php echo isset($jugadores[0]) ? $jugadores[0]['vida'] : 100; ?></span> HP</div>
        <div id="hud-armas">Armas:</div>
        <?php foreach ($armas as $arma): ?>
            <img src="../<?php echo ($arma['img']); ?>" alt="<?php echo ($arma['nom_arma']); ?>" class="arma" id="arma-<?php echo ($arma['id_arma']); ?>" data-dano="<?php echo ($arma['dano']); ?>">
        <?php endforeach; ?>
    </div>
    <div class="contador" id="contador"></div>
    <div class="estadisticas-modal" id="estadisticas-modal">
        <h2>Estadísticas de la Partida</h2>
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
            if (vida > 0) {
                $.ajax({
                    url: 'hacer_dano.php',
                    type: 'POST',
                    data: { id_sala: <?php echo $id_sala; ?>, id_jugador: idJugador, dano: danoArmaSeleccionada, atacante: <?php echo $id_usuario; ?> },
                    success: function(nuevaVida) {
                        if (nuevaVida <= 0) {
                            alert('Has matado a ' + $('#jugador-' + idJugador + ' .jugador-nombre').text());
                            $('#jugador-' + idJugador).addClass('jugador-muerto');
                            $('#jugador-' + idJugador + ' .avatar').css('pointer-events', 'none');
                        }
                        $('#vida-' + idJugador).text(nuevaVida);
                    }
                });
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
                    data: { id_sala: <?php echo $id_sala; ?> },
                    success: function(tiempoRestante) {
                        $('#contador').html("Tiempo restante: " + tiempoRestante + " segundos");
                        if (tiempoRestante <= 0) {
                            clearInterval(intervalo);
                            alert('El tiempo se ha agotado.');
                            registrarEstadisticas();
                        }
                    }
                });
            }, 1000);
        }   

        $(document).ready(function() {
            $('.avatar').click(function() {
                var idJugador = $(this).parent().attr('id').split('-')[1];
                hacerDano(idJugador);
            });

            $('.arma').click(function() {
                $('.arma').removeClass('arma-seleccionada');
                $(this).addClass('arma-seleccionada');
                danoArmaSeleccionada = $(this).data('dano');
            });

            setInterval(actualizarVida, 1000); // Actualizar vida cada segundo
            setInterval(actualizarJugadores, 1000); // Actualizar jugadores cada segundo
            iniciarContador();

            window.onbeforeunload = function() {
                return "No puedes salir del campo de batalla en este momento.";
            };
        });
    </script>
</body>
</html>
