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
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <style>
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
        .jugador-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-top: 10px;
        }
        .jugador-nombre {
            margin-top: 10px;
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
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4"><?php echo ($sala['nom_sala']); ?></h1>
        <div class="d-flex justify-content-center">
            <div id="jugadores-list">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="jugador-cajon <?php echo isset($jugadores[$i]) ? ($jugadores[$i]['listo'] ? 'listo' : 'no-listo') : 'esperando'; ?>" id="jugador-<?php echo $i; ?>">
                        <?php if (isset($jugadores[$i])): ?>
                            <img src="../<?php echo ($jugadores[$i]['avatar']); ?>" alt="Avatar" class="jugador-avatar">
                            <div class="jugador-nombre"><?php echo ($jugadores[$i]['nom_usu']); ?></div>
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
                 console.log('Respuesta del servidor: ', data); // Verifica la respuesta del servidor

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
