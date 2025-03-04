<?php 
$conexion = mysqli_connect('localhost', 'root', '', 'ejercicio_ajax');

$placa = $_POST['placa'];

/*****************************************  CONSULTA DE LOS DATOS ***************************************/
$sql = "SELECT tipo_vehiculo.nombre FROM vehiculo INNER JOIN tipo_vehiculo ON tipo_vehiculo.id_tipo_vehiculo = vehiculo.id_tipo_vehiculo WHERE vehiculo.placa = '$placa'";

$result = mysqli_query($conexion, $sql);	
$cadena = "<label>Tipo Vehiculo</label><br> 
            <select id='tipo_vehiculo' name='tipo_vehiculo'>";

while ($ver = mysqli_fetch_assoc($result)) {
    $cadena .= '<option value="' . $ver['nombre'] . '">' . utf8_encode($ver['nombre']) . '</option>';
}

echo $cadena;
?>
