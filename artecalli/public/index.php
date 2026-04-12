<?php
require_once __DIR__ . '/../controllers/ProductosController.php';
require_once __DIR__ . '/../controllers/CategoriasController.php';
require_once __DIR__ . '/../controllers/HelpersController.php';

$basePath = '/artecalli/public';
$pageTitle = 'Artecalli - Ónix y Mármol';

// Obtener datos
$productos = ProductosController::getActive();
$categorias = CategoriasController::getActive();
$colores = HelpersController::getColores();
$tipos = HelpersController::getTipos();

// Obtener precio máximo para el slider
$precioMax = 0;
foreach ($productos as $p) {
    if ($p['precio'] > $precioMax) $precioMax = $p['precio'];
}
$precioMax = ceil($precioMax / 1000) * 1000;
if ($precioMax < 1000) $precioMax = 10000;

// Limitar a los 8 productos más recientes
$productosRecientes = array_slice($productos, 0, 8);

ob_start();
?>

<!-- Hero Section -->
<section id="inicio" class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container position-relative">
        <div class="row min-vh-100 align-items-center justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-3 fw-bold text-white mb-4 hero-title">Artecalli - Ónix y Mármol</h1>
                <p class="lead text-white-50 mb-5">Descubre piezas únicas elaboradas a mano por nuestros artesanos con más de 25 años de tradición</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="#catalogo" class="btn btn-light btn-lg px-4">Ver Catálogo</a>
                    <a href="https://wa.me/527421234567" target="_blank" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-whatsapp me-2"></i>WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Catalogo Section -->
