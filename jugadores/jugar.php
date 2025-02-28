<?php
$page_title = "Jugar - Jugador";
require_once('header.php');
$db = new Database();
$con = $db->conectar();

$id_usuario = $_SESSION['doc'];
$nivel = $con->query("SELECT id_nivel FROM usuarios_niveles WHERE id_usuario = $id_usuario")->fetch(PDO::FETCH_ASSOC)['id_nivel'];

$mundos = $con->query("SELECT id_mundo, nom_mundo, img FROM mundos WHERE id_mundo <= $nivel")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Mundo</title>
    <style>
        .mundo-card {
            cursor: pointer;
            border: 2px solid transparent;
        }
        .mundo-card:hover {
            border-color: #00eaff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Seleccionar Mundo</h1>
        <div class="row">
            <?php foreach ($mundos as $mundo): ?>
                <div class="col-md-4 mb-3">
                    <div class="card mundo-card" onclick="location.href='salas.php?id_mundo=<?php echo $mundo['id_mundo']; ?>'">
                        <img src="<?php echo BASE_URL . $mundo['img']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($mundo['nom_mundo']); ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($mundo['nom_mundo']); ?></h5>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
