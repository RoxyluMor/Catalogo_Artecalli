<?php
require_once __DIR__ . '/../../controllers/AuthController.php';

$basePath = '/artecalli/public';

// Si ya hay admin, redirigir a login
if (AuthController::hasAdmin()) {
    header('Location: ' . $basePath . '/admin/login.php');
    exit;
}

$errors = [];

// Procesar registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';
    
    // Validaciones
    if (empty($nombre)) $errors['nombre'] = 'El nombre es requerido';
    if (empty($usuario)) $errors['usuario'] = 'El usuario es requerido';
    if (strlen($contrasena) < 6) $errors['contrasena'] = 'La contraseña debe tener al menos 6 caracteres';
    if ($contrasena !== $confirmar) $errors['confirmar'] = 'Las contraseñas no coinciden';
    
    if (empty($errors)) {
        $result = AuthController::register($nombre, $usuario, $contrasena);
        if ($result['success']) {
            $_SESSION['flash_success'] = 'Cuenta creada exitosamente. Ahora puedes iniciar sesión.';
            header('Location: ' . $basePath . '/admin/login.php');
            exit;
        } else {
            $errors['usuario'] = $result['error'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta Admin - Artecalli</title>
    <link rel="icon" href="<?php echo $basePath; ?>/assets/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="<?php echo $basePath; ?>/assets/css/admin.css" rel="stylesheet">
</head>
<body class="bg-dark min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <a href="<?php echo $basePath; ?>/" class="d-flex align-items-center gap-2 text-white text-decoration-none mb-4">
                    <i class="bi bi-arrow-left"></i> Volver al inicio
                </a>
                
                <div class="card bg-secondary-dark border-secondary">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <img src="<?php echo $basePath; ?>/assets/images/logo.png" alt="Artecalli" width="64" height="64" class="rounded-circle mb-3">
                            <h4 class="text-white mb-1">Crear Cuenta Admin</h4>
                            <p class="text-muted small">Configura tus credenciales de administrador</p>
                        </div>
                        
                        <form method="POST" novalidate>
                            <div class="mb-3">
                                <label for="nombre" class="form-label text-white-50">Nombre completo</label>
                                <input type="text" class="form-control bg-dark text-white border-secondary <?php echo isset($errors['nombre']) ? 'is-invalid' : ''; ?>" 
                                       id="nombre" name="nombre" value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" placeholder="Tu nombre">
                                <?php if (isset($errors['nombre'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['nombre']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="usuario" class="form-label text-white-50">Usuario</label>
                                <input type="text" class="form-control bg-dark text-white border-secondary <?php echo isset($errors['usuario']) ? 'is-invalid' : ''; ?>" 
                                       id="usuario" name="usuario" value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>" placeholder="nombre_usuario">
                                <?php if (isset($errors['usuario'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['usuario']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="contrasena" class="form-label text-white-50">Contraseña</label>
                                <input type="password" class="form-control bg-dark text-white border-secondary <?php echo isset($errors['contrasena']) ? 'is-invalid' : ''; ?>" 
                                       id="contrasena" name="contrasena" placeholder="Mínimo 6 caracteres">
                                <?php if (isset($errors['contrasena'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['contrasena']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-4">
                                <label for="confirmar" class="form-label text-white-50">Confirmar contraseña</label>
                                <input type="password" class="form-control bg-dark text-white border-secondary <?php echo isset($errors['confirmar']) ? 'is-invalid' : ''; ?>" 
                                       id="confirmar" name="confirmar" placeholder="Repite la contraseña">
                                <?php if (isset($errors['confirmar'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['confirmar']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <button type="submit" class="btn btn-light w-100">Crear Cuenta</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
