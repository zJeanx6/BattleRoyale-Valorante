<?php
$page_title = "Cambiar Avatar - Jugador";
require_once('header.php');
$db = new Database();
$con = $db->conectar();

$id_usuario = $_SESSION['doc'];
$usuario = $con->query("SELECT id_avatar FROM usuarios WHERE doc = $id_usuario")->fetch(PDO::FETCH_ASSOC);
$avatar_actual = $usuario['id_avatar'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_avatar = intval($_POST['id_avatar']);

        $stmt = $con->prepare("UPDATE usuarios SET id_avatar = ? WHERE doc = ?");
        $stmt->execute([$id_avatar, $id_usuario]);

        echo '<script>alert("Avatar cambiado con éxito.");</script>';
        $avatar_actual = $id_avatar;
    } catch (Exception $e) {
        echo '<script>alert("Error en el servidor: ' . $e->getMessage() . '");</script>';
    }
}

$avatars = $con->query("SELECT id_avatar, nom_avatar, img FROM avatar")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Avatar</title>
    <style>
        .avatar-card {
            cursor: pointer;
            border: 2px solid transparent;
        }
        .avatar-card.selected {
            border-color: #00eaff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Cambiar Avatar</h1>
        <div class="card p-4 shadow-lg bg-white">
            <form id="cambiarAvatarForm" method="POST">
                <div class="row">
                    <?php foreach ($avatars as $avatar): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card avatar-card <?php echo $avatar['id_avatar'] == $avatar_actual ? 'selected' : ''; ?>" data-avatar-id="<?php echo $avatar['id_avatar']; ?>">
                                <img src="<?php echo BASE_URL . $avatar['img']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($avatar['nom_avatar']); ?>">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo htmlspecialchars($avatar['nom_avatar']); ?></h5>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="id_avatar" id="id_avatar" value="<?php echo $avatar_actual; ?>">
                <div class="text-center">
                    <input type="submit" value="Cambiar Avatar" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
    <script>
        document.querySelectorAll('.avatar-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.avatar-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('id_avatar').value = this.getAttribute('data-avatar-id');
            });
        });
    </script>
</body>
</html>
