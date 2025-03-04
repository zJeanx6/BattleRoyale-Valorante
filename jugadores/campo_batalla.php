<?php
require_once('../config/db_config.php');
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_GET['id_sala']);
$id_usuario = $_SESSION['doc'];

$sala = $con->query("SELECT nom_sala FROM salas WHERE id_sala = $id_sala")->fetch(PDO::FETCH_ASSOC);
$jugadores = $con->query("SELECT usuarios.nom_usu, avatar.img AS avatar FROM jugadores_salas INNER JOIN usuarios ON jugadores_salas.id_jugador = usuarios.doc INNER JOIN avatar ON usuarios.id_avatar = avatar.id_avatar WHERE jugadores_salas.id_sala = $id_sala AND usuarios.doc != $id_usuario")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campo de Batalla</title>
    <style>
        body {
            background-image: url('../img/mundos/67c0c6f16cf66_Breeze_loading_screen.jpg');
            background-size: cover;
        }
        .avatar {
            position: absolute;
            width: 50px;
            height: 50px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4"><?php echo htmlspecialchars($sala['nom_sala']); ?></h1>
        <?php foreach ($jugadores as $jugador): ?>
            <img src="<?php echo htmlspecialchars($jugador['avatar']); ?>" alt="<?php echo htmlspecialchars($jugador['nom_usu']); ?>" class="avatar" style="top: <?php echo rand(10, 90); ?>%; left: <?php echo rand(10, 90); ?>%;">
        <?php endforeach; ?>
    </div>
</body>
</html>
