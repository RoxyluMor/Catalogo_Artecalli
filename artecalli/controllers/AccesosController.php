<?php
require_once __DIR__ . '/../config/database.php';

class AccesosController {
    
    // Obtener todos los registros de acceso
    public static function getAll() {
        $conn = getConnection();
        $stmt = $conn->query("
            SELECT r.*, u.nombre as usuario_nombre 
            FROM registro_accesos r 
            LEFT JOIN usuarios u ON r.id_usuario = u.id_usuario 
            ORDER BY r.fecha_hora DESC
            LIMIT 100
        ");
        return $stmt->fetchAll();
    }
    
    // Eliminar registro
    public static function delete($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("DELETE FROM registro_accesos WHERE id_registro = ?");
        $stmt->execute([$id]);
        return ['success' => true];
    }
    
    // Limpiar todos los registros
    public static function clearAll() {
        $conn = getConnection();
        $conn->exec("TRUNCATE TABLE registro_accesos");
        return ['success' => true];
    }
}
