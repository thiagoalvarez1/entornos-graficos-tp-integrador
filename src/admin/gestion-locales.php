<?php
// admin/gestion-locales.php

require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
// Asegúrate de que solo el administrador pueda acceder
$auth->checkAccess([USER_ADMIN]);

$database = new Database();
$conn = $database->getConnection();

$pageTitle = "Gestión de Locales";

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

// --- 1. Lógica para obtener Locales y Dueños ---

$locales = [];
$duenos = [];

try {
    // A. Obtener lista de locales con el email del dueño
    $query_locales = "
        SELECT 
            l.*, 
            u.nombreUsuario as email_dueno,
            u.estado as estado_dueno
        FROM locales l 
        INNER JOIN usuarios u ON l.codUsuario = u.codUsuario
        ORDER BY l.codLocal DESC
    ";
    $stmt_locales = $conn->prepare($query_locales);
    $stmt_locales->execute();
    $locales = $stmt_locales->fetchAll(PDO::FETCH_ASSOC);

    // B. Obtener lista de Dueños disponibles (usuarios con rol de dueño de local)
    // Se recomienda filtrar por dueños que aún no tienen un local asignado si la relación es 1:1
    $query_duenos = "
        SELECT 
            codUsuario, 
            nombreUsuario 
        FROM usuarios 
        WHERE tipoUsuario = :tipo_dueno AND estado = 'activo'
        AND codUsuario NOT IN (SELECT codUsuario FROM locales)
    ";
    $stmt_duenos = $conn->prepare($query_duenos);
    $stmt_duenos->bindValue(':tipo_dueno', USER_OWNER);
    $stmt_duenos->execute();
    $duenos = $stmt_duenos->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Error de base de datos: " . $e->getMessage();
}

require_once '../includes/header-panel.php';
?>

