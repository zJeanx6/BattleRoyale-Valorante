<?php
$conexion = mysqli_connect('localhost', 'root', '', 'ejercicio_ajax');

$placa = $_POST['placa'];

/*****************************************  CONSULTA DE LOS DATOS ***************************************/
$sql = "SELECT img_vehiculo FROM vehiculo WHERE placa = '$placa'";

$result = mysqli_query($conexion, $sql);
while ($ver = mysqli_fetch_assoc($result)) {
    echo trim($ver['img_vehiculo']);
}
?>