<section id="catalogo" class="catalogo-section py-5">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <span class="text-uppercase text-muted small fw-semibold letter-spacing">Nuestras Creaciones</span>
            <h2 class="display-4 fw-bold section-title mt-2">Catálogo de Artesanías</h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">Explora nuestra colección de piezas únicas elaboradas en mármol y ónix. Cada producto es tallado a mano con dedicación y artesanal.</p>
        </div>
        
        <!-- Barra de búsqueda y filtros -->
        <div class="search-filter-container mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-lg-9">
                    <div class="search-box">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" id="searchInput" class="form-control search-input" placeholder="Buscar productos...">
                    </div>
                </div>
                <div class="col-lg-3">
                    <button class="btn btn-outline-dark w-100 filter-toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#filtrosCollapse">
                        <i class="bi bi-sliders me-2"></i>Filtros
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Panel de filtros colapsable -->
        <div class="collapse show" id="filtrosCollapse">
            <div class="filtros-panel p-4 mb-4">
                <div class="row g-4 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label text-uppercase small fw-semibold text-muted">Categoría</label>
                        <select id="filterCategoria" class="form-select">
                            <option value="">Todas</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['id_categoria']; ?>"><?php echo htmlspecialchars($cat['nombre_categoria']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-uppercase small fw-semibold text-muted">Color</label>
                        <select id="filterColor" class="form-select">
                            <option value="">Todos</option>
                            <?php foreach ($colores as $color): ?>
                                <option value="<?php echo $color['id_color']; ?>"><?php echo htmlspecialchars($color['nombre_color']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-uppercase small fw-semibold text-muted">Material</label>
                        <select id="filterMaterial" class="form-select">
                            <option value="">Todos</option>
                            <?php foreach ($tipos as $tipo): ?>
                                <option value="<?php echo $tipo['id_tipo']; ?>"><?php echo htmlspecialchars($tipo['nombre_tipo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-uppercase small fw-semibold text-muted">Precio máximo: $<span id="precioMaxLabel"><?php echo number_format($precioMax, 0); ?></span></label>
                        <input type="range" class="form-range" id="filterPrecio" min="0" max="<?php echo $precioMax; ?>" value="<?php echo $precioMax; ?>" step="100">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contador de resultados -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <p class="text-muted mb-0"><span id="resultCount"><?php echo count($productosRecientes); ?></span> productos encontrados</p>
            <button id="btnLimpiarFiltros" class="btn btn-sm btn-outline-secondary" style="display: none;">
                <i class="bi bi-x-circle me-1"></i>Limpiar filtros
            </button>
        </div>
        
        <!-- Productos Grid -->
        <div class="row g-4" id="productos-grid">
            <?php if (empty($productos)): ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="text-muted mt-3">No hay productos disponibles</p>
                </div>
            <?php else: ?>
                <?php foreach ($productos as $index => $producto): ?>
                    <div class="col-sm-6 col-lg-4 producto-card" 
                         data-categoria="<?php echo $producto['id_categoria']; ?>"
                         data-color="<?php echo $producto['id_color']; ?>"
                         data-tipo="<?php echo $producto['id_tipo']; ?>"
                         data-precio="<?php echo $producto['precio']; ?>"
                         data-nombre="<?php echo strtolower(htmlspecialchars($producto['nombre_producto'])); ?>"
                         data-index="<?php echo $index; ?>"
                         style="<?php echo $index >= 8 ? 'display: none;' : ''; ?>">
                        <div class="card h-100 border-0 product-card">
                            <div class="card-img-wrapper">
                                <?php if ($producto['imagen']): ?>
                                    <img src="<?php echo $basePath . '/' . $producto['imagen']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
                                <?php else: ?>
                                    <div class="placeholder-img d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted display-4"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <span class="categoria-label"><?php echo strtoupper(htmlspecialchars($producto['nombre_categoria'] ?? 'Sin categoría')); ?></span>
                                <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars(substr($producto['descripcion'], 0, 80)); ?>...</p>
                                <button type="button" class="btn btn-ver-detalles" 
                                    onclick='verDetalles(<?php echo json_encode([
                                        "id" => $producto["id_producto"],
                                        "nombre" => $producto["nombre_producto"],
                                        "descripcion" => $producto["descripcion"],
                                        "precio" => $producto["precio"],
                                        "stock" => $producto["stock"],
                                        "imagen" => $producto["imagen"] ? $basePath . "/" . $producto["imagen"] : null,
                                        "categoria" => $producto["nombre_categoria"] ?? "Sin categoría",
                                        "tipo" => $producto["nombre_tipo"] ?? "No especificado",
                                        "color" => $producto["nombre_color"] ?? "No especificado",
                                        "colorHex" => $producto["codigo_hex"] ?? "#cccccc"
                                    ], JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                                    Ver detalles
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Mensaje sin resultados -->
        <div id="noResults" class="text-center py-5" style="display: none;">
            <i class="bi bi-search display-1 text-muted"></i>
            <h4 class="mt-3 text-muted">No se encontraron productos</h4>
            <p class="text-muted">Intenta con otros filtros de búsqueda</p>
        </div>
    </div>
</section>

<!-- Nosotros Section -->
<section id="nosotros" class="nosotros-section py-5">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <span class="text-uppercase text-muted small fw-semibold letter-spacing">Nuestra Historia</span>
            <h2 class="display-4 fw-bold section-title mt-2">Sobre Artecalli</h2>
        </div>
        
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <!-- Carrusel -->
                <div id="carouselNosotros" class="carousel slide carousel-custom" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselNosotros" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#carouselNosotros" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#carouselNosotros" data-bs-slide-to="2"></button>
                    </div>
                    <div class="carousel-inner rounded-4 overflow-hidden">
                        <div class="carousel-item active">
                            <img src="<?php echo $basePath; ?>/assets/images/carousel/workshop-1.jpg" class="d-block w-100" alt="Taller 1">
                        </div>
                        <div class="carousel-item">
                            <img src="<?php echo $basePath; ?>/assets/images/carousel/workshop-2.jpg" class="d-block w-100" alt="Taller 2">
                        </div>
                        <div class="carousel-item">
                            <img src="<?php echo $basePath; ?>/assets/images/carousel/workshop-3.jpg" class="d-block w-100" alt="Taller 3">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselNosotros" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselNosotros" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </div>
            
            <div class="col-lg-6">
                <h3 class="h2 fw-bold mb-4 section-title" style="font-size: 1.75rem;">Tradición y Arte</h3>
                <p class="text-muted mb-4">Artecalli es un taller artesanal dedicado a la creación de piezas únicas en ónix y mármol. Con más de dos décadas de experiencia, nuestros artesanos transforman bloques de piedra en obras de arte funcionales y decorativas.</p>
                <p class="text-muted mb-4">Cada pieza es tallada a mano con técnicas tradicionales heredadas de generación en generación, combinadas con un toque moderno para el diseño. Nuestro compromiso es ofrecer productos de la más alta calidad que celebren la riqueza cultural mexicana.</p>
                
                <!-- Info Cards -->
                <div class="row g-3">
                    <div class="col-6">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Ubicación</h6>
                                <p class="text-muted small mb-0">Carretera Tecali-Tepeaca km 3.5 Puebla, México</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Horario</h6>
                                <p class="text-muted small mb-0">Lun - Vie: 9-17h | Sab: 9-14h</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="bi bi-whatsapp"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">WhatsApp</h6>
                                <a href="https://wa.me/527421234567" class="text-muted small text-decoration-none">+52 1 234 567 890</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="bi bi-facebook"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Correo</h6>
                                <a href="#" class="text-muted small text-decoration-none">ventasartecalli@hotmail.com</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal de Detalles del Producto -->
<div class="modal fade" id="productoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content producto-modal">
            <button type="button" class="btn-close-modal" data-bs-dismiss="modal" aria-label="Cerrar">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="modal-body p-0">
                <h4 id="modalNombre" class="modal-producto-titulo"></h4>
                
                <div class="modal-producto-imagen">
                    <img id="modalImagen" src="" alt="Producto">
                </div>
                
                <p id="modalDescripcion" class="modal-producto-descripcion"></p>
                
                <div class="modal-producto-tags">
                    <span class="tag" id="tagColor"><span class="tag-label">Color:</span> <span id="modalColor"></span></span>
                    <span class="tag" id="tagMaterial"><span class="tag-label">Material:</span> <span id="modalTipo"></span></span>
                    <span class="tag" id="tagCategoria"><span id="modalCategoria"></span></span>
                </div>
                
                <div class="modal-producto-footer">
                    <div class="modal-precio" id="modalPrecio"></div>
                    <div class="modal-stock" id="modalStock"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Datos de productos para JavaScript -->
<script>
const todosLosProductos = <?php echo json_encode($productos); ?>;
const basePath = '<?php echo $basePath; ?>';
const totalProductos = <?php echo count($productos); ?>;

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterCategoria = document.getElementById('filterCategoria');
    const filterColor = document.getElementById('filterColor');
    const filterMaterial = document.getElementById('filterMaterial');
    const filterPrecio = document.getElementById('filterPrecio');
    const precioMaxLabel = document.getElementById('precioMaxLabel');
    const resultCount = document.getElementById('resultCount');
    const btnLimpiar = document.getElementById('btnLimpiarFiltros');
    const noResults = document.getElementById('noResults');
    const productosGrid = document.getElementById('productos-grid');
    
    let filtrosActivos = false;
    
    function aplicarFiltros() {
        const busqueda = searchInput.value.toLowerCase().trim();
        const categoria = filterCategoria.value;
        const color = filterColor.value;
        const material = filterMaterial.value;
        const precioMax = parseFloat(filterPrecio.value);
        
        filtrosActivos = busqueda !== '' || categoria !== '' || color !== '' || material !== '' || precioMax < parseFloat(filterPrecio.max);
        btnLimpiar.style.display = filtrosActivos ? 'inline-block' : 'none';
        
        const cards = document.querySelectorAll('.producto-card');
        let visibles = 0;
        
        cards.forEach((card, index) => {
            const cardCategoria = card.dataset.categoria;
            const cardColor = card.dataset.color;
            const cardTipo = card.dataset.tipo;
            const cardPrecio = parseFloat(card.dataset.precio);
            const cardNombre = card.dataset.nombre;
            
            let mostrar = true;
            
            // Filtro de búsqueda
            if (busqueda && !cardNombre.includes(busqueda)) {
                mostrar = false;
            }
            
            // Filtro de categoría
            if (categoria && cardCategoria !== categoria) {
                mostrar = false;
            }
            
            // Filtro de color
            if (color && cardColor !== color) {
                mostrar = false;
            }
            
            // Filtro de material
            if (material && cardTipo !== material) {
                mostrar = false;
            }
            
            // Filtro de precio
            if (cardPrecio > precioMax) {
                mostrar = false;
            }
            
            // Si no hay filtros activos, mostrar solo los primeros 8
            if (!filtrosActivos && index >= 8) {
                mostrar = false;
            }
            
            if (mostrar) {
                card.style.display = '';
                card.style.opacity = '0';
                setTimeout(() => {
                    card.style.transition = 'opacity 0.3s ease';
                    card.style.opacity = '1';
                }, 50);
                visibles++;
            } else {
                card.style.display = 'none';
            }
        });
        
        resultCount.textContent = visibles;
        noResults.style.display = visibles === 0 ? 'block' : 'none';
        productosGrid.style.display = visibles === 0 ? 'none' : 'flex';
    }
    
    // Event listeners para filtros en tiempo real
    searchInput.addEventListener('input', aplicarFiltros);
    filterCategoria.addEventListener('change', aplicarFiltros);
    filterColor.addEventListener('change', aplicarFiltros);
    filterMaterial.addEventListener('change', aplicarFiltros);
    filterPrecio.addEventListener('input', function() {
        precioMaxLabel.textContent = parseInt(this.value).toLocaleString();
        aplicarFiltros();
    });
    
    // Limpiar filtros
    btnLimpiar.addEventListener('click', function() {
        searchInput.value = '';
        filterCategoria.value = '';
        filterColor.value = '';
        filterMaterial.value = '';
        filterPrecio.value = filterPrecio.max;
        precioMaxLabel.textContent = parseInt(filterPrecio.max).toLocaleString();
        aplicarFiltros();
    });
});