<div class="main-content-panel">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 text-primary"><i class="fas fa-store me-2"></i>Gestión de Locales</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearLocalModal">
            <i class="fas fa-plus-circle me-1"></i> Nuevo Local
        </button>
    </div>

    <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    <?php endif; ?>

    <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-5">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold text-muted">Listado de Locales</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Rubro</th>
                            <th>Dueño (Email)</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($locales) > 0): ?>
                                <?php foreach ($locales as $local): ?>
                                        <tr>
                                            <td class="text-center"><?php echo htmlspecialchars($local['codLocal']); ?></td>
                                            <td><?php echo htmlspecialchars($local['nombreLocal']); ?></td>
                                            <td><?php echo htmlspecialchars($local['ubicacionLocal']); ?></td>
                                            <td><?php echo htmlspecialchars($local['rubroLocal']); ?></td>
                                            <td>
                                                <?php echo htmlspecialchars($local['email_dueno']); ?>
                                                <?php if ($local['estado_dueno'] !== 'activo'): ?>
                                                        <span class="badge bg-danger ms-1"
                                                            title="El dueño está Inactivo o Pendiente. El local no aparecerá al público.">⚠️</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                $badge_class = $local['estado'] == 'activo' ? 'success' : 'danger';
                                                $estado_texto = ucfirst($local['estado']);
                                                echo "<span class='badge bg-{$badge_class}'>{$estado_texto}</span>";
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-info me-1 edit-local-btn"
                                                    data-bs-toggle="modal" data-bs-target="#editarLocalModal"
                                                    data-id="<?php echo $local['codLocal']; ?>"
                                                    data-nombre="<?php echo htmlspecialchars($local['nombreLocal']); ?>"
                                                    data-ubicacion="<?php echo htmlspecialchars($local['ubicacionLocal']); ?>"
                                                    data-rubro="<?php echo htmlspecialchars($local['rubroLocal']); ?>"
                                                    data-usuario="<?php echo htmlspecialchars($local['codUsuario']); ?>"
                                                    data-estado="<?php echo htmlspecialchars($local['estado']); ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <button class="btn btn-sm btn-outline-danger delete-local-btn"
                                                    data-id="<?php echo $local['codLocal']; ?>">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                <?php endforeach; ?>
                        <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No se encontraron locales.</td>
                                </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="crearLocalModal" tabindex="-1" aria-labelledby="crearLocalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="crearLocalModalLabel"><i class="fas fa-plus-circle me-2"></i>Crear Nuevo Local</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="procesar_local.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="crear">
                    
                    <div class="mb-3">
                        <label for="crear_nombre" class="form-label">Nombre del Local</label>
                        <input type="text" class="form-control" id="crear_nombre" name="nombre" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label for="crear_ubicacion" class="form-label">Ubicación (Ej: Planta Baja, Local 123)</label>
                        <input type="text" class="form-control" id="crear_ubicacion" name="ubicacion" required maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label for="crear_rubro" class="form-label">Rubro (Ej: Tecnología, Comida)</label>
                        <input type="text" class="form-control" id="crear_rubro" name="rubro" required maxlength="20">
                    </div>
                    <div class="mb-3">
                        <label for="crear_dueno_id" class="form-label">Dueño Asignado</label>
                        <select class="form-select" id="crear_dueno_id" name="dueno_id" required>
                            <option value="" selected disabled>Seleccione un dueño</option>
                            <?php
                            if (!empty($duenos)):
                                foreach ($duenos as $dueno):
                                    ?>
                                        <option value="<?php echo $dueno['codUsuario']; ?>">
                                            <?php echo htmlspecialchars($dueno['nombreUsuario']); ?>
                                        </option>
                                <?php
                                endforeach;
                            else:
                                ?>
                                    <option disabled>No hay dueños activos sin local asignado</option>
                            <?php endif; ?>
                        </select>
                        <small class="form-text text-muted">Solo se muestran dueños activos que aún no tienen un local.</small>
                    </div>
                    <div class="mb-3">
                        <label for="crear_estado" class="form-label">Estado</label>
                        <select class="form-select" id="crear_estado" name="estado" required>
                            <option value="activo" selected>Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Guardar Local</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editarLocalModal" tabindex="-1" aria-labelledby="editarLocalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="editarLocalModalLabel"><i class="fas fa-edit me-2"></i>Editar Local</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="procesar_local.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="editar">
                    <input type="hidden" name="codLocal" id="edit_codLocal">
                    
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre del Local</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label for="edit_ubicacion" class="form-label">Ubicación</label>
                        <input type="text" class="form-control" id="edit_ubicacion" name="ubicacion" required maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label for="edit_rubro" class="form-label">Rubro</label>
                        <input type="text" class="form-control" id="edit_rubro" name="rubro" required maxlength="20">
                    </div>
                    <div class="mb-3">
                        <label for="edit_dueno_id" class="form-label">Dueño Asignado</label>
                        <select class="form-select" id="edit_dueno_id" name="dueno_id" required>
                            <?php
                            // Lista combinada de dueños: todos los dueños activos y los que no tienen local asignado.
                            // Para simplificar, listaremos a todos los dueños activos.
                            $query_all_duenos = "
                                SELECT 
                                    codUsuario, 
                                    nombreUsuario 
                                FROM usuarios 
                                WHERE tipoUsuario = :tipo_dueno AND estado = 'activo'
                            ";
                            $stmt_all_duenos = $conn->prepare($query_all_duenos);
                            $stmt_all_duenos->bindValue(':tipo_dueno', USER_OWNER);
                            $stmt_all_duenos->execute();
                            $all_duenos = $stmt_all_duenos->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($all_duenos as $dueno):
                                ?>
                                    <option value="<?php echo $dueno['codUsuario']; ?>">
                                        <?php echo htmlspecialchars($dueno['nombreUsuario']); ?>
                                    </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">Asegúrese de que solo un local tenga el mismo dueño asignado.</small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_estado" class="form-label">Estado</label>
                        <select class="form-select" id="edit_estado" name="estado" required>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info text-white"><i class="fas fa-save me-1"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="deleteForm" action="procesar_local.php" method="POST" style="display: none;">
    <input type="hidden" name="action" value="eliminar">
    <input type="hidden" name="codLocal" id="delete_codLocal">
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Lógica para llenar el modal de edición
    document.querySelectorAll('.edit-local-btn').forEach(button => {
        button.addEventListener('click', function () {
            const codLocal = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            const ubicacion = this.getAttribute('data-ubicacion');
            const rubro = this.getAttribute('data-rubro');
            const codUsuario = this.getAttribute('data-usuario');
            const estado = this.getAttribute('data-estado');

            document.getElementById('edit_codLocal').value = codLocal;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_ubicacion').value = ubicacion;
            document.getElementById('edit_rubro').value = rubro;
            
            // Seleccionar el dueño
            document.getElementById('edit_dueno_id').value = codUsuario;
            
            // Seleccionar el estado
            document.getElementById('edit_estado').value = estado;
        });
    });

    // 2. Lógica para la eliminación
    document.querySelectorAll('.delete-local-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const codLocal = this.getAttribute('data-id');

            if (confirm('⚠️ ¿Estás seguro de ELIMINAR este Local? Esta acción es irreversible y afectará a sus promociones asociadas.')) {
                document.getElementById('delete_codLocal').value = codLocal;
                document.getElementById('deleteForm').submit();
            }
        });
    });
});
</script>

<?php require_once '../includes/footer-panel.php'; ?>