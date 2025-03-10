<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
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
        <h2 class="text-center mb-4">Recuperar Contraseña</h2>
        <form action="include/procesar_recuperacion.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input class="form-control" type="email" name="email" id="email" placeholder="Ingrese su Email" required>
            </div>
            <button type="submit" class="btn btn-submit w-100">Enviar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
