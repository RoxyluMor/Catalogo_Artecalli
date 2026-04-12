<?php
require_once __DIR__ . '/../config/database.php';

class UsuariosController {
    
    // Obtener todos los usuarios
    public static function getAll() {
        $conn = getConnection();
        $stmt = $conn->query("SELECT id_usuario, nombre, usuario, rol, estado, fecha_creacion FROM usuarios ORDER BY fecha_creacion DESC");
        return $stmt->fetchAll();
    }
    
    // Obtener usuario por ID
    public static function getById($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT id_usuario, nombre, usuario, rol, estado, fecha_creacion FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Crear usuario
    public static function create($data) {
        $conn = getConnection();
        
        // Verificar si el usuario ya existe
        $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE usuario = ?");
        $stmt->execute([$data['usuario']]);
        if ($stmt->fetch()) {
            return ['success' => false, 'error' => 'El usuario ya existe'];
        }
        
        $hash = password_hash($data['contrasena'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, usuario, contrasena, rol, estado) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nombre'],
            $data['usuario'],
            $hash,
            $data['rol'] ?? 'Administrador',
            $data['estado'] ?? 1
        ]);
        
        return ['success' => true, 'id' => $conn->lastInsertId()];
    }
    
    // Actualizar usuario
    public static function update($id, $data) {
        $conn = getConnection();
        
        // Verificar si el usuario ya existe (excluyendo el actual)
        $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE usuario = ? AND id_usuario != ?");
        $stmt->execute([$data['usuario'], $id]);
        if ($stmt->fetch()) {
            return ['success' => false, 'error' => 'El usuario ya existe'];
        }
        
        if (!empty($data['contrasena'])) {
            $hash = password_hash($data['contrasena'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, usuario = ?, contrasena = ?, estado = ? WHERE id_usuario = ?");
            $stmt->execute([$data['nombre'], $data['usuario'], $hash, $data['estado'], $id]);
        } else {
            $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, usuario = ?, estado = ? WHERE id_usuario = ?");
            $stmt->execute([$data['nombre'], $data['usuario'], $data['estado'], $id]);
        }
        
        return ['success' => true];
    }
    
    // Eliminar usuario
    public static function delete($id) {
        $conn = getConnection();
        
        // Verificar que no sea el ultimo admin
        $stmt = $conn->query("SELECT COUNT(*) FROM usuarios");
        if ($stmt->fetchColumn() <= 1) {
            return ['success' => false, 'error' => 'No puedes eliminar el último administrador'];
        }
        
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id]);
        return ['success' => true];
    }
}
