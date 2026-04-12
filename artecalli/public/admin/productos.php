<?php
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/ProductosController.php';
require_once __DIR__ . '/../../controllers/CategoriasController.php';
require_once __DIR__ . '/../../controllers/HelpersController.php';

$basePath = '/artecalli/public';

// Verificar autenticacion
if (!AuthController::isAuthenticated()) {
    header('Location: ' . $basePath . '/admin/login.php');
    exit;
}

$pageTitle = 'Gestión de Productos';
$currentPage = 'productos';

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create' || $action === 'update') {
        $data = [
            'nombre_producto' => trim($_POST['nombre_producto']),
            'descripcion' => trim($_POST['descripcion']),
            'precio' => floatval($_POST['precio']),
            'stock' => intval($_POST['stock']),
            'id_categoria' => $_POST['id_categoria'] ?: null,
            'id_color' => $_POST['id_color'] ?: null,
            'id_tipo' => $_POST['id_tipo'] ?: null,
            'estado' => isset($_POST['estado']) ? 1 : 0,
            'imagen' => $_POST['imagen_actual'] ?? null
        ];
        
        // Subir imagen si se proporciona
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $upload = HelpersController::uploadImage($_FILES['imagen'], 'productos');
            if ($upload['success']) {
                $data['imagen'] = $upload['path'];
            }
        }
        
        if ($action === 'create') {
            $result = ProductosController::create($data);
        } else {
            $result = ProductosController::update($_POST['id_producto'], $data);
        }
        
        if ($result['success']) {
            $_SESSION['flash_success'] = $action === 'create' ? 'Producto creado correctamente' : 'Producto actualizado correctamente';
        } else {
            $_SESSION['flash_error'] = $result['error'];
        }
    }
    
    if ($action === 'delete') {
        ProductosController::delete($_POST['id_producto']);
        $_SESSION['flash_success'] = 'Producto eliminado correctamente';
    }
    
    if ($action === 'toggle') {
        ProductosController::toggleEstado($_POST['id_producto']);
        $_SESSION['flash_success'] = 'Estado actualizado';
    }
    
    header('Location: ' . $basePath . '/admin/productos.php');
    exit;
}

// Obtener datos
$productos = ProductosController::getAll();
$categorias = CategoriasController::getAll();
$colores = HelpersController::getColores();
$tipos = HelpersController::getTipos();

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">Productos (<?php echo count($productos); ?>)</h2>
    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#productoModal" onclick="resetForm()">
        <i class="bi bi-plus-lg me-2"></i>Nuevo Producto
    </button>
</div>

<!-- Tabla de productos -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Color</th>
                    <th>Tipo</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($productos)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox display-4 d-block mb-2"></i>
                            No hay productos registrados
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($productos as $p): ?>
                        <tr>
                            <td>
                                <?php if ($p['imagen']): ?>
                                    <img src="<?php echo $basePath . '/' . $p['imagen']; ?>" alt="" width="48" height="48" class="rounded object-fit-cover">
                                <?php else: ?>
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width:48px;height:48px">
                                        <i class="bi bi-image text-white"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($p['nombre_producto']); ?></strong>
                                <br><small class="text-muted"><?php echo htmlspecialchars(substr($p['descripcion'], 0, 40)); ?>...</small>
                            </td>
                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($p['nombre_categoria'] ?? '-'); ?></span></td>
                            <td>
                                <?php if ($p['nombre_color']): ?>
                                    <span class="d-flex align-items-center gap-1">
                                        <span class="color-dot" style="background-color: <?php echo $p['codigo_hex']; ?>"></span>
                                        <?php echo htmlspecialchars($p['nombre_color']); ?>
                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($p['nombre_tipo'] ?? '-'); ?></td>
                            <td>$<?php echo number_format($p['precio'], 2); ?></td>
                            <td><?php echo $p['stock']; ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="action" value="toggle">
                                    <input type="hidden" name="id_producto" value="<?php echo $p['id_producto']; ?>">
                                    <button type="submit" class="btn btn-sm <?php echo $p['estado'] ? 'btn-success' : 'btn-secondary'; ?>">
                                        <?php echo $p['estado'] ? 'Activo' : 'Inactivo'; ?>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick='editProducto(<?php echo json_encode($p); ?>)'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este producto?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id_producto" value="<?php echo $p['id_producto']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Producto -->
