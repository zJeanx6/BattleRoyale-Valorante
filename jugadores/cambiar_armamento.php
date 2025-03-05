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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ff4655;
            --secondary-color: #00eaff;
            --dark-bg: #1a1a2e;
            --card-bg: rgba(255, 255, 255, 0.1);
            --text-color: #ffffff;
        }

        body {
            font-family: 'Rajdhani', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--dark-bg) 0%, #16213e 100%);
            color: var(--text-color);
            min-height: 100vh;
        }

        .container {
            padding-top: 50px;
            padding-bottom: 50px;
        }

        h1 {
            color: var(--secondary-color);
            text-shadow: 0 0 10px rgba(0, 234, 255, 0.5);
            font-weight: 700;
            margin-bottom: 30px;
        }

        .card {
            background-color: var(--card-bg);
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .arma-card {
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            transform: translateY(0);
            position: relative;
        }

        .arma-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 234, 255, 0.3);
        }

        .arma-card.selected {
            border-color: var(--secondary-color);
            box-shadow: 0 0 20px rgba(0, 234, 255, 0.5);
        }

        .arma-card.selected::after {
            content: '\f00c';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            top: 10px;
            right: 10px;
            color: var(--secondary-color);
            font-size: 1.5rem;
        }

        .card-img-top {
            height: 200px;
            object-fit: contain;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
        }

        .card-body {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .card-title {
            color: var(--text-color);
            font-weight: 600;
        }

        .btn-primary {
            background: linear-gradient(to right, var(--primary-color), #ff6b78);
            border: none;
            border-radius: 25px;
            padding: 10px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, #ff6b78, var(--primary-color));
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(255, 70, 85, 0.3);
        }

        .armas-seleccionadas {
            background-color: rgba(0, 234, 255, 0.1);
            border-radius: 15px;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .arma-icon {
            font-size: 1.5rem;
            margin: 0 10px;
            color: var(--secondary-color);
        }

        @media (max-width: 768px) {
            .container {
                padding-top: 30px;
                padding-bottom: 30px;
            }

            h1 {
                font-size: 2rem;
            }

            .card-img-top {
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Cambiar Armamento</h1>
        <div class="armas-seleccionadas">
            <span>Armas seleccionadas:</span>
            <i class="fas fa-gun arma-icon" id="arma-icon-1"></i>
            <i class="fas fa-gun arma-icon" id="arma-icon-2"></i>
        </div>
        <div class="card p-4 shadow-lg">
            <form id="cambiarArmamentoForm" method="POST">
                <div class="row">
                    <?php foreach ($armas as $arma): ?>
                        <div class="col-md-4 col-sm-6 mb-4">
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
                    <button type="submit" class="btn btn-primary">Cambiar Armamento</button>
                    <a href="index.php" class="btn btn-primary">Volver</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        function actualizarIconosArmas() {
            const arma1 = document.getElementById('id_arma_1').value;
            const arma2 = document.getElementById('id_arma_2').value;
            document.getElementById('arma-icon-1').style.opacity = arma1 ? '1' : '0.3';
            document.getElementById('arma-icon-2').style.opacity = arma2 ? '1' : '0.3';
        }

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
                        return;
                    }
                }
                actualizarIconosArmas();
            });
        });

        actualizarIconosArmas();
    </script>
</body>
</html>