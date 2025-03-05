<?php
$conexion = mysqli_connect('localhost', 'root', '', 'ejercicio_ajax');

$cedula = $_POST['cedula'];

/*****************************************  CONSULTA DE LOS DATOS ***************************************/
$sql = "SELECT placa FROM vehiculo WHERE cedula = '$cedula'";

$result = mysqli_query($conexion, $sql);
$cadena = "<option value=''>** Seleccione Placa **</option>";

while ($ver = mysqli_fetch_assoc($result)) {
    $cadena .= '<option value="' . $ver['placa'] . '">' . utf8_encode($ver['placa']) . '</option>';
}

echo $cadena;
?>