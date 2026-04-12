<?php
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/UsuariosController.php';

$basePath = '/artecalli/public';

if (!AuthController::isAuthenticated()) {
    header('Location: ' . $basePath . '/admin/login.php');
    exit;
}

$pageTitle = 'Gestión de Administradores';
$currentPage = 'usuarios';

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create' || $action === 'update') {
        $data = [
            'nombre' => trim($_POST['nombre']),
            'usuario' => trim($_POST['usuario']),
            'contrasena' => $_POST['contrasena'] ?? '',
            'estado' => isset($_POST['estado']) ? 1 : 0
        ];
        
        if ($action === 'create') {
            if (strlen($data['contrasena']) < 6) {
                $_SESSION['flash_error'] = 'La contraseña debe tener al menos 6 caracteres';
            } else {
                $result = UsuariosController::create($data);
                if ($result['success']) {
                    $_SESSION['flash_success'] = 'Administrador creado correctamente';
                } else {
                    $_SESSION['flash_error'] = $result['error'];
                }
            }
        } else {
            $result = UsuariosController::update($_POST['id_usuario'], $data);
            if ($result['success']) {
                $_SESSION['flash_success'] = 'Administrador actualizado';
            } else {
                $_SESSION['flash_error'] = $result['error'];
            }
        }
    }
    
    if ($action === 'delete') {
        $result = UsuariosController::delete($_POST['id_usuario']);
        if ($result['success']) {
            $_SESSION['flash_success'] = 'Administrador eliminado';
        } else {
            $_SESSION['flash_error'] = $result['error'];
        }
    }
    
    header('Location: ' . $basePath . '/admin/usuarios.php');
    exit;
}

$usuarios = UsuariosController::getAll();

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">Administradores (<?php echo count($usuarios); ?>)</h2>
    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#usuarioModal" onclick="resetForm()">
        <i class="bi bi-plus-lg me-2"></i>Nuevo Admin
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Fecha Creación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($u['nombre']); ?></strong></td>
                        <td>@<?php echo htmlspecialchars($u['usuario']); ?></td>
                        <td><span class="badge bg-dark"><?php echo htmlspecialchars($u['rol']); ?></span></td>
                        <td class="text-muted"><?php echo date('d/m/Y', strtotime($u['fecha_creacion'])); ?></td>
                        <td>
                            <span class="badge <?php echo $u['estado'] ? 'bg-success' : 'bg-secondary'; ?>">
                                <?php echo $u['estado'] ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick='editUsuario(<?php echo json_encode($u); ?>)'>
                                <i class="bi bi-pencil"></i>
                            </button>
                            <?php if (count($usuarios) > 1): ?>
                                <form method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este administrador?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id_usuario" value="<?php echo $u['id_usuario']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Usuario -->
<div class="modal fade" id="usuarioModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="action" id="formAction" value="create">
                <input type="hidden" name="id_usuario" id="idUsuario">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Nuevo Administrador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre completo *</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Usuario *</label>
                        <input type="text" class="form-control" name="usuario" id="usuario" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Contraseña <span id="passHint" class="text-muted small">(mínimo 6 caracteres)</span></label>
                        <input type="password" class="form-control" name="contrasena" id="contrasena">
                    </div>
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="estado" id="estado" checked>
                        <label class="form-check-label" for="estado">Activo</label>
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
    document.getElementById('idUsuario').value = '';
    document.getElementById('modalTitle').textContent = 'Nuevo Administrador';
    document.getElementById('nombre').value = '';
    document.getElementById('usuario').value = '';
    document.getElementById('contrasena').value = '';
    document.getElementById('contrasena').required = true;
    document.getElementById('passHint').textContent = '(mínimo 6 caracteres)';
    document.getElementById('estado').checked = true;
}

function editUsuario(u) {
    document.getElementById('formAction').value = 'update';
    document.getElementById('idUsuario').value = u.id_usuario;
    document.getElementById('modalTitle').textContent = 'Editar Administrador';
    document.getElementById('nombre').value = u.nombre;
    document.getElementById('usuario').value = u.usuario;
    document.getElementById('contrasena').value = '';
    document.getElementById('contrasena').required = false;
    document.getElementById('passHint').textContent = '(dejar vacío para mantener actual)';
    document.getElementById('estado').checked = u.estado == 1;
    
    new bootstrap.Modal(document.getElementById('usuarioModal')).show();
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../views/layouts/admin.php';
?>
