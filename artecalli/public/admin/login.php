<?php
require_once __DIR__ . '/../../controllers/AuthController.php';

$basePath = '/artecalli/public';

// Si ya esta autenticado, redirigir
if (AuthController::isAuthenticated()) {
    header('Location: ' . $basePath . '/admin/productos.php');
    exit;
}

// Si no hay admin, redirigir a registro
if (!AuthController::hasAdmin()) {
    header('Location: ' . $basePath . '/admin/register.php');
    exit;
}

$error = '';

// Procesar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    
    if (empty($usuario) || empty($contrasena)) {
        $error = 'Todos los campos son requeridos';
    } else {
        $result = AuthController::login($usuario, $contrasena);
        if ($result['success']) {
            header('Location: ' . $basePath . '/admin/productos.php');
            exit;
        } else {
            $error = $result['Correo o contraseña incorrectos'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Artecalli Admin</title>
    <link rel="icon" href="<?php echo $basePath; ?>/assets/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="<?php echo $basePath; ?>/assets/css/admin.css" rel="stylesheet">
</head>
<body class="bg-dark min-vh-100 d-flex align-items-center justify-content-center">
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
                            <h4 class="text-white mb-1">Panel de Administrador</h4>
                            <p class="text-muted small">Ingresa tus credenciales para continuar</p>
                        </div>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger py-2"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" novalidate>
                            <div class="mb-3">
                                <label for="usuario" class="form-label text-white-50">Usuario</label>
                                <input type="text" class="form-control bg-dark text-white border-secondary" id="usuario" name="usuario" 
                                       value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>" placeholder="Tu usuario" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="contrasena" class="form-label text-white-50">Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control bg-dark text-white border-secondary" id="contrasena" name="contrasena" placeholder="Tu contraseña" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-light w-100">Iniciar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const input = document.getElementById('contrasena');
            const icon = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
</body>
</html>
