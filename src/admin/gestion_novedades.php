<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/header.php';

// Datos de prueba
$novedades = [
    [
        'id' => 1,
        'titulo' => '¡NUEVO HORARIO DE ATENCIÓN!',
        'texto' => 'Ampliamos nuestro horario de atención. Ahora abrimos de 9:00 a 22:00 hs todos los días.',
        'categoria' => 'todos',
        'fecha_desde' => '2025-08-20',
        'fecha_hasta' => '2025-12-31',
        'estado' => 'activa',
        'prioridad' => 'alta'
    ],
    [
        'id' => 2,
        'titulo' => 'PROMO ESPECIAL CLIENTES PREMIUM',
        'texto' => '40% off en todos los locales este fin de semana exclusivo para clientes Premium.',
        'categoria' => 'Premium',
        'fecha_desde' => '2025-08-25',
        'fecha_hasta' => '2025-08-27',
        'estado' => 'activa',
        'prioridad' => 'media'
    ],
    [
        'id' => 3,
        'titulo' => 'MANTENIMIENTO PROGRAMADO',
        'texto' => 'El próximo lunes el sistema estará en mantenimiento de 2:00 a 4:00 AM.',
        'categoria' => 'todos',
        'fecha_desde' => '2025-08-28',
        'fecha_hasta' => '2025-08-28',
        'estado' => 'inactiva',
        'prioridad' => 'baja'
    ]
];
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-1">Gestión de Novedades</h1>
                    <p class="text-muted mb-0">Administra las novedades del sistema</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaNovedad">
                    <i class="fas fa-plus me-2"></i>Nueva Novedad
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-transparent">
            <h5 class="mb-0">Novedades del Sistema</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Destinatarios</th>
                            <th>Vigencia</th>
                            <th>Estado</th>
                            <th>Prioridad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($novedades as $novedad): ?>
                        <tr>
                            <td>
                                <strong><?= $novedad['titulo'] ?></strong>
                                <br>
                                <small class="text-muted"><?= substr($novedad['texto'], 0, 50) ?>...</small>
                            </td>
                            <td>
                                <span class="badge bg-<?= 
                                    $novedad['categoria'] === 'todos' ? 'primary' : 
                                    ($novedad['categoria'] === 'Premium' ? 'warning' : 'info')
                                ?>">
                                    <?= $novedad['categoria'] === 'todos' ? 'TODOS' : $novedad['categoria'] ?>
                                </span>
                            </td>
                            <td>
                                <?= date('d/m/Y', strtotime($novedad['fecha_desde'])) ?> - 
                                <?= date('d/m/Y', strtotime($novedad['fecha_hasta'])) ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= $novedad['estado'] === 'activa' ? 'success' : 'secondary' ?>">
                                    <?= ucfirst($novedad['estado']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?= 
                                    $novedad['prioridad'] === 'alta' ? 'danger' : 
                                    ($novedad['prioridad'] === 'media' ? 'warning' : 'info')
                                ?>">
                                    <?= ucfirst($novedad['prioridad']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
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

<!-- Modal para nueva novedad -->
<div class="modal fade" id="modalNuevaNovedad" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Novedad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="procesar_novedad.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Título *</label>
                        <input type="text" class="form-control" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contenido *</label>
                        <textarea class="form-control" name="texto" rows="4" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Destinatarios *</label>
                            <select class="form-select" name="categoria" required>
                                <option value="todos">Todos los usuarios</option>
                                <option value="Inicial">Clientes Inicial</option>
                                <option value="Medium">Clientes Medium</option>
                                <option value="Premium">Clientes Premium</option>
                                <option value="dueños">Dueños de locales</option>
                                <option value="administradores">Administradores</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prioridad</label>
                            <select class="form-select" name="prioridad">
                                <option value="baja">Baja</option>
                                <option value="media">Media</option>
                                <option value="alta">Alta</option>
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
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="estado" value="activa" checked>
                            <label class="form-check-label">Activar inmediatamente</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Novedad</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>