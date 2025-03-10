<?php
require '../config.php';
require '../send_email.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Conexión a la base de datos
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Verificar si el correo existe en la base de datos
    $sql = "SELECT doc FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($doc);
        $stmt->fetch();

        // Generar un token de recuperación
        $token = bin2hex(random_bytes(16));
        $token_expiracion = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        // Insertar el token en la base de datos
        $sql = "INSERT INTO recuperacion_contrasena (id_usuario, token_recuperacion, token_expiracion) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $doc, $token, $token_expiracion);
        $stmt->execute();

        // Enviar el correo de recuperación
        $subject = "Recuperacion de Contrasena";
        $body = "Haz clic en el siguiente enlace para recuperar tu contraseña: <a href='" . BASE_URL . "/nueva_contraseña.php?token=$token'>Recuperar Contraseña</a>";

        sendEmail($email, $subject, $body);

        echo "<script>alert('Se ha enviado un correo de recuperación a tu email.'); window.location.href = '../login.php';</script>";
    } else {
        echo "<script>alert('El correo electrónico no está registrado.'); window.location.href = '../login.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
