<?php
require_once('header.php');
$db = new Database();
$con = $db->conectar();

$id_usuario = $_SESSION['doc'];
$armas_actuales = $con->query("SELECT id_arma FROM jugadores_armas WHERE id_jugador = $id_usuario")->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $armas_seleccionadas = $_POST['id_arma'];

        if (count($armas_seleccionadas) > 2) {
            echo '<script>alert("Solo puedes equipar un máximo de dos armas.");</script>';
        } else {
            $con->beginTransaction();
            $con->prepare("DELETE FROM jugadores_armas WHERE id_jugador = ?")->execute([$id_usuario]);

            $stmt = $con->prepare("INSERT INTO jugadores_armas (id_jugador, id_arma) VALUES (?, ?)");
            foreach ($armas_seleccionadas as $id_arma) {
                $stmt->execute([$id_usuario, $id_arma]);
            }

            $con->commit();
            echo '<script>alert("Armamento cambiado con éxito.");</script>';
            $armas_actuales = $armas_seleccionadas;
        }
    } catch (Exception $e) {
        $con->rollBack();
        echo '<script>alert("Error en el servidor: ' . $e->getMessage() . '");</script>';
    }
}

$armas = $con->query("SELECT id_arma, nom_arma, img FROM armas")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Armamento</title>
    <style>
        .arma-card {
            cursor: pointer;
            border: 2px solid transparent;
        }
        .arma-card.selected {
            border-color: #00eaff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Cambiar Armamento</h1>
        <div class="card p-4 shadow-lg bg-white">
            <form id="cambiarArmamentoForm" method="POST">
                <div class="row">
                    <?php foreach ($armas as $arma): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card arma-card <?php echo in_array($arma['id_arma'], $armas_actuales) ? 'selected' : ''; ?>" data-arma-id="<?php echo $arma['id_arma']; ?>">
                                <img src="<?php echo BASE_URL . $arma['img']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($arma['nom_arma']); ?>">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo htmlspecialchars($arma['nom_arma']); ?></h5>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="id_arma[]" id="id_arma_1" value="<?php echo $armas_actuales[0] ?? ''; ?>">
                <input type="hidden" name="id_arma[]" id="id_arma_2" value="<?php echo $armas_actuales[1] ?? ''; ?>">
                <div class="text-center">
                    <input type="submit" value="Cambiar Armamento" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
    <script>
        document.querySelectorAll('.arma-card').forEach(card => {
            card.addEventListener('click', function() {
                if (this.classList.contains('selected')) {
                    this.classList.remove('selected');
                    if (document.getElementById('id_arma_1').value == this.getAttribute('data-arma-id')) {
                        document.getElementById('id_arma_1').value = '';
                    } else {
                        document.getElementById('id_arma_2').value = '';
                    }
                } else {
                    if (document.getElementById('id_arma_1').value == '') {
                        document.getElementById('id_arma_1').value = this.getAttribute('data-arma-id');
                        this.classList.add('selected');
                    } else if (document.getElementById('id_arma_2').value == '') {
                        document.getElementById('id_arma_2').value = this.getAttribute('data-arma-id');
                        this.classList.add('selected');
                    } else {
                        alert('Solo puedes equipar un máximo de dos armas.');
                    }
                }
            });
        });
    </script>
</body>
</html>
