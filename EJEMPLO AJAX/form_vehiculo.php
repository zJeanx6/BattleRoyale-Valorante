<?php
require 'config/database.php';
$db = new Database();
$con = $db->conectar();
?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Formulario Propietario-Vehiculos</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600&family=Rubik:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Registrar Vehiculos</h1>
    <div class="card p-4 shadow-lg bg-white">
        <form id="registroForm" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <!-- Campo de PLACA -->
                    <div class="mb-3">
                        <label for="placa" class="form-label"><i class="fas fa-id-card"></i> PLACA</label><br>
                        <input type="text" class="form-control" id="placa" name="placa" required>
                    </div>
                    <!-- Campo de cédula -->    
                    <div class="mb-3">
                        <label for="cedula" class="form-label"><i class="fas fa-user"></i> PROPIETARIO</label>
                        <select class="form-control" name="cedula" id="cedula" required>
                            <option value="" selected>** Seleccione Propietario **</option>
                            <?php   
                            $statement = $con->prepare('SELECT * FROM propietario;');
                            $statement->execute();
                            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value=" . $row['cedula'] . ">" . $row['cedula'] . " - " . $row['nombres'] . "</option>";
                            }?>
                        </select>
                    </div>
                    <!-- Campo para subir imagen -->
                    <div class="mb-3">
                        <label for="imagen">Subir Imagen (Solo JPG, máximo 200 KB):</label>
                        <input class="form-control" type="file" id="imagen" name="imagen" accept=".jpg" required>
                        <div class="error-message" id="errorImagen"></div>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Campo Tipo vehiculo -->
                    <div class="mb-3">
                        <label for="tipo_vehiculo" class="form-label"><i class="fas fa-car"></i> Tipo Vehículo</label>
                        <select class="form-control" name="tipo_vehiculo" id="tipo_vehiculo" required>
                            <option value="" selected>** Seleccione Tipo Vehículo **</option>
                            <?php   $statement = $con->prepare('SELECT * FROM tipo_vehiculo;');
                                    $statement->execute();
                                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value=" . $row['id_tipo_vehiculo'] . ">" . $row['id_tipo_vehiculo'] . " - " . $row['nombre'] . "</option>";
                            }?>
                        </select>
                    </div>

                    <!-- Campo Color -->
                    <div class="mb-3">
                        <label for="id_color" class="form-label"><i class="fas fa-palette"></i> Color</label>
                        <select class="form-control" name="id_color" id="id_color" required>
                            <option value="" selected>** Seleccione Color **</option>
                            <?php   $statement = $con->prepare('SELECT * FROM color;');
                                    $statement->execute();
                                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value=" . $row['codigo'] . ">" . $row['codigo'] . " - " . $row['nombre'] . "</option>";
                            }?>
                        </select>
                    </div>

                    <!-- Campo Marca -->    
                    <div class="mb-3">
                        <label for="marca" class="form-label"><i class="fas fa-palette"></i> Marca</label>
                        <select class="form-control" name="marca" id="marca" required>
                            <option value="" selected>** Seleccione marca **</option>
                            <?php   $statement = $con->prepare('SELECT * FROM marca;');
                                    $statement->execute();
                                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value=" . $row['id'] . ">" . $row['id'] . " - " . $row['nombre'] . "</option>";
                            }?>
                        </select>
                    </div>
                </div>
                <!-- Botón de guardar -->
                <input type="submit" value="Guardar">
        </form>
    </div>
    <script>
        const form = document.getElementById('registroForm');

         //async function (event)) de JavaScript sirve para definir funciones asíncronas. Esto permite que un programa pueda iniciar una tarea larga y seguir respondiendo a otros eventos mientras esa tarea se ejecuta.  
        form.addEventListener('submit', async function (event) {
            event.preventDefault();
    
            const formData = new FormData();
            formData.append('placa', document.getElementById('placa').value);
            formData.append('cedula', document.getElementById('cedula').value);
            formData.append('tipo_vehiculo', document.getElementById('tipo_vehiculo').value);
            formData.append('id_color', document.getElementById('id_color').value);
            formData.append('marca', document.getElementById('marca').value);
            formData.append('imagen', document.getElementById('imagen').files[0]);
    
            try {
                const response = await fetch('guardar.php', {
                    method: 'POST',
                    body: formData,
                });

                //espera la respuesta que viene de guardar.php para mostrar el mensaje  
                const result = await response.json();

                // si recibe un msj por parte de echo enconde_json los muestra viene de guardar.php 
                if (result.message) {
                    alert(result.message); // Mostrar mensaje de éxito
                    limpiarFormulario();   // Limpiar el formulario
                } else if (result.error) {
                    alert(result.error);
                }
            } catch (error) {
                console.error(error);
                alert('Error al conectar con el servidor.');
            }

            // Función para limpiar el formulario
            function limpiarFormulario() {
                document.getElementById('placa').value = '';
                document.getElementById('cedula').value = '';
                document.getElementById('tipo_vehiculo').value = '';
                document.getElementById('id_color').value = '';
                document.getElementById('marca').value = '';
                document.getElementById('imagen').value = '';
            }
        });
    </script>
</body>
</html>