<?php
$page_title = "Perfil - Admin";
require_once('../header.php');

$consulta = obtenerUsuarios();
$consulta .= " WHERE usuarios.doc = $id";

try {
    $stmt = $con->prepare($consulta);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo "<p>Usuario no encontrado.</p>";
        exit;
    }
} catch (PDOException $e) {
    echo "<p>Error al obtener los datos: " . $e->getMessage() . "</p>";
    exit;
}
?>

<body>
    <div class="form-wrapper">
        <div class="form-container">
            <form id="formPerfil" method="post" action="actualizarPerfil.php">
                <h1 class="form-header">Perfil de Usuario</h1>
                <div class="container">
                    <div class="row">
                        <!-- Primera columna -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom_usu" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nom_usu" name="nom_usu" value="<?php echo $usuario['nom_usu']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $usuario['email']; ?>" readonly>
                            </div>
                        </div>

                        <!-- Segunda columna -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <input type="text" class="form-control" id="estado" name="estado" value="<?php echo $usuario['nom_estado']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="rol" class="form-label">Rol</label>
                                <input type="text" class="form-control" id="rol" name="rol" value="<?php echo $usuario['nom_rol']; ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-primary w-50" id="btnEditar" onclick="habilitarEdicion()">Editar</button>
                        <button type="submit" class="btn btn-success w-50 d-none" id="btnGuardar">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
    function habilitarEdicion() {
        // Campos que deben habilitarse
        const camposEditables = ['nom_usu', 'email'];

        // Habilitar los campos editables
        camposEditables.forEach(id => {
            const campo = document.getElementById(id);
            if (campo) {
                campo.readOnly = false;
            }
        });

        // Mostrar y ocultar botones
        document.getElementById('btnEditar').classList.add('d-none');
        document.getElementById('btnGuardar').classList.remove('d-none');
    }
    </script>
</body>
</html>
