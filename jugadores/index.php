<?php 
session_start();
$page_title = "Inicio - Jugador";
require_once('header.php');
$db = new Database();
$con = $db->conectar();

$id_usuario = $_SESSION['doc'];
$usuario = $con->query("SELECT usuarios.nom_usu, usuarios.doc FROM usuarios WHERE usuarios.doc = $id_usuario")->fetch(PDO::FETCH_ASSOC);
$nivel = $con->query("SELECT niveles.nom_nivel, niveles.img AS nivel_img FROM usuarios_niveles INNER JOIN niveles ON usuarios_niveles.id_nivel = niveles.id_nivel WHERE usuarios_niveles.id_usuario = $id_usuario")->fetch(PDO::FETCH_ASSOC);
$avatar = $con->query("SELECT img FROM avatar WHERE id_avatar = (SELECT id_avatar FROM usuarios WHERE doc = $id_usuario)")->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<style>
    body {
        background-image: url('ruta/a/tu/imagen.jpg');
        background-size: cover;
        background-position: center;
        height: 100vh;
        color: white;
    }
    .btn-custom {
        background-color: #ff4655;
        border-color: #ff4655;
    }
    .btn-custom:hover {
        background-color: #e03e4e;
        border-color: #e03e4e;
    }
    .btn-avatar {
        background-color: #00eaff;
        border-color: #00eaff;
    }
    .btn-avatar:hover {
        background-color: #00c4d6;
        border-color: #00c4d6;
    }
    .profile-card {
        background-color: rgba(0, 0, 0, 0.7);
        padding: 20px;
        border-radius: 10px;
    }
    .avatar-img {
        max-width: 200px;
        border-radius: 50%;
        border: 5px solid #00eaff;
    }
</style>
<body>
    <div class="container-fluid h-100 d-flex flex-column justify-content-between">
        <div class="row mt-3">
            <div class="col-12 text-center">
                <img src="<?php echo BASE_URL . $avatar['img']; ?>" class="avatar-img" alt="Avatar">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <div class="profile-card">
                    <h2><?php echo htmlspecialchars($usuario['nom_usu']); ?></h2>
                    <img src="<?php echo BASE_URL . $nivel['nivel_img']; ?>" alt="Nivel" style="max-height: 50px;">
                    <p>ID: <?php echo htmlspecialchars($usuario['doc']); ?></p>
                    <a href="<?php echo BASE_URL . '/jugadores/perfil.php'; ?>" class="btn btn-custom">Ver Perfil</a>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <a href="<?php echo BASE_URL . '/jugadores/cambiar_avatar.php'; ?>" class="btn btn-avatar">Cambiar Avatar</a>
                <a href="<?php echo BASE_URL . '/jugadores/cambiar_armamento.php'; ?>" class="btn btn-avatar">Cambiar Armamento</a>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <div class="dropdown">
                    <a href="jugar.php" class="btn btn-custom">Jugar</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
