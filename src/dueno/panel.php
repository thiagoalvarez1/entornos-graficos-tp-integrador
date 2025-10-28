<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess(['dueño de local']);

// Obtener datos del dueño y su local
$database = new Database();
$conn = $database->getConnection();
$user_id = $_SESSION['user_id'];

// Obtener información del local del dueño
$query_local = "SELECT 
    l.codLocal,
    l.nombreLocal,
    l.ubicacionLocal,
    l.rubroLocal,
    l.estado as estadoLocal
    FROM locales l
    WHERE l.codUsuario = :user_id AND l.estado = 'activo'";

$stmt_local = $conn->prepare($query_local);
$stmt_local->bindParam(':user_id', $user_id);
$stmt_local->execute();
$local = $stmt_local->fetch(PDO::FETCH_ASSOC);

// Estadísticas del local
if ($local) {
    $query_stats = "SELECT 
        COUNT(p.codPromo) as promociones_activas,
        COUNT(up.codUso) as solicitudes_hoy,
        ROUND(COUNT(CASE WHEN up.estado = 'aceptada' THEN 1 END) * 100.0 / COUNT(up.codUso), 0) as tasa_aceptacion,
        COUNT(DISTINCT up.codCliente) as clientes_unicos
        FROM promociones p
        LEFT JOIN uso_promociones up ON p.codPromo = up.codPromo AND DATE(up.fechaUsoPromo) = CURDATE()
        WHERE p.codLocal = :local_id 
        AND p.estadoPromo = 'aprobada'";

    $stmt_stats = $conn->prepare($query_stats);
    $stmt_stats->bindParam(':local_id', $local['codLocal']);
    $stmt_stats->execute();
    $stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);

    // Solicitudes recientes
    $query_solicitudes = "SELECT 
        up.codUso,
        u.nombreUsuario as cliente,
        p.textoPromo,
        up.fechaUsoPromo,
        up.estado
        FROM uso_promociones up
        JOIN usuarios u ON up.codCliente = u.codUsuario
        JOIN promociones p ON up.codPromo = p.codPromo
        WHERE p.codLocal = :local_id 
        AND up.estado = 'enviada'
        ORDER BY up.fechaUsoPromo DESC
        LIMIT 5";

    $stmt_solicitudes = $conn->prepare($query_solicitudes);
    $stmt_solicitudes->bindParam(':local_id', $local['codLocal']);
    $stmt_solicitudes->execute();
    $solicitudes_recientes = $stmt_solicitudes->fetchAll(PDO::FETCH_ASSOC);

    // Promociones activas
    $query_promociones = "SELECT 
        p.codPromo,
        p.textoPromo,
        p.categoriaCliente,
        p.diasSemana,
        p.fechaDesdePromo,
        p.fechaHastaPromo
        FROM promociones p
        WHERE p.codLocal = :local_id 
        AND p.estadoPromo = 'aprobada'
        AND p.fechaHastaPromo >= CURDATE()
        ORDER BY p.fechaCreacion DESC
        LIMIT 3";

    $stmt_promociones = $conn->prepare($query_promociones);
    $stmt_promociones->bindParam(':local_id', $local['codLocal']);
    $stmt_promociones->execute();
    $promociones_activas = $stmt_promociones->fetchAll(PDO::FETCH_ASSOC);
}

$pageTitle = "Panel de Dueño";
require_once '../includes/header-panel.php';
?>

