<?php
$page_title = "Crear Sala - Admin";
require_once('header.php');
$db = new Database();
$con = $db->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nom_sala = trim($_POST['nom_sala']);
        $jugadores_actuales = 0;
        $max_jugadores = intval($_POST['max_jugadores']);
        $id_mundo = intval($_POST['id_mundo']);
        $id_nivel = intval($_POST['id_nivel']);
        $duracion_segundos = intval($_POST['duracion_segundos']);
        $id_estado_sala = 1; // Estado activo

        $stmt = $con->prepare("INSERT INTO salas (nom_sala, jugadores_actuales, max_jugadores, id_mundo, id_nivel, duracion_segundos, id_estado_sala) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom_sala, $jugadores_actuales, $max_jugadores, $id_mundo, $id_nivel, $duracion_segundos, $id_estado_sala]);

        echo '<script>alert("Sala creada correctamente.");</script>';
    } catch (Exception $e) {
        echo '<script>alert("Error en el servidor: ' . $e->getMessage() . '");</script>';
    }
}

$mundos = $con->query("SELECT id_mundo, nom_mundo FROM mundos")->fetchAll(PDO::FETCH_ASSOC);
$niveles = $con->query("SELECT id_nivel, nom_nivel FROM niveles")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Sala</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Crear Sala</h1>
        <div class="card p-4 shadow-lg bg-white">
            <form id="crearSalaForm" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nom_sala" class="form-label"><i class="fas fa-id-card"></i> Nombre de la Sala</label><br>
                            <input type="text" class="form-control" id="nom_sala" name="nom_sala" required>
                        </div>
                        <div class="mb-3">
                            <label for="max_jugadores" class="form-label"><i class="fas fa-users"></i> Máximo de Jugadores</label><br>
                            <input type="number" class="form-control" id="max_jugadores" name="max_jugadores" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_mundo" class="form-label"><i class="fas fa-globe"></i> Mundo</label><br>
                            <select class="form-control" id="id_mundo" name="id_mundo" required>
                                <?php foreach ($mundos as $mundo): ?>
                                    <option value="<?php echo $mundo['id_mundo']; ?>">
                                        <?php echo htmlspecialchars($mundo['nom_mundo']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_nivel" class="form-label"><i class="fas fa-level-up-alt"></i> Nivel</label><br>
                            <select class="form-control" id="id_nivel" name="id_nivel" required>
                                <?php foreach ($niveles as $nivel): ?>
                                    <option value="<?php echo $nivel['id_nivel']; ?>">
                                        <?php echo htmlspecialchars($nivel['nom_nivel']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="duracion_segundos" class="form-label"><i class="fas fa-clock"></i> Duración (segundos)</label><br>
                            <input type="number" class="form-control" id="duracion_segundos" name="duracion_segundos" required>
                        </div>
                    </div>
                    <input type="submit" value="Crear Sala" class="btn btn-primary">
            </form>
        </div>
    </div>
</body>
</html>
