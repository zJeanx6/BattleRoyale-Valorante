<?php
class Database {
    
    private $db_host = 'localhost';
    private $db_user = 'root';
    private $db_pass = '';
    private $db_name = 'battle_royale';
    private $charset = 'utf8';
    
    function conectar() {
        try {
            $conexion = "mysql:host=" . $this->db_host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            // Configuración de las opciones de PDO
            $option = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Para mostrar errores de PDO
                PDO::ATTR_EMULATE_PREPARES => false           // Evitar que PDO emule consultas preparadas
            ];
            // Crear la conexión PDO
            $pdo = new PDO($conexion, $this->db_user, $this->db_pass, $option);
            return $pdo;
        } catch (PDOException $e) {
            // Mostrar el error si la conexión falla
            echo 'Error de Conexion: ' . $e->getMessage();
            exit;
        }
    }
}
?>