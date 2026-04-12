<?php
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/AccesosController.php';

$basePath = '/artecalli/public';

if (!AuthController::isAuthenticated()) {
    header('Location: ' . $basePath . '/admin/login.php');
    exit;
}

$pageTitle = 'Registro de Accesos';
$currentPage = 'accesos';

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'delete') {
        AccesosController::delete($_POST['id_registro']);
        $_SESSION['flash_success'] = 'Registro eliminado';
    }
    
    if ($action === 'clear') {
        AccesosController::clearAll();
        $_SESSION['flash_success'] = 'Todos los registros han sido eliminados';
    }
    
    header('Location: ' . $basePath . '/admin/accesos.php');
    exit;
}

$accesos = AccesosController::getAll();

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">Registro de Accesos (<?php echo count($accesos); ?>)</h2>
    <?php if (!empty($accesos)): ?>
        <form method="POST" onsubmit="return confirm('¿Eliminar todos los registros?')">
            <input type="hidden" name="action" value="clear">
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-trash me-2"></i>Limpiar Todo
            </button>
        </form>
    <?php endif; ?>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Fecha y Hora</th>
                    <th>Usuario</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($accesos)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-clock-history display-4 d-block mb-2"></i>
                            No hay registros de acceso
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($accesos as $a): ?>
                        <tr>
                            <td>
                                <i class="bi bi-calendar me-1"></i>
                                <?php echo date('d/m/Y H:i:s', strtotime($a['fecha_hora'])); ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($a['nombre_usuario'] ?? 'Desconocido'); ?></strong></td>
                            <td><span class="badge bg-dark">Administrador</span></td>
                            <td>
                                <?php if ($a['exito']): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Exitoso</span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Fallido</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este registro?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id_registro" value="<?php echo $a['id_registro']; ?>">
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

<?php
$content = ob_get_clean();
include __DIR__ . '/../../views/layouts/admin.php';
?>
