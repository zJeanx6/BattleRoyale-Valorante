<?php
$page_title = "Niveles - Admin";
require_once('header.php');
$db = new Database();
$con = $db->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nom_nivel = trim($_POST['nom_nivel']);
        $puntos_necesarios = intval($_POST['puntos_necesarios']);

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['imagen']['tmp_name'];
            $fileName = $_FILES['imagen']['name'];
            $fileSize = $_FILES['imagen']['size'];
            $fileType = $_FILES['imagen']['type'];

            $allowedTypes = ['image/png'];
            if (!in_array($fileType, $allowedTypes)) {
                die(json_encode(['error' => 'Solo se permiten archivos PNG.']));
            }

            if ($fileSize > 5 * 1024 * 1024) {
                die(json_encode(['error' => 'El archivo no debe superar los 5 MB.']));
            }

            $uploadDir = '../img/niveles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $newFileName = uniqid() . '_' . $fileName;
            $destPath = $uploadDir . $newFileName;

            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                die(json_encode(['error' => 'Error al subir la imagen.']));
            }

            $imagen = $destPath;

            $stmt = $con->prepare("INSERT INTO niveles (nom_nivel, puntos_necesarios, img) VALUES (?, ?, ?)");
            $stmt->execute([$nom_nivel, $puntos_necesarios, $imagen]);

            echo '<script>alert("Nivel guardado correctamente.");</script>';
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
    <title>Registrar Nivel</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Registrar Nivel</h1>
        <div class="card p-4 shadow-lg bg-white">
            <form id="registroNivelForm" enctype="multipart/form-data" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nom_nivel" class="form-label"><i class="fas fa-level-up-alt"></i> Nombre del Nivel</label><br>
                            <input type="text" class="form-control" id="nom_nivel" name="nom_nivel" required>
                        </div>
                        <div class="mb-3">
                            <label for="puntos_necesarios" class="form-label"><i class="fas fa-star"></i> Puntos Necesarios</label><br>
                            <input type="number" class="form-control" id="puntos_necesarios" name="puntos_necesarios" required>
                        </div>
                        <div class="mb-3">
                            <label for="imagen">Subir Imagen (Solo PNG, máximo 5MB):</label>
                            <input class="form-control" type="file" id="imagen" name="imagen" accept=".png" required>
                            <div class="error-message" id="errorImagen"></div>
                        </div>
                    </div>
                    <input type="submit" value="Guardar">
            </form>
        </div>
    </div>
</body>
</html>
