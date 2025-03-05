<?php
// Configuración de la conexión a MySQL
$host = 'localhost';
$dbname = 'ejercicio_ajax';
$username = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Validar y procesar los datos del formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $placa = trim($_POST['placa']);
        $cedula = trim($_POST['cedula']);
        $tipo_vehiculo = trim($_POST['tipo_vehiculo']);
        $id_color = trim($_POST['id_color']);
        $marca = trim($_POST['marca']);

        // Validar la imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['imagen']['tmp_name'];
            $fileName = $_FILES['imagen']['name'];
            $fileSize = $_FILES['imagen']['size'];
            $fileType = $_FILES['imagen']['type'];

            // Verificar el tipo de archivo y el tamaño
            $allowedTypes = ['image/jpeg'];
            if (!in_array($fileType, $allowedTypes)) {
                die(json_encode(['error' => 'Solo se permiten archivos JPG.']));
            }

            if ($fileSize > 200 * 1024) { // 200 KB
                die(json_encode(['error' => 'El archivo no debe superar los 200 KB.']));
            }

            // Guardar la imagen en una carpeta
            $uploadDir = 'uploads/';

            
            //si la carpeta uploads no existe la crea y le otorga todos los permisos con el 0777 true

            //uniqid() : Esta función genera un identificador único basado en la hora actual (microsegundos). Es útil para evitar conflictos de nombres de archivo.
            //Ejemplo de salida: '64b5f3e9c8f1a'

            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $newFileName = uniqid() . '_' . $fileName;
            $destPath = $uploadDir . $newFileName;

            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                die(json_encode(['error' => 'Error al subir la imagen.']));
            }

            $imagen = $destPath;

            // Insertar los datos en la base de datos
            $stmt = $pdo->prepare("INSERT INTO vehiculo (placa, cedula, id_tipo_vehiculo, id_color, id_marca, img_vehiculo) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$placa, $cedula, $tipo_vehiculo, $id_color, $marca, $imagen]);

            echo json_encode(['message' => 'Datos guardados correctamente.']);
        } else {
            echo json_encode(['error' => 'Error al subir la imagen.']);
        }
    } else {
        echo json_encode(['error' => 'Método no permitido.']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>