<?php
require_once('../header.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_oculto'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $estado = $_POST['estado'];
    $rol = $_POST['rol'];

    $sql = "UPDATE usuarios SET nom_usu = '$nombre', email = '$email', id_estado = $estado, id_rol = $rol WHERE doc = $id";

    if ($con->exec($sql)) {
        echo '<script>alert("Jugador actualizado correctamente.");</script>';
        echo '<script> window.location= "jugadores.php" </script>';
        exit();
    } else {
        echo "Error al actualizar el usuario.";
    }
} else {
    echo "Método no permitido.";
}
?>
