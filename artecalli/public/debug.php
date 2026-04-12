<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/database.php';

echo "<h2>Diagnóstico de Artecalli</h2>";

// 1. Verificar conexión
echo "<h3>1. Conexión a MySQL</h3>";
try {
    $conn = getConnection();
    echo "<p style='color:green'>✓ Conexión exitosa a la base de datos 'artecalli'</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>✗ Error: " . $e->getMessage() . "</p>";
    exit;
}

// 2. Verificar tabla usuarios
echo "<h3>2. Tabla usuarios</h3>";
try {
    $stmt = $conn->query("SELECT COUNT(*) as total FROM usuarios");
    $count = $stmt->fetch()['total'];
    echo "<p>Total de usuarios: <strong>$count</strong></p>";
    
    if ($count > 0) {
        $stmt = $conn->query("SELECT id_usuario, nombre, usuario, contraseña, estado FROM usuarios");
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Usuario</th><th>Hash (primeros 20 chars)</th><th>Estado</th></tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . $row['id_usuario'] . "</td>";
            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($row['usuario']) . "</td>";
            echo "<td>" . substr($row['contraseña'], 0, 20) . "...</td>";
            echo "<td>" . ($row['estado'] ? 'Activo' : 'Inactivo') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>✗ Error: " . $e->getMessage() . "</p>";
}

// 3. Verificar hash de contraseña
echo "<h3>3. Verificar contraseña</h3>";
if ($count > 0) {
    $stmt = $conn->query("SELECT usuario, contraseña FROM usuarios LIMIT 1");
    $user = $stmt->fetch();
    
    echo "<p>Probando contraseña 'admin123' para usuario '{$user['usuario']}'...</p>";
    
    if (password_verify('admin123', $user['contraseña'])) {
        echo "<p style='color:green'>✓ La contraseña 'admin123' es CORRECTA</p>";
    } else {
        echo "<p style='color:red'>✗ La contraseña 'admin123' NO coincide con el hash almacenado</p>";
        echo "<p>Hash almacenado: <code>" . htmlspecialchars($user['contraseña']) . "</code></p>";
        
        // Generar hash correcto
        $hashCorrecto = password_hash('admin123', PASSWORD_DEFAULT);
        echo "<h4>Solución:</h4>";
        echo "<p>Ejecuta este SQL en phpMyAdmin para corregir la contraseña:</p>";
        echo "<pre style='background:#f0f0f0;padding:10px;'>UPDATE usuarios SET contraseña = '$hashCorrecto' WHERE usuario = '{$user['usuario']}';</pre>";
    }
} else {
    echo "<p style='color:orange'>No hay usuarios. Ve a <a href='register.php'>register.php</a> para crear uno.</p>";
}

// 4. Crear usuario de prueba
echo "<h3>4. Crear usuario de prueba</h3>";
if (isset($_GET['crear'])) {
    $nombre = 'Admin Test';
    $usuario = 'test';
    $hash = password_hash('test123', PASSWORD_DEFAULT);
    
    try {
        // Eliminar si existe
        $conn->exec("DELETE FROM usuarios WHERE usuario = 'test'");
        
        // Crear nuevo
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, usuario, contraseña, rol, estado) VALUES (?, ?, ?, 'Administrador', 1)");
        $stmt->execute([$nombre, $usuario, $hash]);
        
        echo "<p style='color:green'>✓ Usuario creado: <strong>test</strong> / <strong>test123</strong></p>";
    } catch (Exception $e) {
        echo "<p style='color:red'>✗ Error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p><a href='?crear=1'>Clic aquí para crear usuario de prueba (test / test123)</a></p>";
}

echo "<hr><p><a href='admin/login.php'>Ir al login</a></p>";
