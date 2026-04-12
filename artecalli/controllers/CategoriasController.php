<?php
require_once __DIR__ . '/../config/database.php';

class CategoriasController {
    
    // Obtener todas las categorias
    public static function getAll() {
        $conn = getConnection();
        $stmt = $conn->query("SELECT * FROM categorias ORDER BY nombre_categoria");
        return $stmt->fetchAll();
    }
    
    // Obtener categorias activas
    public static function getActive() {
        $conn = getConnection();
        $stmt = $conn->query("SELECT * FROM categorias WHERE estado = 1 ORDER BY nombre_categoria");
        return $stmt->fetchAll();
    }
    
    // Obtener categoria por ID
    public static function getById($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM categorias WHERE id_categoria = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Verificar si la categoria ya existe
    public static function exists($nombre, $excludeId = null) {
        $conn = getConnection();
        if ($excludeId) {
            $stmt = $conn->prepare("SELECT id_categoria FROM categorias WHERE nombre_categoria = ? AND id_categoria != ?");
            $stmt->execute([$nombre, $excludeId]);
        } else {
            $stmt = $conn->prepare("SELECT id_categoria FROM categorias WHERE nombre_categoria = ?");
            $stmt->execute([$nombre]);
        }
        return $stmt->fetch() !== false;
    }
    
    // Crear categoria
    public static function create($data) {
        // Verificar duplicado
        if (self::exists($data['nombre_categoria'])) {
            return ['success' => false, 'error' => 'Ya existe una categoria con ese nombre'];
        }
        
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO categorias (nombre_categoria, descripcion, estado) VALUES (?, ?, ?)");
        $stmt->execute([
            $data['nombre_categoria'],
            $data['descripcion'],
            $data['estado'] ?? 1
        ]);
        
        return ['success' => true, 'id' => $conn->lastInsertId()];
    }
    
    // Actualizar categoria
    public static function update($id, $data) {
        // Verificar duplicado
        if (self::exists($data['nombre_categoria'], $id)) {
            return ['success' => false, 'error' => 'Ya existe otra categoria con ese nombre'];
        }
        
        $conn = getConnection();
        $stmt = $conn->prepare("UPDATE categorias SET nombre_categoria = ?, descripcion = ?, estado = ? WHERE id_categoria = ?");
        $stmt->execute([
            $data['nombre_categoria'],
            $data['descripcion'],
            $data['estado'],
            $id
        ]);
        
        return ['success' => true];
    }
    
    // Eliminar categoria
    public static function delete($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("DELETE FROM categorias WHERE id_categoria = ?");
        $stmt->execute([$id]);
        return ['success' => true];
    }
    
    // Cambiar estado
    public static function toggleEstado($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("UPDATE categorias SET estado = NOT estado WHERE id_categoria = ?");
        $stmt->execute([$id]);
        return ['success' => true];
    }
}
