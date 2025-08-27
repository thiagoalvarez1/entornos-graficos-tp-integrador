<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/header.php';

// Datos de prueba (simulando BD)
$locales = [
    [
        'id' => 1,
        'nombre' => 'Tienda Fashion',
        'ubicacion' => 'Planta Baja - Local 12',
        'rubro' => 'Indumentaria',
        'dueño' => 'Juan Pérez',
        'estado' => 'activo',
        'codigo' => 'LOC-001'
    ],
    [
        'id' => 2,
        'nombre' => 'Calzados Premium',
        'ubicacion' => 'Primer Piso - Local 45',
        'rubro' => 'Calzado',
        'dueño' => 'María García',
        'estado' => 'activo',
        'codigo' => 'LOC-002'
    ],
    [
        'id' => 3,
        'nombre' => 'TecnoShop',
        'ubicacion' => 'Segundo Piso - Local 78',
        'rubro' => 'Tecnología',
        'dueño' => 'Pedro Rodríguez',
        'estado' => 'inactivo',
        'codigo' => 'LOC-003'
    ]
];

$rubros = ['Indumentaria', 'Calzado', 'Tecnología', 'Perfumería', 'Óptica', 'Gastronomía', 'Juguetería', 'Deportes'];
?>

<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-1">Gestión de Locales</h1>
                    <p class="text-muted mb-0">Administra todos los locales del shopping center</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoLocal">
                    <i class="fas fa-plus me-2"></i>Nuevo Local
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Locales</h5>
                    <h2 class="mb-0"><?= count($locales) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Activos</h5>
                    <h2 class="mb-0"><?= count(array_filter($locales, fn($local) => $local['estado'] === 'activo')) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Inactivos</h5>
                    <h2 class="mb-0"><?= count(array_filter($locales, fn($local) => $local['estado'] === 'inactivo')) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Rubros</h5>
                    <h2 class="mb-0"><?= count($rubros) ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header bg-transparent">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">Listado de Locales</h5>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Buscar locales..." id="buscadorLocales">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Rubro</th>
                            <th>Dueño</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($locales as $local): ?>
                        <tr>
                            <td><span class="badge bg-secondary"><?= $local['codigo'] ?></span></td>
                            <td><?= $local['nombre'] ?></td>
                            <td><?= $local['ubicacion'] ?></td>
                            <td><span class="badge bg-info"><?= $local['rubro'] ?></span></td>
                            <td><?= $local['dueño'] ?></td>
                            <td>
                                <span class="badge bg-<?= $local['estado'] === 'activo' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($local['estado']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditarLocal"
                                            data-id="<?= $local['id'] ?>"
                                            data-nombre="<?= $local['nombre'] ?>"
                                            data-ubicacion="<?= $local['ubicacion'] ?>"
                                            data-rubro="<?= $local['rubro'] ?>"
                                            data-dueno="<?= $local['dueño'] ?>"
                                            data-estado="<?= $local['estado'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEliminarLocal"
                                            data-id="<?= $local['id'] ?>"
                                            data-nombre="<?= $local['nombre'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nuevo Local -->
<div class="modal fade" id="modalNuevoLocal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear Nuevo Local</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="procesar_local.php" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre del Local *</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ubicación *</label>
                            <input type="text" class="form-control" name="ubicacion" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rubro *</label>
                            <select class="form-select" name="rubro" required>
                                <option value="">Seleccionar rubro</option>
                                <?php foreach ($rubros as $rubro): ?>
                                    <option value="<?= $rubro ?>"><?= $rubro ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dueño Asignado</label>
                            <select class="form-select" name="dueno">
                                <option value="">Sin asignar</option>
                                <option value="1">Juan Pérez (juan@mail.com)</option>
                                <option value="2">María García (maria@mail.com)</option>
                                <option value="3">Pedro Rodríguez (pedro@mail.com)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="estado" value="activo" checked>
                            <label class="form-check-label">Activo</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="estado" value="inactivo">
                            <label class="form-check-label">Inactivo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Local</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Local -->
<div class="modal fade" id="modalEditarLocal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Local</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="procesar_local.php" method="POST">
                <input type="hidden" name="id" id="editId">
                <input type="hidden" name="action" value="editar">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre del Local *</label>
                            <input type="text" class="form-control" name="nombre" id="editNombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ubicación *</label>
                            <input type="text" class="form-control" name="ubicacion" id="editUbicacion" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rubro *</label>
                            <select class="form-select" name="rubro" id="editRubro" required>
                                <option value="">Seleccionar rubro</option>
                                <?php foreach ($rubros as $rubro): ?>
                                    <option value="<?= $rubro ?>"><?= $rubro ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dueño Asignado</label>
                            <select class="form-select" name="dueno" id="editDueno">
                                <option value="">Sin asignar</option>
                                <option value="1">Juan Pérez (juan@mail.com)</option>
                                <option value="2">María García (maria@mail.com)</option>
                                <option value="3">Pedro Rodríguez (pedro@mail.com)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="estado" value="activo" id="editEstadoActivo">
                            <label class="form-check-label">Activo</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="estado" value="inactivo" id="editEstadoInactivo">
                            <label class="form-check-label">Inactivo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Local -->
<div class="modal fade" id="modalEliminarLocal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="procesar_local.php" method="POST">
                <input type="hidden" name="id" id="deleteId">
                <input type="hidden" name="action" value="eliminar">
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar el local?</p>
                    <p><strong>Local:</strong> <span id="deleteNombre"></span></p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Esta acción no se puede deshacer. Se eliminarán todas las promociones asociadas.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar Local</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Script para modales de edición y eliminación
document.addEventListener('DOMContentLoaded', function() {
    // Modal de edición
    const modalEditar = document.getElementById('modalEditarLocal');
    modalEditar.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const nombre = button.getAttribute('data-nombre');
        const ubicacion = button.getAttribute('data-ubicacion');
        const rubro = button.getAttribute('data-rubro');
        const dueno = button.getAttribute('data-dueno');
        const estado = button.getAttribute('data-estado');

        document.getElementById('editId').value = id;
        document.getElementById('editNombre').value = nombre;
        document.getElementById('editUbicacion').value = ubicacion;
        document.getElementById('editRubro').value = rubro;
        document.getElementById('editDueno').value = dueno;
        
        if (estado === 'activo') {
            document.getElementById('editEstadoActivo').checked = true;
        } else {
            document.getElementById('editEstadoInactivo').checked = true;
        }
    });

    // Modal de eliminación
    const modalEliminar = document.getElementById('modalEliminarLocal');
    modalEliminar.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const nombre = button.getAttribute('data-nombre');

        document.getElementById('deleteId').value = id;
        document.getElementById('deleteNombre').textContent = nombre;
    });

    // Búsqueda
    const buscador = document.getElementById('buscadorLocales');
    const tabla = document.querySelector('table');
    const filas = tabla.querySelectorAll('tbody tr');

    buscador.addEventListener('input', function() {
        const texto = this.value.toLowerCase();
        
        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(texto) ? '' : 'none';
        });
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>