<div class="container-fluid py-4">
    <!-- Header de bienvenida -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Panel del Dueño</h1>
            <p class="text-muted mb-0">
                <?php if ($local): ?>
                    Gestión de <?= htmlspecialchars($local['nombreLocal']) ?>
                <?php else: ?>
                    Bienvenido a tu panel de control
                <?php endif; ?>
            </p>
        </div>
        <?php if ($local): ?>
            <div class="bg-light p-3 rounded">
                <small class="text-muted">Código del Local</small>
                <div class="badge-status badge-approved mt-1">
                    L-<?= $local['codLocal'] ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Información del Local -->
    <?php if ($local): ?>
        <div class="card card-panel mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="text-primary mb-2"><?= htmlspecialchars($local['nombreLocal']) ?></h4>
                        <p class="text-muted mb-1">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <?= htmlspecialchars($local['ubicacionLocal']) ?>
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-tag me-2"></i>
                            <?= htmlspecialchars($local['rubroLocal']) ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>Activo
                        </span>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            No tienes un local activo asignado. <a href="mi_local.php" class="alert-link">Configura tu local</a>.
        </div>
    <?php endif; ?>

    <!-- Estadísticas -->
    <?php if ($local && $stats): ?>
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card card-panel h-100">
                    <div class="card-body text-center">
                        <div class="text-primary mb-2">
                            <i class="fas fa-tags fa-2x"></i>
                        </div>
                        <h3 class="text-primary"><?= $stats['promociones_activas'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Promociones Activas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card card-panel h-100">
                    <div class="card-body text-center">
                        <div class="text-success mb-2">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                        <h3 class="text-success"><?= $stats['solicitudes_hoy'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Solicitudes Hoy</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card card-panel h-100">
                    <div class="card-body text-center">
                        <div class="text-info mb-2">
                            <i class="fas fa-percentage fa-2x"></i>
                        </div>
                        <h3 class="text-info"><?= $stats['tasa_aceptacion'] ?? 0 ?>%</h3>
                        <p class="text-muted mb-0">Tasa de Aceptación</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card card-panel h-100">
                    <div class="card-body text-center">
                        <div class="text-warning mb-2">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <h3 class="text-warning"><?= $stats['clientes_unicos'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Clientes Únicos</p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- En la sección de Solicitudes Recientes del panel -->
    <div class="row">
        <!-- Solicitudes Recientes -->
        <div class="col-lg-6 mb-4">
            <div class="card card-panel h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>Solicitudes Recientes
                    </h5>
                    <a href="mis_solicitudes.php" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body">
                    <?php if (empty($solicitudes_recientes)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay solicitudes pendientes</h5>
                            <p class="text-muted">Las solicitudes de clientes aparecerán aquí</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Promoción</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($solicitudes_recientes as $solicitud): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($solicitud['cliente']) ?></strong>
                                            </td>
                                            <td>
                                                <small><?= htmlspecialchars($solicitud['textoPromo']) ?></small>
                                            </td>
                                            <td>
                                                <small><?= date('d/m/Y H:i', strtotime($solicitud['fechaUsoPromo'])) ?></small>
                                            </td>
                                            <td>
                                                <!-- FORMULARIO CORREGIDO - usa los mismos nombres que en mis_solicitudes.php -->
                                                <form method="POST" action="mis_solicitudes.php" class="d-inline">
                                                    <input type="hidden" name="codUso" value="<?= $solicitud['codUso'] ?>">
                                                    <button type="submit" name="aceptar_solicitud"
                                                        class="btn btn-success btn-sm"
                                                        onclick="return confirm('¿Aceptar solicitud de <?= htmlspecialchars($solicitud['cliente']) ?>?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="mis_solicitudes.php" class="d-inline ms-1">
                                                    <input type="hidden" name="codUso" value="<?= $solicitud['codUso'] ?>">
                                                    <button type="submit" name="rechazar_solicitud"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('¿Rechazar solicitud de <?= htmlspecialchars($solicitud['cliente']) ?>?')">
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
                                <a href="mis_promociones.php" class="btn btn-outline-primary btn-lg w-100 h-100 py-3">
                                    <i class="fas fa-plus fa-2x mb-2"></i>
                                    <br>
                                    <span>Nueva Promoción</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="mis_promociones.php" class="btn btn-outline-success btn-lg w-100 h-100 py-3">
                                    <i class="fas fa-tags fa-2x mb-2"></i>
                                    <br>
                                    <span>Mis Promociones</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="mis_solicitudes.php" class="btn btn-outline-info btn-lg w-100 h-100 py-3">
                                    <i class="fas fa-clipboard-check fa-2x mb-2"></i>
                                    <br>
                                    <span>Solicitudes</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="mi_local.php" class="btn btn-outline-warning btn-lg w-100 h-100 py-3">
                                    <i class="fas fa-store fa-2x mb-2"></i>
                                    <br>
                                    <span>Mi Local</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Animación de números en estadísticas
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

            // Efectos hover para las tarjetas
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-5px)';
                });

                card.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Animación de entrada para las tarjetas
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

            // Confirmación para acciones
            const deleteButtons = document.querySelectorAll('.btn-danger');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    if (!confirm('¿Estás seguro de que quieres realizar esta acción?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>

    <?php require_once '../includes/footer-panel.php'; ?>