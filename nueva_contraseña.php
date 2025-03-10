<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];

    // Conexión a la base de datos
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Verificar si el token es válido y no ha expirado
    $sql = "SELECT id_usuario FROM recuperacion_contrasena WHERE token_recuperacion = ? AND token_expiracion > NOW() AND esta_usado = FALSE";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_usuario);
        $stmt->fetch();
    } else {
        echo "El token es inválido o ha expirado.";
        exit;
    }

    $stmt->close();
    $conn->close();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['token']) && isset($_POST['password'])) {
    $token = $_POST['token'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Conexión a la base de datos
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Verificar si el token es válido y no ha expirado
    $sql = "SELECT id_usuario FROM recuperacion_contrasena WHERE token_recuperacion = ? AND token_expiracion > NOW() AND esta_usado = FALSE";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_usuario);
        $stmt->fetch();

        // Actualizar la contraseña del usuario
        $sql = "UPDATE usuarios SET contra = ? WHERE doc = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $password, $id_usuario);
        $stmt->execute();

        // Marcar el token como usado
        $sql = "UPDATE recuperacion_contrasena SET esta_usado = TRUE WHERE token_recuperacion = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();

        echo "<script>alert('Tu contraseña ha sido actualizada exitosamente.'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('El token es inválido o ha expirado.'); window.location.href = 'login.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Solicitud inválida.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #121212;
        color: white;
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        overflow: hidden;
    }

    .video-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
    }

    .recovery-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 90%;
        max-width: 400px;
        background: rgba(30, 30, 30, 0.9);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
    }

    .recovery-container h2 {
        margin-bottom: 20px;
    }

    .input-group {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    label {
        font-size: 14px;
        margin-bottom: 5px;
    }

    input {
        background: #333;
        border: none;
        padding: 10px;
        color: white;
        width: 100%;
        border-radius: 5px;
        outline: none;
    }

    .btn-submit {
        background: red;
        border: none;
        padding: 10px;
        width: 100%;
        border-radius: 5px;
        cursor: pointer;
        color: white;
        font-size: 16px;
    }

    .help-text {
        color: white;
        font-size: 12px;
        margin-top: 10px;
    }
    </style>
</head>
<body>
    <video class="video-background" autoplay loop muted>
        <source src="img/Videos/login.mp4" type="video/mp4">
        Tu navegador no soporta videos.
    </video>

    <div class="recovery-container">
        <h2 class="text-center mb-4">Nueva Contraseña</h2>
        <form action="nueva_contraseña.php" method="post">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="mb-3">
                <label for="password" class="form-label">Nueva Contraseña:</label>
                <input class="form-control" type="password" name="password" id="password" placeholder="Ingrese su nueva contraseña" required>
            </div>
            <button type="submit" class="btn btn-submit w-100">Actualizar Contraseña</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
