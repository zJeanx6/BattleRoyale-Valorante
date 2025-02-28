<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['id_rol']);
unset($_SESSION['id_estado']);
session_destroy();
session_write_close();
header("Location: ../login.php")
?>