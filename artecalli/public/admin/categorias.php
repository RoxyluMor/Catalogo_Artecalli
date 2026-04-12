<?php
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/CategoriasController.php';

$basePath = '/artecalli/public';

if (!AuthController::isAuthenticated()) {
    header('Location: ' . $basePath . '/admin/login.php');
    exit;
}

$pageTitle = 'Gestión de Categorías';
$currentPage = 'categorias';

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create' || $action === 'update') {
        $data = [
            'nombre_categoria' => trim($_POST['nombre_categoria']),
            'descripcion' => trim($_POST['descripcion']),
            'estado' => isset($_POST['estado']) ? 1 : 0
        ];
        
        if ($action === 'create') {
            $result = CategoriasController::create($data);
        } else {
            $result = CategoriasController::update($_POST['id_categoria'], $data);
        }
        
        if ($result['success']) {
            $_SESSION['flash_success'] = $action === 'create' ? 'Categoría creada correctamente' : 'Categoría actualizada';
        } else {
            $_SESSION['flash_error'] = $result['error'];
        }
    }
    
    if ($action === 'delete') {
        CategoriasController::delete($_POST['id_categoria']);
        $_SESSION['flash_success'] = 'Categoría eliminada';
    }
    
    if ($action === 'toggle') {
        CategoriasController::toggleEstado($_POST['id_categoria']);
        $_SESSION['flash_success'] = 'Estado actualizado';
    }
    
    header('Location: ' . $basePath . '/admin/categorias.php');
    exit;
}

$categorias = CategoriasController::getAll();

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">Categorías (<?php echo count($categorias); ?>)</h2>
    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#categoriaModal" onclick="resetForm()">
        <i class="bi bi-plus-lg me-2"></i>Nueva Categoría
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categorias)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-tags display-4 d-block mb-2"></i>
                            No hay categorías registradas
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categorias as $c): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($c['nombre_categoria']); ?></strong></td>
                            <td class="text-muted"><?php echo htmlspecialchars(substr($c['descripcion'] ?? '', 0, 60)); ?>...</td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="action" value="toggle">
                                    <input type="hidden" name="id_categoria" value="<?php echo $c['id_categoria']; ?>">
                                    <button type="submit" class="btn btn-sm <?php echo $c['estado'] ? 'btn-success' : 'btn-secondary'; ?>">
                                        <?php echo $c['estado'] ? 'Activa' : 'Inactiva'; ?>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick='editCategoria(<?php echo json_encode($c); ?>)'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta categoría?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id_categoria" value="<?php echo $c['id_categoria']; ?>">
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

<!-- Modal Categoria -->
<div class="modal fade" id="categoriaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="action" id="formAction" value="create">
                <input type="hidden" name="id_categoria" id="idCategoria">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre de la categoría *</label>
                        <input type="text" class="form-control" name="nombre_categoria" id="nombreCategoria" required>
                        <small class="text-muted">No se permite duplicar nombres</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" rows="3"></textarea>
                    </div>
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="estado" id="estado" checked>
                        <label class="form-check-label" for="estado">Activa</label>
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
    document.getElementById('idCategoria').value = '';
    document.getElementById('modalTitle').textContent = 'Nueva Categoría';
    document.getElementById('nombreCategoria').value = '';
    document.getElementById('descripcion').value = '';
    document.getElementById('estado').checked = true;
}

function editCategoria(c) {
    document.getElementById('formAction').value = 'update';
    document.getElementById('idCategoria').value = c.id_categoria;
    document.getElementById('modalTitle').textContent = 'Editar Categoría';
    document.getElementById('nombreCategoria').value = c.nombre_categoria;
    document.getElementById('descripcion').value = c.descripcion || '';
    document.getElementById('estado').checked = c.estado == 1;
    
    new bootstrap.Modal(document.getElementById('categoriaModal')).show();
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../views/layouts/admin.php';
?>
