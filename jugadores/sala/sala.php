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
        /* Estilos generales */
        body {
            background-color: #0f1123;
            color: white;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            justify-content: center;
        }
        
        /* Título con efecto de brillo */
        h1 {
            color: #00e5ff;
            font-size: 3rem;
            text-align: center;
            margin-bottom: 2rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 0 0 10px rgba(0, 229, 255, 0.7), 0 0 20px rgba(0, 229, 255, 0.5);
        }
        
        /* Contenedor de jugadores */
        #jugadores-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-bottom: 2rem;
        }
        
        /* Cajas de jugadores */
        .jugador-cajon {
            width: 150px;
            height: 180px;
            border: 2px solid;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #1a1c30;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .listo {
            border-color: #00ff9d;
            box-shadow: 0 0 10px rgba(0, 255, 157, 0.5);
        }
        
        .no-listo {
            border-color: #ff3e3e;
            box-shadow: 0 0 10px rgba(255, 62, 62, 0.5);
        }
        
        .esperando {
            border-color: #444;
        }
        
        .jugador-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
            object-fit: cover;
        }
        
        .jugador-nombre {
            font-weight: bold;
            text-align: center;
            width: 100%;
            padding: 0 5px;
            box-sizing: border-box;
        }
        
        .jugador-estado {
            font-size: 0.8rem;
            margin-top: 5px;
        }
        
        .estado-listo {
            color: #00ff9d;
        }
        
        .estado-no-listo {
            color: #ff3e3e;
        }
        
        /* Botones */
        .btn-container {
            display: flex;
            gap: 15px;
            margin-bottom: 1.5rem;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .btn-secondary {
            background-color: #00e5ff;
            color: #000;
        }
        
        .btn-secondary:hover {
            background-color: #00b8cc;
            box-shadow: 0 0 15px rgba(0, 229, 255, 0.7);
        }
        
        .btn-success {
            background-color: #00ff9d;
            color: #000;
        }
        
        .btn-success:hover {
            background-color: #00cc7d;
            box-shadow: 0 0 15px rgba(0, 255, 157, 0.7);
        }
        
        .btn-danger {
            background-color: #ff3e3e;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #e62e2e;
            box-shadow: 0 0 15px rgba(255, 62, 62, 0.7);
        }
        
        /* Contador */
        #contador {
            font-size: 1.5rem;
            font-weight: bold;
            color: #00e5ff;
            text-shadow: 0 0 10px rgba(0, 229, 255, 0.7);
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 0.7; }
            50% { opacity: 1; }
            100% { opacity: 0.7; }
        }
        
        /* Modal de confirmación */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: #1a1c30;
            border: 1px solid #00e5ff;
            border-radius: 10px;
            padding: 20px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 0 20px rgba(0, 229, 255, 0.3);
        }
        
        .modal-title {
            color: #00e5ff;
            margin-top: 0;
        }
        
        .modal-text {
            margin-bottom: 20px;
            color: #ccc;
        }
        
        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }
            
            .jugador-cajon {
                width: 130px;
                height: 160px;
            }
            
            .jugador-avatar {
                width: 80px;
                height: 80px;
            }
            
            .btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 id="room-name">Sala de Batalla</h1>
        
        <div id="jugadores-list">
            <!-- Los jugadores se generarán dinámicamente -->
        </div>
        
        <div class="btn-container" id="buttons-container">
            <button id="listo-btn" class="btn btn-secondary">Listo</button>
            <button id="abandonar" class="btn btn-danger">Abandonar Sala</button>
        </div>
        
        <div id="contador"></div>
    </div>
    
    <!-- Modal de confirmación -->
    <div id="confirm-modal" class="modal">
        <div class="modal-content">
            <h3 class="modal-title">¿Abandonar la sala?</h3>
            <p class="modal-text">¿Estás seguro de que quieres abandonar la sala?</p>
            <div class="modal-buttons">
                <button id="cancel-leave" class="btn btn-secondary">Cancelar</button>
                <button id="confirm-leave" class="btn btn-danger">Abandonar</button>
            </div>
        </div>
    </div>
    
    <script>
        // Variables globales
        var listo = false;
        var intervalo;
        var contadorEnEjecucion = false;
        var id_sala = <?php echo isset($id_sala) ? $id_sala : 1; ?>;
        var id_usuario = <?php echo isset($id_usuario) ? $id_usuario : 1; ?>;
        
        // Datos de ejemplo para jugadores (reemplazar con datos reales de PHP)
        var jugadores = [
            { id: 1, nom_usu: "Jugador1", avatar: "placeholder.jpg", listo: false },
            { id: 2, nom_usu: "Jugador2", avatar: "placeholder.jpg", listo: true },
            null,
            null,
            null
        ];
        
        // Función para renderizar jugadores
        function renderizarJugadores() {
            var html = '';
            
            for (var i = 0; i < 5; i++) {
                var jugador = jugadores[i];
                var estadoClase = !jugador ? 'esperando' : (jugador.listo ? 'listo' : 'no-listo');
                
                html += '<div class="jugador-cajon ' + estadoClase + '" id="jugador-' + i + '">';
                
                if (jugador) {
                    html += '<img src="' + jugador.avatar + '" alt="Avatar" class="jugador-avatar">';
                    html += '<div class="jugador-nombre">' + jugador.nom_usu + '</div>';
                    html += '<div class="jugador-estado ' + (jugador.listo ? 'estado-listo' : 'estado-no-listo') + '">';
                    html += jugador.listo ? 'LISTO' : 'NO LISTO';
                    html += '</div>';
                } else {
                    html += '<div class="jugador-nombre">ESPERANDO...</div>';
                }
                
                html += '</div>';
            }
            
            $('#jugadores-list').html(html);
        }
        
        // Inicializar la interfaz
        $(document).ready(function() {
            // Renderizar jugadores iniciales
            renderizarJugadores();
            
            // Establecer nombre de sala (reemplazar con datos reales de PHP)
            var nombreSala = "<?php echo isset($sala['nom_sala']) ? $sala['nom_sala'] : 'Sala de Batalla'; ?>";
            $('#room-name').text(nombreSala);
            
            // Evento de botón listo
            $('#listo-btn').click(function() {
                listo = !listo;
                $(this).toggleClass('btn-secondary btn-success');
                $(this).text(listo ? 'Cancelar' : 'Listo');
                actualizarEstadoListo();
            });
            
            // Evento de botón abandonar
            $('#abandonar').click(function() {
                $('#confirm-modal').css('display', 'flex');
            });
            
            // Eventos del modal
            $('#cancel-leave').click(function() {
                $('#confirm-modal').css('display', 'none');
            });
            
            $('#confirm-leave').click(function() {
                abandonarSala();
            });
            
            // Iniciar actualizaciones periódicas
            setInterval(actualizarJugadores, 1000);
            setInterval(verificarListos, 1000);
        });
        
        // Función para actualizar estado listo
        function actualizarEstadoListo() {
            // En un entorno real, esto sería una llamada AJAX
            // $.ajax({
            //     url: 'marcar_listo.php',
            //     type: 'POST',
            //     data: { id_sala: id_sala, id_usuario: id_usuario, listo: listo ? 1 : 0 },
            //     success: function() {
            //         actualizarJugadores();
            //         verificarListos();
            //     }
            // });
            
            // Para demostración, actualizamos directamente
            jugadores[0].listo = listo;
            renderizarJugadores();
            verificarListos();
        }
        
        // Función para actualizar jugadores
        function actualizarJugadores() {
            if (!contadorEnEjecucion) {
                // En un entorno real, esto sería una llamada AJAX
                // $.ajax({
                //     url: 'actualizar_jugadores.php',
                //     type: 'POST',
                //     data: { id_sala: id_sala },
                //     success: function(data) {
                //         // Procesar datos recibidos
                //         renderizarJugadores();
                //     }
                // });
                
                // Para demostración, simulamos cambios aleatorios
                if (Math.random() > 0.9) {
                    var emptySlot = jugadores.findIndex(p => p === null);
                    if (emptySlot >= 0 && emptySlot > 1) { // No modificar los primeros dos jugadores
                        jugadores[emptySlot] = {
                            id: Math.floor(Math.random() * 1000),
                            nom_usu: "Jugador" + Math.floor(Math.random() * 100),
                            avatar: "placeholder.jpg",
                            listo: Math.random() > 0.5
                        };
                        renderizarJugadores();
                    }
                }
            }
        }
        
        // Función para verificar si todos están listos
        function verificarListos() {
            if (!contadorEnEjecucion) {
                // En un entorno real, esto sería una llamada AJAX
                // $.ajax({
                //     url: 'verificar_listos.php',
                //     type: 'POST',
                //     data: { id_sala: id_sala },
                //     success: function(data) {
                //         if (data === 'todos_listos') {
                //             iniciarContador();
                //         } else {
                //             clearInterval(intervalo);
                //             $('#contador').html('');
                //             contadorEnEjecucion = false;
                //         }
                //     }
                // });
                
                // Para demostración, verificamos directamente
                var jugadoresPresentes = jugadores.filter(j => j !== null);
                var todosListos = jugadoresPresentes.length >= 2 && jugadoresPresentes.every(j => j.listo);
                
                if (todosListos) {
                    iniciarContador();
                } else if (contadorEnEjecucion) {
                    clearInterval(intervalo);
                    $('#contador').html('');
                    contadorEnEjecucion = false;
                }
            }
        }
        
        // Función para iniciar contador
        function iniciarContador() {
            if (!contadorEnEjecucion) {
                contadorEnEjecucion = true;
                var contador = 10;
                $('#buttons-container').hide();
                
                $('#contador').html("Iniciando en " + contador + " segundos...");
                
                intervalo = setInterval(function() {
                    contador--;
                    $('#contador').html("Iniciando en " + contador + " segundos...");
                    
                    if (contador < 0) {
                        clearInterval(intervalo);
                        iniciarPartida();
                    }
                }, 1000);
            }
        }
        
        // Función para iniciar partida
        function iniciarPartida() {
            // En un entorno real, esto sería una llamada AJAX
            // $.ajax({
            //     url: 'iniciar_partida.php',
            //     type: 'POST',
            //     data: { id_sala: id_sala },
            //     success: function() {
            //         window.location.href = "../batalla/campo_batalla.php?id_sala=" + id_sala;
            //     }
            // });
            
            // Para demostración, redirigimos directamente
            window.location.href = "../batalla/campo_batalla.php?id_sala=" + id_sala;
        }
        
        // Función para abandonar sala
        function abandonarSala() {
            // En un entorno real, esto sería una llamada AJAX
            // $.ajax({
            //     url: 'abandonar_sala.php',
            //     type: 'POST',
            //     data: { id_sala: id_sala, id_usuario: id_usuario },
            //     success: function() {
            //         window.location.href = '../index.php';
            //     }
            // });
            
            // Para demostración, redirigimos directamente
            window.location.href = '../index.php';
        }
    </script>
</body>
</html>
