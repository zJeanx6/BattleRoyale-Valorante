<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riot Games Login</title>
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

    .login-container {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        width: 90%;
        max-width: 900px;
        background: rgba(30, 30, 30, 0.9);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
    }

    .login-box {
        flex: 1;
        padding: 20px;
        text-align: center;
        max-width: 400px;
        width: 100%;
    }

    .login-box h2 {
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

    input.is-invalid {
        border: 1px solid red;
    }

    input.is-valid {
        border: 1px solid green;
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

    @media (max-width: 768px) {
        .login-container {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .login-box {
            width: 90%;
        }
        .login-image {
            max-width: 100%;
        }
    }
    </style>
</head>
<body>
    <video class="video-background" autoplay loop muted>
        <source src="img/Videos/login.mp4" type="video/mp4">
        Tu navegador no soporta videos.
    </video>

    <div class="login-container">
        <div class="login-box">
            <h2 class="text-center mb-4">Inicio De Sesión</h2>
            <form action="include/loginRedirec.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="username" class="form-label">Nombre de Usuario:</label>
                    <input class="form-control" type="text" name="username" id="username" placeholder="Ingrese su Nombre de Usuario" required>
                </div>
                <div class="mb-3">
                    <label for="pass" class="form-label">Contraseña:</label>
                    <input class="form-control" type="password" name="pass" id="pass" placeholder="Ingrese su Contraseña" required>
                </div>
                <button type="submit" name="enviar" class="btn btn-submit w-100">Ingresar</button>
                <p class="help-text">No estas registrado? <a href="register_user.php">Crear Cuenta</a></p>
                <div class="text-center">
                    <a href="recuperar_contraseña.php">¿Olvidaste tu contraseña?</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
