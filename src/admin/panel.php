<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess(['administrador']);

// Obtener datos para el dashboard
$database = new Database();
$conn = $database->getConnection();

// Estadísticas generales
$query_stats = "SELECT 
    COUNT(DISTINCT l.codLocal) as locales_registrados,
    COUNT(DISTINCT p.codPromo) as promociones_activas,
    COUNT(DISTINCT CASE WHEN u.estado = 'pendiente' AND u.tipoUsuario = 'dueño de local' THEN u.codUsuario END) as duenos_pendientes,
    COUNT(DISTINCT CASE WHEN up.estado = 'enviada' THEN up.codUso END) as solicitudes_pendientes
    FROM locales l
    LEFT JOIN promociones p ON l.codLocal = p.codLocal AND p.estadoPromo = 'aprobada' AND p.fechaHastaPromo >= CURDATE()
    LEFT JOIN usuarios u ON u.tipoUsuario = 'dueño de local'
    LEFT JOIN uso_promociones up ON up.estado = 'enviada'";

$stmt_stats = $conn->prepare($query_stats);
$stmt_stats->execute();
$stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);

// Dueños pendientes de validación
$query_duenos = "SELECT 
    u.codUsuario,
    u.nombreUsuario,
    u.nombreUsuario AS email,
    l.nombreLocal,
    u.fechaRegistro
    FROM usuarios u
    LEFT JOIN locales l ON u.codUsuario = l.codUsuario
    WHERE u.tipoUsuario = 'dueño de local' 
    AND u.estado = 'pendiente'
    ORDER BY u.fechaRegistro DESC
    LIMIT 5";

$stmt_duenos = $conn->prepare($query_duenos);
$stmt_duenos->execute();
$duenos_pendientes = $stmt_duenos->fetchAll(PDO::FETCH_ASSOC);

// Novedades recientes
$query_novedades = "SELECT 
    textoNovedad,
    fechaCreacion
    FROM novedades 
    ORDER BY fechaCreacion DESC
    LIMIT 3";

