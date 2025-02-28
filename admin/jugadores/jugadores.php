<?php
$page_title = "Usuarios - Admin";
require_once('../header.php');
?>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Administrar Usuarios</h1>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Rol</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $sql = $con->prepare(obtenerUsuarios());
                    $sql->execute();
                    $filas = $sql->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($filas as $detalle) {
                ?>
                    <tr>
                        <td><?php echo $detalle['doc']; ?></td>
                        <td><?php echo $detalle['nom_usu']; ?></td>
                        <td><?php echo $detalle['email']; ?></td>
                        <td><?php echo $detalle['nom_estado']; ?></td>
                        <td><?php echo $detalle['nom_rol']; ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalActualizar"
                                    onclick="cargarDatosModal('<?php echo $detalle['doc']; ?>',
                                                              '<?php echo $detalle['nom_usu']; ?>',
                                                              '<?php echo $detalle['email']; ?>',
                                                              '<?php echo $detalle['id_estado']; ?>',
                                                              '<?php echo $detalle['id_rol']; ?>')"> Actualizar
                            </button>
                            <a class="btn btn-danger btn-sm" href="eliminarJugadores.php?id=<?php echo $detalle['doc']; ?>" onclick="return confirm('¿Desea eliminar el registro?')">Eliminar</a>
                        </td>
                    </tr>
                <?php
                    }
                } catch (Exception $e) {
                    echo "<tr><td colspan='8'>Error al cargar los datos: " . ($e->getMessage()) . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalActualizar" tabindex="-1" aria-labelledby="modalActualizarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="actualizarJugadores.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalActualizarLabel">Actualizar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id_oculto" name="id_oculto">
                        <div class="mb-3">
                            <label for="id" class="form-label">ID</label>
                            <input type="text" class="form-control" id="id" name="id" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <?php
                                try {
                                    $sql = obtenerEstadosRango($id_min = 1, $id_max = 3);
                                    $stmt_estado = $con->prepare($sql);
                                    $stmt_estado->bindParam(':id_min', $id_min, PDO::PARAM_INT);
                                    $stmt_estado->bindParam(':id_max', $id_max, PDO::PARAM_INT);
                                    $stmt_estado->execute();
                                    $estados = $stmt_estado->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($estados as $estado) {
                                ?>
                                        <option value="<?php echo $estado['id_estado']; ?>">
                                            <?php echo ($estado['nom_estado']); ?>
                                        </option>
                                <?php
                                    }
                                } catch (Exception $e) {
                                    echo "<option>Error al cargar estados: " . ($e->getMessage()) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="rol" class="form-label">Rol</label>
                            <select class="form-control" id="rol" name="rol" required>
                                <?php
                                try {
                                    $query_rol = "SELECT id_rol, nom_rol FROM roles";
                                    $stmt_rol = $con->prepare($query_rol);
                                    $stmt_rol->execute();
                                    $roles = $stmt_rol->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($roles as $rol) {
                                ?>
                                        <option value="<?php echo $rol['id_rol']; ?>">
                                            <?php echo ($rol['nom_rol']); ?>
                                        </option>
                                <?php
                                    }
                                } catch (Exception $e) {
                                    echo "<option>Error al cargar roles</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function cargarDatosModal(id, nombre, email, estado, rol) {
            document.getElementById('id_oculto').value = id;
            document.getElementById('id').value = id;
            document.getElementById('nombre').value = nombre;
            document.getElementById('email').value = email;
            document.getElementById('estado').value = estado;
            document.getElementById('rol').value = rol;
        }
    </script>
</body>
</html>
