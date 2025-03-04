<?php 
$conexion = mysqli_connect('localhost', 'root', '', 'ejercicio_ajax');

$placa = $_POST['placa'];

/*****************************************  CONSULTA DE LOS DATOS ***************************************/
$sql = "SELECT marca.nombre FROM vehiculo INNER JOIN marca ON marca.id = vehiculo.id_marca WHERE vehiculo.placa = '$placa'";

$result = mysqli_query($conexion, $sql);	
$cadena = "<label>V=Marca del Vehiculo</label><br> 
            <select id='marca' name='marca'>";

while ($ver = mysqli_fetch_assoc($result)) {
    $cadena .= '<option value="' . $ver['nombre'] . '">' . utf8_encode($ver['nombre']) . '</option>';
}

echo $cadena;
?>