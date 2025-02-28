<?php
require_once('../header.php');

// Obtener el ID del usuario desde la sesión
session_start();
if (!isset($_SESSION['doc'])) {
    echo "<p>Error: No se ha iniciado sesión.</p>";
    exit;
}

$idUsuario = $_SESSION['doc'];

// Obtener datos del formulario
$nom_usu = isset($_POST['nom_usu']) ? trim($_POST['nom_usu']) : null;
$email = isset($_POST['email']) ? trim($_POST['email']) : null;

// Validar datos enviados
if ($email !== null && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo '<script>alert("El correo electrónico no es válido.");</script>';
    echo '<script>window.location = "perfil.php"</script>';
    exit;
}

// Construir la consulta dinámicamente según los campos enviados
$campos = [];
$parametros = [];

if ($nom_usu !== null && $nom_usu !== '') {
    $campos[] = "nom_usu = :nom_usu";
    $parametros[':nom_usu'] = $nom_usu;
}

if ($email !== null && $email !== '') {
    $campos[] = "email = :email";
    $parametros[':email'] = $email;
}

// Verificar si hay al menos un campo para actualizar
if (empty($campos)) {
    echo '<script>alert("No se enviaron datos para actualizar.");</script>';
    echo '<script>window.location = "perfil.php"</script>';
    exit;
}

// Preparar la consulta
$query = "UPDATE usuarios SET " . implode(', ', $campos) . " WHERE doc = :doc";
$parametros[':doc'] = $idUsuario;

$stmt = $con->prepare($query);

// Ejecutar la consulta
if ($stmt->execute($parametros)) {
    echo '<script>alert("Perfil actualizado correctamente.");</script>';
    echo '<script>window.location = "perfil.php"</script>';
} else {
    echo "Error al actualizar el perfil.";
}
?>
