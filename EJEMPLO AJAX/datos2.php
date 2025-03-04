<?php 
$conexion = mysqli_connect('localhost', 'root', '', 'ejercicio_ajax');

$placa = $_POST['placa'];

/*****************************************  CONSULTA DE LOS DATOS ***************************************/
$sql = "SELECT color.nombre FROM vehiculo INNER JOIN color ON color.codigo = vehiculo.id_color WHERE vehiculo.placa = '$placa'";

$result = mysqli_query($conexion, $sql);	
$cadena = "<label>Color Vehiculo</label><br> 
            <select id='id_color' name='id_color'>";

while ($ver = mysqli_fetch_assoc($result)) {
    $cadena .= '<option value="' . $ver['nombre'] . '">' . utf8_encode($ver['nombre']) . '</option>';
}

echo $cadena;
?>
