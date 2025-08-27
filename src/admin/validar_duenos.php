<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/header.php';

// Datos de prueba
$solicitudes = [
    [
        'id' => 1,
        'nombre' => 'Carlos López',
        'email' => 'carlos@mail.com',
        'telefono' => '341-1234567',
        'local_solicitado' => 'Deportes Max',
        'rubro' => 'Deportes',
        'fecha_solicitud' => '2025-08-20',
        'estado' => 'pendiente',
        'documento' => 'DNI 40.123.456'
    ],
    [
        'id' => 2,
        'nombre' => 'Ana Martínez',
        'email' => 'ana@mail.com', 
        'telefono' => '341-7654321',
        'local_solicitado' => 'Óptica Vision',
        'rubro' => 'Óptica',
        'fecha_solicitud' => '2025-08-19',
        'estado' => 'pendiente',
        'documento' => 'DNI 35.987.654'
    ],
    [
        'id' => 3,
        'nombre' => 'Luis García',
        'email' => 'luis@mail.com',
        'telefono' => '341-5555555',
        'local_solicitado' => 'Café Central',
        'rubro' => 'Gastronomía',
        'fecha_solicitud' => '2025-08-18',
        'estado' => 'aprobada',
        'documento' => 'DNI 38.456.789'
    ]
];
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 fw-bold mb-1">Validación de Dueños de Locales</h1>
            <p class="text-muted mb-0">Gestiona las solicitudes de registro de dueños de locales</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-transparent">
            <h5 class="mb-0">Solicitudes Pendientes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Solicitante</th>
                            <th>Contacto</th>
                            <th>Local Solicitado</th>
                            <th>Rubro</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudes as $solicitud): ?>
                        <tr>
                            <td>
                                <div>
                                    <strong><?= $solicitud['nombre'] ?></strong>
                                    <br>
                                    <small class="text-muted"><?= $solicitud['documento'] ?></small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <small><?= $solicitud['email'] ?></small>
                                    <br>
                                    <small class="text-muted"><?= $solicitud['telefono'] ?></small>
                                </div>
                            </td>
                            <td><?= $solicitud['local_solicitado'] ?></td>
                            <td><span class="badge bg-info"><?= $solicitud['rubro'] ?></span></td>
                            <td><?= date('d/m/Y', strtotime($solicitud['fecha_solicitud'])) ?></td>
                            <td>
                                <span class="badge bg-<?= 
                                    $solicitud['estado'] === 'aprobada' ? 'success' : 
                                    ($solicitud['estado'] === 'rechazada' ? 'danger' : 'warning')
                                ?>">
                                    <?= ucfirst($solicitud['estado']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($solicitud['estado'] === 'pendiente'): ?>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-success"
                                            onclick="aprobarSolicitud(<?= $solicitud['id'] ?>)">
                                        <i class="fas fa-check"></i> Aprobar
                                    </button>
                                    <button class="btn btn-sm btn-danger"
                                            onclick="rechazarSolicitud(<?= $solicitud['id'] ?>)">
                                        <i class="fas fa-times"></i> Rechazar
                                    </button>
                                </div>
                                <?php else: ?>
                                <span class="text-muted">Procesada</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function aprobarSolicitud(id) {
    if (confirm('¿Estás seguro de aprobar esta solicitud?')) {
        // Simular AJAX
        alert('Solicitud ' + id + ' aprobada exitosamente');
        location.reload();
    }
}

function rechazarSolicitud(id) {
    if (confirm('¿Estás seguro de rechazar esta solicitud?')) {
        // Simular AJAX  
        alert('Solicitud ' + id + ' rechazada');
        location.reload();
    }
}
</script>

<?php require_once '../includes/footer.php'; ?>