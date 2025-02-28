<?php
$page_title = "Avatars - Admin";
require_once('header.php');
$db = new Database();
$con = $db->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nom_avatar = trim($_POST['nom_avatar']);

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

            $uploadDir = '../img/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $newFileName = uniqid() . '_' . $fileName;
            $destPath = $uploadDir . $newFileName;

            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                die(json_encode(['error' => 'Error al subir la imagen.']));
            }

            $imagen = $destPath;

            $stmt = $con->prepare("INSERT INTO avatar (nom_avatar, img) VALUES (?, ?)");
            $stmt->execute([$nom_avatar, $imagen]);

            echo '<script>alert("Avatar guardado correctamente.");</script>';
        } else {
            echo '<script>alert("Error al subir la imagen.");</script>';
        }
    } catch (Exception $e) {
        echo '<script>alert("Error en el servidor: ' . $e->getMessage() . '");</script>';
    }
}
?>
<!DOCTYPE html>
    <body>
        <div class="container mt-5">
            <h1 class="text-center mb-4">Registrar Avatar</h1>
            <div class="card p-4 shadow-lg bg-white">
                <form id="registroAvatarForm" enctype="multipart/form-data" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom_avatar" class="form-label"><i class="fas fa-id-card"></i> Nombre del Avatar</label><br>
                                <input type="text" class="form-control" id="nom_avatar" name="nom_avatar" required>
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