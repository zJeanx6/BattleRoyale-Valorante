<?php
require_once('../header.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($id == 10000003) {
        echo '<script>alert("No puedes eliminar al Usuario con ID 0 (administrador principal).");</script>';
        echo '<script> window.location= "jugadores.php" </script>';
        exit();
    }

    $sql = "DELETE FROM usuarios WHERE doc = $id";

    if ($con->exec($sql)) {
        echo '<script>alert("Jugador eliminado correctamente.");</script>';
        echo '<script> window.location= "jugadores.php" </script>';
        exit();
    } else {
        echo "Error al eliminar el Jugador.";
    }
} else {
    echo "No se ha proporcionado un ID válido para eliminar.";
}
?>
