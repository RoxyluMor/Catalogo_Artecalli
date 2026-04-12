<?php
require_once __DIR__ . '/../config/database.php';

class HelpersController {
    
    // Obtener todos los colores
    public static function getColores() {
        $conn = getConnection();
        $stmt = $conn->query("SELECT * FROM colores ORDER BY nombre_color");
        return $stmt->fetchAll();
    }
    
    // Obtener todos los tipos de material
    public static function getTipos() {
        $conn = getConnection();
        $stmt = $conn->query("SELECT * FROM tipos_material ORDER BY nombre_tipo");
        return $stmt->fetchAll();
    }
    
    // Subir imagen
    public static function uploadImage($file, $folder = 'productos') {
        $targetDir = __DIR__ . "/../public/uploads/{$folder}/";
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($extension, $allowedTypes)) {
            return ['success' => false, 'error' => 'Tipo de archivo no permitido'];
        }
        
        $fileName = uniqid() . '_' . time() . '.' . $extension;
        $targetPath = $targetDir . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => true, 'path' => "uploads/{$folder}/{$fileName}"];
        }
        
        return ['success' => false, 'error' => 'Error al subir el archivo'];
    }
}
