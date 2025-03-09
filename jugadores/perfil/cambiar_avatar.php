<?php
require_once('../header.php');
$usuario = $con->query("SELECT id_avatar FROM usuarios WHERE doc = $id_usuario")->fetch(PDO::FETCH_ASSOC);
$avatars = $con->query("SELECT id_avatar, nom_avatar, img FROM avatar")->fetchAll(PDO::FETCH_ASSOC);
$avatar_actual = $usuario['id_avatar'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_avatar = intval($_POST['id_avatar']);

        $stmt = $con->prepare("UPDATE usuarios SET id_avatar = ? WHERE doc = ?");
        $stmt->execute([$id_avatar, $id_usuario]);

        echo '<script>alert("Avatar cambiado con éxito.");</script>';
        echo "<script> window.location = '../index.php' </script>";
        $avatar_actual = $id_avatar;
    } catch (Exception $e) {
        echo '<script>alert("Error en el servidor: ' . $e->getMessage() . '");</script>';
    }
}

?>
<head>
    <title>Cambiar Avatar</title>
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

        .avatar-card {
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            transform: translateY(0);
        }

        .avatar-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 234, 255, 0.3);
        }

        .avatar-card.selected {
            border-color: var(--secondary-color);
            box-shadow: 0 0 20px rgba(0, 234, 255, 0.5);
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
            border-bottom: 2px solid var(--secondary-color);
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
        <h1 class="text-center">Cambiar Avatar</h1>
        <div class="card p-4 shadow-lg">
            <form id="cambiarAvatarForm" method="POST">
                <div class="row">
                    <?php foreach ($avatars as $avatar): ?>
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="card avatar-card <?php echo $avatar['id_avatar'] == $avatar_actual ? 'selected' : ''; ?>" data-avatar-id="<?php echo $avatar['id_avatar']; ?>">
                                <img src="../<?php echo $avatar['img']; ?>">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo ($avatar['nom_avatar']); ?></h5>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="id_avatar" id="id_avatar" value="<?php echo $avatar_actual; ?>">
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Cambiar Avatar</button>
                    <a href="../index.php" class="btn btn-primary">Volver</a>
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