$stmt_novedades = $conn->prepare($query_novedades);
$stmt_novedades->execute();
$novedades_recientes = $stmt_novedades->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Panel Administrador";
require_once '../includes/header-panel.php';
?>
<link rel="stylesheet" href="../css/panel-admin.css">
<div class="container-fluid py-4">
    <!-- Header de bienvenida -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Panel de Administración</h1>
            <p class="text-muted mb-0">Gestión completa del sistema Bandera Shopping</p>
        </div>
        <a href="generar_reporte.php" class="btn btn-primary">
            <i class="fas fa-download me-2"></i>Generar Reporte
        </a>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card card-panel h-100">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="fas fa-store fa-2x"></i>
                    </div>
                    <h3 class="text-primary"><?= $stats['locales_registrados'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Locales Registrados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-panel h-100">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="fas fa-tags fa-2x"></i>
                    </div>
                    <h3 class="text-success"><?= $stats['promociones_activas'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Promociones Activas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-panel h-100">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="fas fa-clipboard-list fa-2x"></i>
                    </div>
                    <h3 class="text-info"><?= $stats['solicitudes_pendientes'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Solicitudes Pendientes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-panel h-100">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                    <h3 class="text-warning"><?= $stats['duenos_pendientes'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Dueños por Validar</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Dueños por Validar -->
        <div class="col-lg-6 mb-4">
            <div class="card card-panel h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-check me-2"></i>Dueños por Validar
                    </h5>
                    <a href="validar-duenos.php" class="btn btn-sm btn-outline-primary">Ver todos</a>
                </div>
                <div class="card-body">
                    <?php if (empty($duenos_pendientes)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay dueños pendientes</h5>
                            <p class="text-muted">No hay solicitudes de dueños pendientes de validación</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Local</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($duenos_pendientes as $dueno): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($dueno['nombreUsuario']) ?></strong>
                                            </td>
                                            <td><?= htmlspecialchars($dueno['email']) ?></td>
                                            <td>
                                                <?php if ($dueno['nombreLocal']): ?>
                                                    <span
                                                        class="badge bg-info"><?= htmlspecialchars($dueno['nombreLocal']) ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Sin local</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form method="POST" action="procesar_validacion.php" class="d-inline">
                                                    <input type="hidden" name="usuario_id" value="<?= $dueno['codUsuario'] ?>">
                                                    <input type="hidden" name="accion" value="aprobar">
                                                    <button type="submit" class="btn btn-success btn-sm"
                                                        onclick="return confirm('¿Aprobar a <?= htmlspecialchars($dueno['nombreUsuario']) ?>?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="procesar_validacion.php" class="d-inline ms-1">
                                                    <input type="hidden" name="usuario_id" value="<?= $dueno['codUsuario'] ?>">
                                                    <input type="hidden" name="accion" value="rechazar">
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('¿Rechazar a <?= htmlspecialchars($dueno['nombreUsuario']) ?>?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Novedades Recientes -->
        <div class="col-lg-6 mb-4">
            <div class="card card-panel h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bullhorn me-2"></i>Novedades Recientes
                    </h5>
                    <a href="gestion_novedades.php" class="btn btn-sm btn-outline-primary">Gestionar</a>
                </div>
                <div class="card-body">
                    <?php if (empty($novedades_recientes)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay novedades</h5>
                            <p class="text-muted">Crea la primera novedad del sistema</p>
                            <a href="crear_novedad.php" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-2"></i>Crear Novedad
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($novedades_recientes as $novedad): ?>
                                <div class="list-group-item px-0">
                                    <h6 class="mb-2"><?= htmlspecialchars($novedad['textoNovedad']) ?></h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= date('d/m/Y H:i', strtotime($novedad['fechaCreacion'])) ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="row">
        <div class="col-12">
            <div class="card card-panel">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <a href="gestion-locales.php" class="btn btn-outline-primary btn-lg w-100 h-100 py-3">
                                <i class="fas fa-store fa-2x mb-2"></i>
                                <br>
                                <span>Gestionar Locales</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="gestion_promociones.php" class="btn btn-outline-success btn-lg w-100 h-100 py-3">
                                <i class="fas fa-tags fa-2x mb-2"></i>
                                <br>
                                <span>Promociones</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="gestion_novedades.php" class="btn btn-outline-info btn-lg w-100 h-100 py-3">
                                <i class="fas fa-bullhorn fa-2x mb-2"></i>
                                <br>
                                <span>Novedades</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="reportes.php" class="btn btn-outline-warning btn-lg w-100 h-100 py-3">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                <br>
                                <span>Reportes</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Sistema -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-panel">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información del Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="bg-light p-3 rounded">
                                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                <h5 class="text-primary">150+</h5>
                                <p class="text-muted mb-0">Usuarios Registrados</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="bg-light p-3 rounded">
                                <i class="fas fa-shopping-bag fa-2x text-success mb-2"></i>
                                <h5 class="text-success">500+</h5>
                                <p class="text-muted mb-0">Promociones Creadas</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="bg-light p-3 rounded">
                                <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                                <h5 class="text-info">95%</h5>
                                <p class="text-muted mb-0">Satisfacción del Sistema</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. ANIMACIÓN DE NÚMEROS EN ESTADÍSTICAS (solo si existen)
        const statNumbers = document.querySelectorAll('.card-panel h3');
        statNumbers.forEach(stat => {
            const finalValue = parseInt(stat.textContent);
            if (!isNaN(finalValue) && finalValue > 0) {
                let currentValue = 0;
                const increment = Math.ceil(finalValue / 30);
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        stat.textContent = finalValue;
                        clearInterval(timer);
                    } else {
                        stat.textContent = currentValue;
                    }
                }, 50);
            }
        });

        // 2. EFECTOS HOVER PARA TARJETAS (solo si existen)
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-5px)';
            });

            card.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0)';
            });
        });

        // 3. ANIMACIÓN DE ENTRADA PARA TARJETAS (solo si existen)
        const animatedCards = document.querySelectorAll('.card-panel');
        animatedCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';

            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // 4. CONFIRMACIÓN PARA ACCIONES (solo si existen)
        const actionButtons = document.querySelectorAll('.btn-danger, .btn-success');
        actionButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                if (!confirm('¿Estás seguro de que quieres realizar esta acción?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
<?php require_once '../includes/footer-panel.php'; ?>