function verDetalles(producto) {
    document.getElementById('modalNombre').textContent = producto.nombre;
    document.getElementById('modalDescripcion').textContent = producto.descripcion || 'Sin descripción disponible';
    document.getElementById('modalCategoria').textContent = producto.categoria;
    document.getElementById('modalTipo').textContent = producto.tipo;
    document.getElementById('modalColor').textContent = producto.color;
    document.getElementById('modalPrecio').textContent = '$' + parseFloat(producto.precio).toLocaleString('es-MX', {minimumFractionDigits: 0, maximumFractionDigits: 0}) + ' MXN';
    
    const imgEl = document.getElementById('modalImagen');
    if (producto.imagen) {
        imgEl.src = producto.imagen;
    } else {
        imgEl.src = basePath + '/assets/images/placeholder.jpg';
    }
    
    const stockEl = document.getElementById('modalStock');
    if (producto.stock > 0) {
        stockEl.innerHTML = '<i class="bi bi-check-circle me-1"></i>' + producto.stock + ' en stock';
        stockEl.className = 'modal-stock stock-disponible';
    } else {
        stockEl.innerHTML = '<i class="bi bi-x-circle me-1"></i>Agotado';
        stockEl.className = 'modal-stock stock-agotado';
    }
    
    const modal = new bootstrap.Modal(document.getElementById('productoModal'));
    modal.show();
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../views/layouts/main.php';
?>
