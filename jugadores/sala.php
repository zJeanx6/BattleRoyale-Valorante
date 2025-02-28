<?php
$page_title = "Sala - Jugador";
require_once('header.php');
$db = new Database();
$con = $db->conectar();

$id_usuario = $_SESSION['doc'];
$id_sala = intval($_GET['id_sala']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['abandonar'])) {
    try {
        $con->beginTransaction();
        $con->prepare("DELETE FROM jugadores_salas WHERE id_jugador = ? AND id_sala = ?")->execute([$id_usuario, $id_sala]);
        $con->prepare("UPDATE salas SET jugadores_actuales = jugadores_actuales - 1 WHERE id_sala = ?")->execute([$id_sala]);
        $con->commit();

        echo '<script>alert("Has abandonado la sala."); window.location.href = "salas.php?id_mundo=' . $_GET['id_mundo'] . '";</script>';
    } catch (Exception $e) {
        $con->rollBack();
        echo '<script>alert("Error en el servidor: ' . $e->getMessage() . '");</script>';
    }
}

$sala = $con->query("SELECT nom_sala, jugadores_actuales, max_jugadores FROM salas WHERE id_sala = $id_sala")->fetch(PDO::FETCH_ASSOC);
$jugadores = $con->query("SELECT usuarios.nom_usu FROM jugadores_salas INNER JOIN usuarios ON jugadores_salas.id_jugador = usuarios.doc WHERE jugadores_salas.id_sala = $id_sala")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sala</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4"><?php echo htmlspecialchars($sala['nom_sala']); ?></h1>
        <div class="card p-4 shadow-lg bg-white">
            <h5 class="card-title">Jugadores en la sala: <?php echo $sala['jugadores_actuales']; ?>/<?php echo $sala['max_jugadores']; ?></h5>
            <ul class="list-group list-group-flush">
                <?php foreach ($jugadores as $jugador): ?>
                    <li class="list-group-item"><?php echo htmlspecialchars($jugador['nom_usu']); ?></li>
                <?php endforeach; ?>
            </ul>
            <form method="POST" class="mt-3">
                <button type="submit" name="abandonar" class="btn btn-danger">Abandonar Sala</button>
            </form>
        </div>
    </div>
</body>
</html>