<div class="modal fade" id="productoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" id="formAction" value="create">
                <input type="hidden" name="id_producto" id="idProducto">
                <input type="hidden" name="imagen_actual" id="imagenActual">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nombre del producto *</label>
                            <input type="text" class="form-control" name="nombre_producto" id="nombreProducto" required>
                            <small class="text-muted">No se permite duplicar nombres</small>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Precio *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="precio" id="precio" step="0.01" min="0" placeholder="0.00" required>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" id="descripcion" rows="3"></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Categoría</label>
                            <select class="form-select" name="id_categoria" id="idCategoria">
                                <option value="">Seleccionar categoría</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?php echo $cat['id_categoria']; ?>"><?php echo htmlspecialchars($cat['nombre_categoria']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Stock</label>
                            <input type="number" class="form-control" name="stock" id="stock" min="0" value="0">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Color</label>
                            <div class="color-options">
                                <?php foreach ($colores as $color): ?>
                                    <label class="color-option">
                                        <input type="radio" name="id_color" value="<?php echo $color['id_color']; ?>">
                                        <span class="color-box" style="background-color: <?php echo $color['codigo_hex']; ?>" title="<?php echo htmlspecialchars($color['nombre_color']); ?>"></span>
                                        <small><?php echo htmlspecialchars($color['nombre_color']); ?></small>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Tipo de material</label>
                            <div class="tipo-options">
                                <?php foreach ($tipos as $tipo): ?>
                                    <label class="tipo-option">
                                        <input type="radio" name="id_tipo" value="<?php echo $tipo['id_tipo']; ?>">
                                        <span class="tipo-box"><?php echo htmlspecialchars($tipo['nombre_tipo']); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <label class="form-label">Imagen</label>
                            <input type="file" class="form-control" name="imagen" accept="image/*">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Estado</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="estado" id="estado" checked>
                                <label class="form-check-label" for="estado">Activo</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetForm() {
    document.getElementById('formAction').value = 'create';
    document.getElementById('idProducto').value = '';
    document.getElementById('imagenActual').value = '';
    document.getElementById('modalTitle').textContent = 'Nuevo Producto';
    document.getElementById('nombreProducto').value = '';
    document.getElementById('precio').value = '';
    document.getElementById('descripcion').value = '';
    document.getElementById('idCategoria').value = '';
    document.getElementById('stock').value = '0';
    document.getElementById('estado').checked = true;
    
    document.querySelectorAll('input[name="id_color"]').forEach(r => r.checked = false);
    document.querySelectorAll('input[name="id_tipo"]').forEach(r => r.checked = false);
}

function editProducto(p) {
    document.getElementById('formAction').value = 'update';
    document.getElementById('idProducto').value = p.id_producto;
    document.getElementById('imagenActual').value = p.imagen || '';
    document.getElementById('modalTitle').textContent = 'Editar Producto';
    document.getElementById('nombreProducto').value = p.nombre_producto;
    document.getElementById('precio').value = p.precio;
    document.getElementById('descripcion').value = p.descripcion || '';
    document.getElementById('idCategoria').value = p.id_categoria || '';
    document.getElementById('stock').value = p.stock;
    document.getElementById('estado').checked = p.estado == 1;
    
    document.querySelectorAll('input[name="id_color"]').forEach(r => {
        r.checked = r.value == p.id_color;
    });
    document.querySelectorAll('input[name="id_tipo"]').forEach(r => {
        r.checked = r.value == p.id_tipo;
    });
    
    new bootstrap.Modal(document.getElementById('productoModal')).show();
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../views/layouts/admin.php';
?>
