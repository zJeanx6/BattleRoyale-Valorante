<?php
session_start();
require_once(__DIR__ . '/../config.php');
require_once(ROOT_PATH . '/config/db_config.php');
require_once(ROOT_PATH . '/include/consultas.php');
include (ROOT_PATH . '/include/validarSession.php');
$conex = new Database;  
$con = $conex->conectar();
$id = $_SESSION['doc'];
$sql = $con->prepare("SELECT usuarios.doc AS usuario_id, usuarios.nom_usu AS nom_usu, roles.id_rol AS rol_id, roles.nom_rol AS nom_rol, usuarios.id_estado AS id_estado FROM usuarios INNER JOIN roles ON usuarios.id_rol = roles.id_rol WHERE usuarios.doc = ? ");
$sql->execute([$id]);
$fila = $sql->fetch();

// Validar que el usuario sea administrador (id_rol = 1)
if ($fila['id_estado'] != 1 || $fila['rol_id'] != 1) {
    echo "<script>alert('No tiene el rol necesario para ingresar a esta ubicación.');</script>";
    echo "<script>window.location.href = '" . "../jugadores/index.php';</script>";
    exit();
}

// Actualizar la última sesión del usuario
$con->prepare("UPDATE usuarios SET ultima_sesion = CURRENT_TIMESTAMP WHERE doc = ?")->execute([$id]);
$page_title = isset($page_title) ? $page_title : "Sin Titulo...";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <!-- Usamos BASE_URL para la ruta de archivos públicos (CSS) -->
    <link rel="stylesheet" href="<?php echo BASE_URL . '/css/styles.css'; ?>">
    <!-- Bootstrap CSS desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <!-- Logo y Texto "Valorante" -->
            <!-- Usamos BASE_URL para la URL -->
            <a class="navbar-brand d-flex align-items-center" href="<?php echo BASE_URL . '/admin/index.php'; ?>">
                <img src="<?php echo BASE_URL . '/img/SolucionesTextilesSinFondo.png'; ?>" alt="Logo" class="img-fluid" style="max-height: 60px;">
                <span class="ms-2 fw-semibold">Valorante</span>
            </a>
            <!-- Boton de 3 Lineas Para Responsive-->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Menu De Opciones Banner -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Usamos BASE_URL para las URLs de los enlaces -->
                    <li class="nav-item"><a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL . '/admin/index.php'; ?>">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'jugadores.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL . '/admin/jugadores/jugadores.php'; ?>">Jugadores</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'crear_sala.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL . '/admin/crear_sala.php'; ?>">Crear Salas</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'avatars.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL . '/admin/avatars.php'; ?>">Avatars</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'armas.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL . '/admin/armas.php'; ?>">Armas</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'mundos.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL . '/admin/mundos.php'; ?>">Mundos</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'niveles.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL . '/admin/niveles.php'; ?>">Niveles</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo (basename($_SERVER['PHP_SELF']) == 'perfil.php') ? 'active' : ''; ?>" href="#" id="menuOpciones" role="button" data-bs-toggle="dropdown" aria-expanded="false">Configuración</a>
                        <ul class="dropdown-menu" aria-labelledby="menuOpciones">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL . '/admin/perfil/perfil.php'; ?>">Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL . '/include/exit.php'; ?>">Cerrar sesión</a></li>
                        </ul>
                    </li>   
                </ul>
                <span class="navbar-text">Bienvenido, <?php echo htmlspecialchars($fila['nom_usu']); ?> (<?php echo htmlspecialchars($fila['nom_rol']); ?>)</span>
            </div>
        </div>
    </nav>
</body>
