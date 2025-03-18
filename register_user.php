<?php
require_once('config/db_config.php');
require_once('send_email.php');
$conex = new Database;
$con = $conex->conectar();
session_start();
$estado = 2;
$id_rol = 2;
$id_nivel = 1;
$id_avatar = 3;
$armas = [1, 2];

if (isset($_POST['enviar'])) {
    $nom_usu = $_POST['nom_usu'];
    $contra = $_POST['contra'];
    $contra2 = $_POST['contra2'];
    $email = $_POST['email'];

    $sql = $con->prepare("SELECT * FROM usuarios WHERE nom_usu = ?");
    $sql->execute([$nom_usu]);
    $fila = $sql->fetchAll(PDO::FETCH_ASSOC);

    if ($fila) {
        echo '<script>alert("Nombre de usuario existente")</script>';
    } else {
        $contra_en = password_hash($contra, PASSWORD_DEFAULT, array('cost' => 12));

        if ($nom_usu == "" || $contra == "" || $email == "") {
            echo '<script>alert("Existen datos vacíos")</script>';
        } else {
            $con->beginTransaction();
            $insert = $con->prepare("INSERT INTO usuarios(nom_usu, contra, email, id_avatar, id_rol, id_estado) 
                                    VALUES (?, ?, ?, ?, ?, ?)");
            $insert->execute([$nom_usu, $contra_en, $email, $id_avatar, $id_rol, $estado]);
            $id_usuario = $con->lastInsertId();

            $insert_nivel = $con->prepare("INSERT INTO usuarios_niveles(id_usuario, id_nivel) VALUES (?, ?)");
            $insert_nivel->execute([$id_usuario, $id_nivel]);

            foreach ($armas as $id_arma) {
                $insert_arma = $con->prepare("INSERT INTO jugadores_armas(id_jugador, id_arma) VALUES (?, ?)");
                $insert_arma->execute([$id_usuario, $id_arma]);
            }

            $con->commit();

            // Enviar correo de notificación de registro
            $subject = "Registro Exitoso";
            $body = "Hola $nom_usu, gracias por registrarte en nuestra aplicación. Tu cuenta está pendiente de activación.
            ";
            sendEmail($email, $subject, $body);

            echo '<script>alert("Registro exitoso, Te hemos enviado un correo electronico con más informacion...")</script>';
            echo '<script> window.location= "register_user.php" </script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
    <!-- <link rel="stylesheet" href="css/stylesR.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> 
    body {
        background-color: #121212;
        color: white;
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        background-image: url('img/registro.jpg');
        background-size: cover;
        background-position: center;
    }

    .register-container {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        width: 90%;
        max-width: 900px;
        background: rgba(30, 30, 30, 0.9);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
    }

    .register-box {
        flex: 1;
        padding: 20px;
        text-align: center;
        max-width: 400px;
        width: 100%;
    }

    .register-box h2 {
        margin-bottom: 20px;
    }

    .input-group {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    label {
        font-size: 14px;
        margin-bottom: 5px;
    }

    input {
        background: #333;
        border: none;
        padding: 10px;
        color: white;
        width: 100%;
        border-radius: 5px;
        outline: none;
    }

    input.is-invalid {
        border: 1px solid red;
    }

    input.is-valid {
        border: 1px solid green;
    }

    .btn-submit {
        background: red;
        border: none;
        padding: 10px;
        width: 100%;
        border-radius: 5px;
        cursor: pointer;
        color: white;
        font-size: 16px;
    }

    .btn-submit_1 {
        background: red;
        border: none;
        padding: 10px;
        width: 100%;
        border-radius: 5px;
        cursor: pointer;
        color: white;
        font-size: 16px;
    }


    .invalid-feedback {
        color: red;
        font-size: 12px;
        display: none;
    }

    @media (max-width: 768px) {
        .register-container {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .register-box {
            width: 90%;
        }
        .register-image {
            max-width: 100%;
        }
    }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-box">
            <h2 class="text-center mb-4">Registro</h2>
            <form id="form__registerUser" action="register_user.php" method="POST">
                <div class="mb-3">
                    <label for="nom_usu" class="form-label">Nombre de Usuario:</label>
                    <input type="text" class="form-control" name="nom_usu" id="nom_usu" placeholder="Ingrese su nombre de usuario">
                    <div class="invalid-feedback">El nombre de usuario debe tener entre 4 y 20 caracteres. 
                    Y solo se adminten letras y numeros, mayúsculas y minúsculas.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Ingrese su email">
                    <div class="invalid-feedback">Ingrese un email válido.</div>
                </div>
                <div class="mb-3">
                    <label for="contra" class="form-label">Contraseña:</label>
                    <input type="password" class="form-control" name="contra" id="contra" placeholder="Ingrese su contraseña">
                    <div class="invalid-feedback">La contraseña debe tener entre 8 y 12 caracteres.
                        Y solo se adminten letras y numeros, mayúsculas y minúsculas.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="contra2" class="form-label">Confirmar Contraseña:</label>
                    <input type="password" class="form-control" name="contra2" id="contra2" placeholder="Confirme su contraseña">
                    <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                </div>
                <button class="btn btn-submit w-100" type="submit" name="enviar">Registrar</button>
                <div class="text-center">
                    <a href="login.php">ya tienes una cuenta? inicar sesion.</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/validacion_form_register.js"></script>
</body>
</html>

