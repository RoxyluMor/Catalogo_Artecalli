<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

class AuthController {
    
    // Verificar si hay admin registrado
    public static function hasAdmin() {
        $conn = getConnection();
        $stmt = $conn->query("SELECT COUNT(*) FROM usuarios");
        return $stmt->fetchColumn() > 0;
    }
    
    // Registrar nuevo admin
    public static function register($nombre, $usuario, $contrasena) {
        $conn = getConnection();
        
        // Verificar si el usuario ya existe
        $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        if ($stmt->fetch()) {
            return ['success' => false, 'error' => 'El usuario ya existe'];
        }
        
        // Crear usuario
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, usuario, contrasena, rol, estado) VALUES (?, ?, ?, 'Administrador', 1)");
        $stmt->execute([$nombre, $usuario, $hash]);
        
        return ['success' => true, 'id' => $conn->lastInsertId()];
    }
    
    // Iniciar sesión
    public static function login($usuario, $contrasena) {
        $conn = getConnection();
        
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ? AND estado = 1");
        $stmt->execute([$usuario]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($contrasena, $user['contrasena'])) {
            // Registrar acceso exitoso
            self::logAccess($user['id_usuario'], $user['nombre'], true);
            
            $_SESSION['admin_id'] = $user['id_usuario'];
            $_SESSION['admin_nombre'] = $user['nombre'];
            $_SESSION['admin_usuario'] = $user['usuario'];
            
            return ['success' => true, 'user' => $user];
        }
        
        // Registrar acceso fallido
        self::logAccess(null, $usuario, false);
        
        return ['success' => false, 'error' => 'Usuario o contraseña incorrectos'];
    }
    
    // Cerrar sesion
    public static function logout() {
        session_destroy();
        header('Location: /artecalli/public/admin/login.php');
        exit;
    }
    
    // Verificar si esta autenticado
    public static function isAuthenticated() {
        return isset($_SESSION['admin_id']);
    }
    
    // Registrar acceso (sin IP)
    private static function logAccess($userId, $nombreUsuario, $exito) {
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO registro_accesos (id_usuario, nombre_usuario, exito) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $nombreUsuario, $exito ? 1 : 0]);
    }
}
