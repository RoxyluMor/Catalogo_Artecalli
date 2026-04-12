<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Panel de Administrador'; ?> - Artecalli</title>
    <link rel="icon" href="<?php echo $basePath; ?>/assets/images/logo.png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo $basePath; ?>/assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="sidebar bg-dark text-white" id="sidebar">
            <div class="sidebar-header p-3 border-bottom border-secondary">
                <a href="<?php echo $basePath; ?>/" class="d-flex align-items-center gap-2 text-decoration-none text-white">
                    <img src="<?php echo $basePath; ?>/assets/images/logo.png" alt="Artecalli" width="36" height="36" class="rounded-circle">
                    <span class="brand-text">Admin</span>
                </a>
            </div>
            
            <nav class="sidebar-nav p-3">
                <ul class="nav flex-column gap-1">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'productos' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>/admin/productos.php">
                            <i class="bi bi-box-seam me-2"></i> Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'categorias' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>/admin/categorias.php">
                            <i class="bi bi-tags me-2"></i> Categorías
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'accesos' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>/admin/accesos.php">
                            <i class="bi bi-clock-history me-2"></i> Registro de Accesos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'usuarios' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>/admin/usuarios.php">
                            <i class="bi bi-people me-2"></i> Administradores
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer p-3 border-top border-secondary mt-auto">
                <a href="<?php echo $basePath; ?>/admin/logout.php" class="nav-link text-danger">
                    <i class="bi bi-box-arrow-left me-2"></i> Cerrar Sesión
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <header class="admin-header bg-white border-bottom p-3 d-flex align-items-center justify-content-between">
                <button class="btn btn-outline-dark d-lg-none" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="h5 mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-grid"></i>
                    <?php echo $pageTitle ?? 'Dashboard'; ?>
                </h1>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">Hola, <?php echo $_SESSION['admin_nombre'] ?? 'Admin'; ?></span>
                </div>
            </header>
            
            <main class="p-4">
                <?php if (isset($_SESSION['flash_success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['flash_error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php echo $content; ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Admin JS -->
    <script src="<?php echo $basePath; ?>/assets/js/admin.js"></script>
</body>
</html>
