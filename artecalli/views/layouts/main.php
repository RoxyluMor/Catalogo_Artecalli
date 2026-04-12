<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Artecalli Catalogo Web'; ?></title>
    <meta name="description" content="Catálogo de artesanías de mármol y ónix hechas a mano por artesanos mexicanos.">
    <link rel="icon" href="<?php echo $basePath; ?>/assets/images/logo.png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo $basePath; ?>/assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo $basePath; ?>/">
                <img src="<?php echo $basePath; ?>/assets/images/logo.png" alt="Artecalli" width="40" height="40" class="rounded-circle">
                <span class="brand-text">ARTECALLI</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $basePath; ?>/#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $basePath; ?>/#catalogo">Catálogo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $basePath; ?>/#nosotros">Nosotros</a>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link admin-btn" href="<?php echo $basePath; ?>/admin/">
                            <i class="bi bi-person-gear fs-5"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?php echo $content; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5" id="contacto">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="<?php echo $basePath; ?>/assets/images/logo.png" alt="Artecalli" width="40" height="40" class="rounded-circle">
                        <span class="h5 mb-0" style="font-family: 'Playfair Display', serif;">ARTECALLI</span>
                    </div>
                    <p class="text-secondary small">Artesanías de mármol hechas a mano por maestros artesanos mexicanos. Calidad, tradición y belleza en cada pieza.</p>
                </div>
                
                <div class="col-lg-4">
                    <h6 class="text-uppercase mb-3 fw-semibold small" style="letter-spacing: 1px;">Navegación</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo $basePath; ?>/#inicio" class="text-secondary text-decoration-none small">Inicio</a></li>
                        <li class="mb-2"><a href="<?php echo $basePath; ?>/#catalogo" class="text-secondary text-decoration-none small">Catálogo</a></li>
                        <li class="mb-2"><a href="<?php echo $basePath; ?>/#nosotros" class="text-secondary text-decoration-none small">Nosotros</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4">
                    <h6 class="text-uppercase mb-3 fw-semibold small" style="letter-spacing: 1px;">Redes Sociales</h6>
                    <ul class="list-unstyled text-secondary small">
                        <li class="mb-2">
                            <a href="https://wa.me/527421234567" class="text-secondary text-decoration-none d-flex align-items-center gap-2">
                                <i class="bi bi-whatsapp"></i>
                                WhatsApp: +52 1 234 567 890
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-secondary text-decoration-none d-flex align-items-center gap-2">
                                <i class="bi bi-facebook"></i>
                                Facebook: /artecalli
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4 border-secondary opacity-25">
            
            <p class="text-center text-secondary small mb-0">
                &copy; <?php echo date('Y'); ?> Artecalli. Todos los derechos reservados.
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo $basePath; ?>/assets/js/main.js"></script>
</body>
</html>
