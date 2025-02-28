<?php
$page_title = "Mundos - Admin";
require_once('header.php');
$db = new Database();
$con = $db->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nom_mundo = trim($_POST['nom_mundo']);
        $max_jugadores = intval($_POST['max_jugadores']);

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['imagen']['tmp_name'];
            $fileName = $_FILES['imagen']['name'];
            $fileSize = $_FILES['imagen']['size'];
            $fileType = $_FILES['imagen']['type'];

            $allowedTypes = ['image/jpeg'];
            if (!in_array($fileType, $allowedTypes)) {
                die(json_encode(['error' => 'Solo se permiten archivos JPG.']));
            }

            if ($fileSize > 5 * 1024 * 1024) {
                die(json_encode(['error' => 'El archivo no debe superar los 5 MB.']));
            }

            $uploadDir = '../img/mundos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $newFileName = uniqid() . '_' . $fileName;
            $destPath = $uploadDir . $newFileName;

            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                die(json_encode(['error' => 'Error al subir la imagen.']));
            }

            $imagen = $destPath;

            $stmt = $con->prepare("INSERT INTO mundos (nom_mundo, max_jugadores, img) VALUES (?, ?, ?)");
            $stmt->execute([$nom_mundo, $max_jugadores, $imagen]);

            echo '<script>alert("Mundo guardado correctamente.");</script>';
        } else {
            echo '<script>alert("Error al subir la imagen.");</script>';
        }
    } catch (Exception $e) {
        echo '<script>alert("Error en el servidor: ' . $e->getMessage() . '");</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Mundo</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Registrar Mundo</h1>
        <div class="card p-4 shadow-lg bg-white">
            <form id="registroMundoForm" enctype="multipart/form-data" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nom_mundo" class="form-label"><i class="fas fa-globe"></i> Nombre del Mundo</label><br>
                            <input type="text" class="form-control" id="nom_mundo" name="nom_mundo" required>
                        </div>
                        <div class="mb-3">
                            <label for="max_jugadores" class="form-label"><i class="fas fa-users"></i> Máximo de Jugadores</label><br>
                            <input type="number" class="form-control" id="max_jugadores" name="max_jugadores" required>
                        </div>
                        <div class="mb-3">
                            <label for="imagen">Subir Imagen (Solo JPG, máximo 5MB):</label>
                            <input class="form-control" type="file" id="imagen" name="imagen" accept=".jpg" required>
                            <div class="error-message" id="errorImagen"></div>
                        </div>
                    </div>
                    <input type="submit" value="Guardar">
            </form>
        </div>
    </div>
</body>
</html>
