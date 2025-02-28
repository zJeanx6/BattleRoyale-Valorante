<?php
$page_title = "Armas - Admin";
require_once('header.php');
$db = new Database();
$con = $db->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nom_arma = trim($_POST['nom_arma']);
        $balas = intval($_POST['balas']);
        $id_tipo_arma = intval($_POST['id_tipo_arma']);

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

            $uploadDir = '../img/armas/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $newFileName = uniqid() . '_' . $fileName;
            $destPath = $uploadDir . $newFileName;

            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                die(json_encode(['error' => 'Error al subir la imagen.']));
            }

            $imagen = $destPath;

            $stmt = $con->prepare("INSERT INTO armas (nom_arma, balas, img, id_tipo_arma) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nom_arma, $balas, $imagen, $id_tipo_arma]);

            echo '<script>alert("Arma guardada correctamente.");</script>';
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
    <title>Registrar Arma</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Registrar Arma</h1>
        <div class="card p-4 shadow-lg bg-white">
            <form id="registroArmaForm" enctype="multipart/form-data" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nom_arma" class="form-label"><i class="fas fa-id-card"></i> Nombre del Arma</label><br>
                            <input type="text" class="form-control" id="nom_arma" name="nom_arma" required>
                        </div>
                        <div class="mb-3">
                            <label for="balas" class="form-label"><i class="fas fa-bullet"></i> Balas</label><br>
                            <input type="number" class="form-control" id="balas" name="balas" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_tipo_arma" class="form-label"><i class="fas fa-gun"></i> Tipo de Arma</label><br>
                            <select class="form-control" id="id_tipo_arma" name="id_tipo_arma" required>
                                <?php
                                try {
                                    $stmt_tipo_arma = $con->prepare("SELECT id_tip_arma, nom_tip_arma FROM tipos_armas");
                                    $stmt_tipo_arma->execute();
                                    $tipos_armas = $stmt_tipo_arma->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($tipos_armas as $tipo_arma) {
                                ?>
                                        <option value="<?php echo $tipo_arma['id_tip_arma']; ?>">
                                            <?php echo htmlspecialchars($tipo_arma['nom_tip_arma']); ?>
                                        </option>
                                <?php
                                    }
                                } catch (Exception $e) {
                                    echo "<option>Error al cargar tipos de armas</option>";
                                }
                                ?>
                            </select>
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
