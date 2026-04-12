<?php
require_once __DIR__ . '/../config/database.php';

class ProductosController {
    
    // Obtener todos los productos
    public static function getAll() {
        $conn = getConnection();
        $stmt = $conn->query("
            SELECT p.*, c.nombre_categoria, col.nombre_color, col.codigo_hex, t.nombre_tipo 
            FROM productos p 
            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
            LEFT JOIN colores col ON p.id_color = col.id_color
            LEFT JOIN tipos_material t ON p.id_tipo = t.id_tipo
            ORDER BY p.fecha_registro DESC
        ");
        return $stmt->fetchAll();
    }
    
    // Obtener productos activos (para catalogo publico)
    public static function getActive() {
        $conn = getConnection();
        $stmt = $conn->query("
            SELECT p.*, c.nombre_categoria, col.nombre_color, col.codigo_hex, t.nombre_tipo 
            FROM productos p 
            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
            LEFT JOIN colores col ON p.id_color = col.id_color
            LEFT JOIN tipos_material t ON p.id_tipo = t.id_tipo
            WHERE p.estado = 1
            ORDER BY p.fecha_registro DESC
        ");
        return $stmt->fetchAll();
    }
    
    // Obtener producto por ID
    public static function getById($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("
            SELECT p.*, c.nombre_categoria, col.nombre_color, col.codigo_hex, t.nombre_tipo 
            FROM productos p 
            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
            LEFT JOIN colores col ON p.id_color = col.id_color
            LEFT JOIN tipos_material t ON p.id_tipo = t.id_tipo
            WHERE p.id_producto = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Verificar si el producto ya existe
    public static function exists($nombre, $excludeId = null) {
        $conn = getConnection();
        if ($excludeId) {
            $stmt = $conn->prepare("SELECT id_producto FROM productos WHERE nombre_producto = ? AND id_producto != ?");
            $stmt->execute([$nombre, $excludeId]);
        } else {
            $stmt = $conn->prepare("SELECT id_producto FROM productos WHERE nombre_producto = ?");
            $stmt->execute([$nombre]);
        }
        return $stmt->fetch() !== false;
    }
    
    // Crear producto
    public static function create($data) {
        // Verificar duplicado
        if (self::exists($data['nombre_producto'])) {
            return ['success' => false, 'error' => 'Ya existe un producto con ese nombre'];
        }
        
        $conn = getConnection();
        $stmt = $conn->prepare("
            INSERT INTO productos (nombre_producto, descripcion, precio, stock, imagen, id_categoria, id_color, id_tipo, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['nombre_producto'],
            $data['descripcion'],
            $data['precio'],
            $data['stock'],
            $data['imagen'] ?? null,
            $data['id_categoria'] ?: null,
            $data['id_color'] ?: null,
            $data['id_tipo'] ?: null,
            $data['estado'] ?? 1
        ]);
        
        return ['success' => true, 'id' => $conn->lastInsertId()];
    }
    
    // Actualizar producto
    public static function update($id, $data) {
        // Verificar duplicado
        if (self::exists($data['nombre_producto'], $id)) {
            return ['success' => false, 'error' => 'Ya existe otro producto con ese nombre'];
        }
        
        $conn = getConnection();
        $stmt = $conn->prepare("
            UPDATE productos SET 
                nombre_producto = ?, descripcion = ?, precio = ?, stock = ?, 
                imagen = ?, id_categoria = ?, id_color = ?, id_tipo = ?, estado = ?
            WHERE id_producto = ?
        ");
        $stmt->execute([
            $data['nombre_producto'],
            $data['descripcion'],
            $data['precio'],
            $data['stock'],
            $data['imagen'],
            $data['id_categoria'] ?: null,
            $data['id_color'] ?: null,
            $data['id_tipo'] ?: null,
            $data['estado'],
            $id
        ]);
        
        return ['success' => true];
    }
    
    // Eliminar producto
    public static function delete($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("DELETE FROM productos WHERE id_producto = ?");
        $stmt->execute([$id]);
        return ['success' => true];
    }
    
    // Cambiar estado
    public static function toggleEstado($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("UPDATE productos SET estado = NOT estado WHERE id_producto = ?");
        $stmt->execute([$id]);
        return ['success' => true];
    }
}
