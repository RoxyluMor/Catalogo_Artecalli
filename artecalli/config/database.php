<?php
// =============================================
// Configuracion de base de datos para XAMPP
// =============================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'artecalli_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Conexion PDO
function getConnection() {
    static $conn = null;
    
    if ($conn === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Error de conexion: " . $e->getMessage());
        }
    }
    
    return $conn;
}

// Iniciar sesion si no esta iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
