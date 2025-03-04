<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contador</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Asegúrate de incluir jQuery -->
</head>
<body>
    <!-- <h1>¡Hola!</h1>
    <div id="contador" class="mt-3 text-center"></div>

    <script>
        var id_sala = 2; // Asegúrate de que la variable id_sala esté correctamente definida en PHP

        // Función para verificar si todos los jugadores están listos
        function verificarListos() {
            $.ajax({
                url: 'verificar_listos.php', // Este archivo PHP se encargará de verificar si los jugadores están listos
                type: 'POST',
                data: { id_sala: id_sala }, // Usamos id_sala desde PHP
                success: function(data) {
                    console.log("Respuesta de verificar_listos.php:", data); // Agregar log para depuración
                    if (data.trim() === 'todos_listos') { // Comparar con 'todos_listos' sin espacios extra
                        iniciarContador(); // Si todos están listos, iniciar el contador
                    } else {
                        $('#contador').html("Esperando a que todos los jugadores estén listos...");
                    }
                },
                error: function() {
                    alert("Hubo un error al verificar si los jugadores están listos.");
                }
            });
        }

        // Función para iniciar el contador
        function iniciarContador() {
            var contador = 10; // Tiempo en segundos
            var intervalo = setInterval(function() {
                $('#contador').html("Iniciando en " + contador + " segundos...");
                contador--;
                
                if (contador < 0) {
                    clearInterval(intervalo); // Detenemos el contador cuando llegue a cero
                    $.ajax({
                        url: 'iniciar_partida.php', // Cambia esto por la URL que necesites
                        type: 'POST',
                        data: { id_sala: id_sala }, // Usamos id_sala desde PHP
                        success: function(response) {
                            console.log("Respuesta de iniciar_partida.php:", response); // Agregar log para depuración
                            if(response.trim() === 'todos_listos') {
                                // Si todos los jugadores están listos, redirige
                                window.location.href = "campo_batalla.php?id_sala=" + id_sala; // Redirige después de la petición
                            } else {
                                // Si no todos están listos, muestra un mensaje
                                alert("Aún no todos los jugadores están listos.");
                            }
                        },
                        error: function() {
                            alert("Hubo un error al iniciar la partida.");
                        }
                    });
                }
            }, 1000); // El contador se actualiza cada segundo
        }

        // Verificar si todos los jugadores están listos cada 3 segundos
        setInterval(verificarListos, 3000);
        console.log(data);
    </script> -->
</body>
</html>
