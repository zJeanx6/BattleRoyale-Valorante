<?php
session_start();
require_once('../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_GET['id_sala']);
$id_usuario = $_SESSION['doc'];

$sala = $con->query("SELECT nom_sala, duracion_segundos FROM salas WHERE id_sala = $id_sala")->fetch(PDO::FETCH_ASSOC);
$jugadores = $con->query("SELECT usuarios.doc, usuarios.nom_usu, avatar.img AS avatar, jugadores_salas.vida FROM jugadores_salas INNER JOIN usuarios ON jugadores_salas.id_jugador = usuarios.doc INNER JOIN avatar ON usuarios.id_avatar = avatar.id_avatar WHERE jugadores_salas.id_sala = $id_sala AND usuarios.doc != $id_usuario")->fetchAll(PDO::FETCH_ASSOC);
$armas = $con->query("SELECT armas.id_arma, armas.nom_arma, armas.img, tipos_armas.dano FROM jugadores_armas INNER JOIN armas ON jugadores_armas.id_arma = armas.id_arma INNER JOIN tipos_armas ON armas.id_tipo_arma = tipos_armas.id_tip_arma WHERE jugadores_armas.id_jugador = $id_usuario")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campo de Batalla</title>
    <style>
        body {
            background-image: url('../img/mundos/67c0c6f16cf66_Breeze_loading_screen.jpg');
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
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4"><?php echo htmlspecialchars($sala['nom_sala']); ?></h1>
        <div class="d-flex justify-content-center">
            <?php foreach ($jugadores as $jugador): ?>
                <div class="jugador-cajon" id="jugador-<?php echo $jugador['doc']; ?>">
                    <img src="<?php echo htmlspecialchars($jugador['avatar']); ?>" alt="<?php echo htmlspecialchars($jugador['nom_usu']); ?>" class="avatar">
                    <div class="jugador-nombre"><?php echo htmlspecialchars($jugador['nom_usu']); ?></div>
                    <div class="jugador-vida">Vida: <span id="vida-<?php echo $jugador['doc']; ?>"><?php echo $jugador['vida']; ?></span> HP</div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="hud">
        <div>Vida: <span id="vida"><?php echo isset($jugadores[0]) ? $jugadores[0]['vida'] : 100; ?></span> HP</div>
        <div>Armas:</div>
        <?php foreach ($armas as $arma): ?>
            <img src="<?php echo htmlspecialchars($arma['img']); ?>" alt="<?php echo htmlspecialchars($arma['nom_arma']); ?>" class="arma" id="arma-<?php echo htmlspecialchars($arma['id_arma']); ?>" data-dano="<?php echo htmlspecialchars($arma['dano']); ?>">
        <?php endforeach; ?>
    </div>
    <div class="contador" id="contador"></div>
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
                        alert('Has sido eliminado.');
                        window.location.href = 'salas.php';
                    }
                }
            });
        }

        function hacerDano(idJugador) {
            $.ajax({
                url: 'hacer_dano.php',
                type: 'POST',
                data: { id_sala: <?php echo $id_sala; ?>, id_jugador: idJugador, dano: danoArmaSeleccionada },
                success: function(nuevaVida) {
                    $('#vida-' + idJugador).text(nuevaVida);
                }
            });
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
                    });
                }
            });
        }

        // function iniciarContador() {
        //     intervalo = setInterval(function() {
        //         $('#contador').html("Tiempo restante: " + duracionSala + " segundos");
        //         duracionSala--;
        //         if (duracionSala < 0) {
        //             clearInterval(intervalo);
        //             alert('El tiempo se ha agotado.');
        //             window.location.href = 'salas.php';
        //         }
        //     }, 1000);
        // }

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
