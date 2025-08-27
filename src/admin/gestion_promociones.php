<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/header.php';

// Datos de prueba
$promociones = [
    [
        'id' => 1,
        'texto' => '20% DE DESCUENTO EN EFECTIVO',
        'local' => 'Tienda Fashion',
        'local_id' => 1,
        'fecha_desde' => '2025-08-01',
        'fecha_hasta' => '2025-09-30',
        'categoria' => 'Inicial',
        'estado' => 'aprobada',
        'dias' => ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes']
    ],
    [
        'id' => 2,
        'texto' => '2x1 EN PRODUCTOS SELECCIONADOS',
        'local' => 'Calzados Premium',
        'local_id' => 2,
        'fecha_desde' => '2025-09-01',
        'fecha_hasta' => '2025-10-15',
        'categoria' => 'Medium',
        'estado' => 'pendiente',
        'dias' => ['Sábado', 'Domingo']
    ],
    [
        'id' => 3,
        'texto' => '30% OFF + ENVÍO GRATIS',
        'local' => 'TecnoShop',
        'local_id' => 3,
        'fecha_desde' => '2025-08-15',
        'fecha_hasta' => '2025-08-31',
        'categoria' => 'Premium',
        'estado' => 'rechazada',
        'dias' => ['Lunes', 'Miércoles', 'Viernes']
    ]
];

$locales = [
    1 => 'Tienda Fashion',
    2 => 'Calzados Premium', 
    3 => 'TecnoShop'
];

$categorias = ['Inicial', 'Medium', 'Premium'];
$dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
?>

<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-1">Gestión de Promociones</h1>
                    <p class="text-muted mb-0">Administra todas las promociones del sistema</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaPromo">
                    <i class="fas fa-plus me-2"></i>Nueva Promoción
                </button>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-md-3">
            <select class="form-select" id="filtroEstado">
                <option value="">Todos los estados</option>
                <option value="aprobada">Aprobadas</option>
                <option value="pendiente">Pendientes</option>
                <option value="rechazada">Rechazadas</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="filtroCategoria">
                <option value="">Todas las categorías</option>
                <option value="Inicial">Inicial</option>
                <option value="Medium">Medium</option>
                <option value="Premium">Premium</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="filtroLocal">
                <option value="">Todos los locales</option>
                <?php foreach ($locales as $id => $nombre): ?>
                    <option value="<?= $id ?>"><?= $nombre ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar..." id="buscadorPromos">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header bg-transparent">
            <h5 class="mb-0">Listado de Promociones</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Promoción</th>
                            <th>Local</th>
                            <th>Vigencia</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($promociones as $promo): ?>
                        <tr>
                            <td>
                                <div>
                                    <strong><?= $promo['texto'] ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= date('d/m/Y', strtotime($promo['fecha_desde'])) ?> - <?= date('d/m/Y', strtotime($promo['fecha_hasta'])) ?>
                                    </small>
                                </div>
                            </td>
                            <td><?= $promo['local'] ?></td>
                            <td>
                                <span class="badge bg-<?= 
                                    strtotime($promo['fecha_hasta']) > time() ? 'success' : 'danger' 
                                ?>">
                                    <?= strtotime($promo['fecha_hasta']) > time() ? 'Vigente' : 'Expirada' ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?= 
                                    $promo['categoria'] === 'Premium' ? 'warning' : 
                                    ($promo['categoria'] === 'Medium' ? 'info' : 'secondary')
                                ?>">
                                    <?= $promo['categoria'] ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?= 
                                    $promo['estado'] === 'aprobada' ? 'success' : 
                                    ($promo['estado'] === 'pendiente' ? 'warning' : 'danger')
                                ?>">
                                    <?= ucfirst($promo['estado']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditarPromo"
                                            data-id="<?= $promo['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEliminarPromo"
                                            data-id="<?= $promo['id'] ?>"
                                            data-texto="<?= $promo['texto'] ?>">
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

<!-- Modales similares a los de locales pero para promociones -->
<!-- Modal Nueva Promoción -->
<div class="modal fade" id="modalNuevaPromo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Promoción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="procesar_promocion.php" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Texto de la promoción *</label>
                            <textarea class="form-control" name="texto" rows="3" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Local *</label>
                            <select class="form-select" name="local_id" required>
                                <option value="">Seleccionar local</option>
                                <?php foreach ($locales as $id => $nombre): ?>
                                    <option value="<?= $id ?>"><?= $nombre ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha desde *</label>
                            <input type="date" class="form-control" name="fecha_desde" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha hasta *</label>
                            <input type="date" class="form-control" name="fecha_hasta" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Categoría de cliente *</label>
                            <select class="form-select" name="categoria" required>
                                <option value="">Seleccionar categoría</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?= $categoria ?>"><?= $categoria ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="estado">
                                <option value="pendiente">Pendiente</option>
                                <option value="aprobada">Aprobada</option>
                                <option value="rechazada">Rechazada</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Días de la semana *</label>
                        <div class="row">
                            <?php foreach ($dias_semana as $index => $dia): ?>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="dias[]" value="<?= $index + 1 ?>" id="dia<?= $index ?>">
                                    <label class="form-check-label" for="dia<?= $index ?>"><?= $dia ?></label>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Promoción</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts para filtros y modales -->
<script>
// Implementar scripts similares a los de locales
</script>

<?php require_once '../includes/footer.php'; ?>