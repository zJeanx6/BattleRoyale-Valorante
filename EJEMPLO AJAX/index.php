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
    <h1 class="text-center mb-4">Consultar Informacion de Vehiculos</h1>
    <div class="card p-4 shadow-lg bg-white">
        <form id="registroForm" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="cedula" class="form-label"><i class="fas fa-user-tie"></i> Propietario</label>
                        <select class="form-control" name="cedula" id="cedula" required>
                            <option value="" selected>** Seleccione Propietario **</option>
                            <?php
                            $statement = $con->prepare('SELECT * FROM propietario');
                            $statement->execute();
                            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['cedula'] . "'>" . $row['cedula'] . " - " . $row['nombres'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="placa" class="form-label"><i class="fas fa-id-card"></i> Placas</label>
                        <select class="form-control" name="placa" id="placa" required>
                            <option value="" selected>** Seleccione Placa **</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="img_vehiculo" class="form-label"><i class="fas fa-image"></i> Imagen del Vehículo</label><br>
                        <img id="img_vehiculo" name="img_vehiculo" src="" alt="" class="img-fluid" style="max-width: 500px; max-height: 300px;">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">  
                        <label for="tipo_vehiculo" class="form-label"><i class="fas fa-car"></i> Tipo Vehículo</label>
                        <select class="form-control" name="tipo_vehiculo" id="tipo_vehiculo" required>
                            <option value="" selected>** Seleccione Tipo Vehículo **</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_color" class="form-label"><i class="fas fa-palette"></i> Color</label>
                        <select class="form-control" name="id_color" id="id_color" required>
                            <option value="" selected>** Seleccione Color **</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="marca" class="form-label"><i class="fas fa-palette"></i> Marca</label>
                        <select class="form-control" name="marca" id="marca" required>
                            <option value="" selected>** Seleccione marca **</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
            <!-- Copyright End -->
        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="lib/wow/wow.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="lib/counterup/counterup.min.js"></script>
        <!-- Template Javascript -->
        <script src="js/main.js"></script>
    </body>
</html>

<!-- A PARTIR DEL NUMERO DE LA CEDULA BUSCAMOS LAS PLACAS -->
<script type="text/javascript">
	$(document).ready(function(){
		$('#cedula').val(0);
		recargarLista();

		$('#cedula').change(function(){
			recargarLista();
		});
	})
</script>
<script type="text/javascript">
	function recargarLista(){
		$.ajax({
			type:"POST",
			url:"projects/datos.php",
			data:"cedula=" + $('#cedula').val(),
			success:function(r){
				$('#placa').html(r);
			}
		});
	}
</script>

<!-- A PARTIR DE LA PLACA BUSCAMOS EL TIPO DE VEHICULO -->
<script type="text/javascript">
	$(document).ready(function(){
		$('#placa').val();
		recargarLista1();

		$('#placa').change(function(){
			recargarLista1();
		});
	})
</script>
<script type="text/javascript">
	function recargarLista1(){
		console.log("Placa seleccionada: " + placa);
		$.ajax({
			type:"POST",
			url:"projects/datos1.php",
			data:"placa=" + $('#placa').val(),
			success:function(r){
				console.log("Respuesta del servidor: " + r);
				$('#tipo_vehiculo').html(r);
			},
		});
	}
</script>

<!-- A PARTIR DE LA PLACA BUSCAMOS EL COLOR DEL VEHICULO -->
<script type="text/javascript">
	$(document).ready(function(){
		$('#placa').val();
		recargarLista2();

		$('#placa').change(function(){
			recargarLista2();
		});
	})
</script>
<script type="text/javascript">
	function recargarLista2(){
		console.log("Placa seleccionada: " + placa);
		$.ajax({
			type:"POST",
			url:"projects/datos2.php",
			data:"placa=" + $('#placa').val(),
			success:function(r){
				console.log("Respuesta del servidor: " + r);
				$('#id_color').html(r);
			},
		});
	}
</script>

<!-- A PARTIR DE LA PLACA BUSCAMOS LA MARCA DEL VEHICULO -->
<script type="text/javascript">
    $(document).ready(function(){
        $('#placa').val();
        recargarLista3();

        $('#placa').change(function(){
            recargarLista3();
        });
    })
</script>
<script type="text/javascript">
    function recargarLista3(){
        console.log("Placa seleccionada: " + placa);
        $.ajax({
            type:"POST",
            url:"projects/datos3.php",
            data:"placa=" + $('#placa').val(),
            success:function(r){
                console.log("Respuesta del servidor: " + r); 
                $('#marca').html(r);
            },
        });
    }
</script>

<!-- A PARTIR DE LA PLACA BUSCAMOS LA IMAGEN DEL VEHICULO -->
<script type="text/javascript">
        $(document).ready(function(){
            $('#placa').val();
            recargarImagen();

            $('#placa').change(function(){
                recargarImagen();
            });
        })
    </script>
<script type="text/javascript">
    function recargarImagen(){
        $.ajax({
            type:"POST",
            url:"projects/datos4.php",
            data:"placa=" + $('#placa').val(),
            success: function(r) {
            $('#img_vehiculo').attr('src', r);
        }
        });
    }
</script>