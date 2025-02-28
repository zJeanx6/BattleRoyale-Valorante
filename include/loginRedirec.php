<?php
session_start();
require_once('../config/db_config.php');
$conex = new Database;
$con = $conex->conectar();

if (isset($_POST['enviar'])) {
    $email = ($_POST['email']); 
    $pass = ($_POST['pass']); 

    if ($email == '' || $pass == '') {
        echo '<script>alert("Email o Contraseña Vacíos")</script>';
        echo '<script>window.location = "../login.php"</script>';
        exit();
    }

    $sql = $con->prepare("SELECT * FROM usuarios WHERE email = '$email'");
    $sql->execute();
    $fila = $sql->fetch();

    if ($fila && $fila['id_estado'] == 1 && password_verify($pass, $fila['contra'])) {
        $_SESSION['doc'] = $fila['doc'];  
        $_SESSION['id_rol'] = $fila['id_rol'];     
        $_SESSION['id_estado'] = $fila['id_estado']; 

        if ($_SESSION['id_rol'] == 1) { 
            header("location: ../admin/index.php");
            exit(); 
        }
        if ($_SESSION['id_rol'] == 2){
            header("location: ../jugadores/index.php");
            exit();
        }
        
    } else {
        echo '<script>alert("Credenciales Inválidas o Usuario Inactivo, consulte con soporte.")</script>';
        echo '<script>window.location = "../login.php"</script>';
        exit();
    }
}
?>
