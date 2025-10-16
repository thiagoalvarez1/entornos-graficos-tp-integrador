<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess(['administrador']);

// Obtener datos para reportes
$database = new Database();
$conn = $database->getConnection();

// Estadísticas generales
$query_stats = "SELECT 
    COUNT(DISTINCT CASE WHEN u.tipoUsuario = 'cliente' THEN u.codUsuario END) as total_clientes,
    COUNT(DISTINCT CASE WHEN u.tipoUsuario = 'dueño de local' THEN u.codUsuario END) as total_duenos,
    COUNT(DISTINCT CASE WHEN p.estadoPromo = 'aprobada' AND p.fechaHastaPromo >= CURDATE() THEN p.codPromo END) as promociones_activas,
    COUNT(DISTINCT CASE WHEN DATE(up.fechaUsoPromo) = CURDATE() THEN up.codUso END) as usos_hoy
    FROM usuarios u
    LEFT JOIN promociones p ON 1=1
    LEFT JOIN uso_promociones up ON 1=1";

$stmt_stats = $conn->prepare($query_stats);
$stmt_stats->execute();
$stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);

// Top promociones más usadas
$query_top_promos = "SELECT 
    p.textoPromo,
    l.nombreLocal,
    COUNT(up.codUso) as total_usos
    FROM promociones p
    JOIN locales l ON p.codLocal = l.codLocal
    LEFT JOIN uso_promociones up ON p.codPromo = up.codPromo
    WHERE p.estadoPromo = 'aprobada'
    GROUP BY p.codPromo
    ORDER BY total_usos DESC
    LIMIT 5";

$stmt_top_promos = $conn->prepare($query_top_promos);
$stmt_top_promos->execute();
$top_promociones = $stmt_top_promos->fetchAll(PDO::FETCH_ASSOC);

// Procesar generación de reportes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generar_reporte'])) {
    $tipo_reporte = $_POST['tipo_reporte'];
    $formato = $_POST['formato'];

    // Aquí iría la lógica para generar el reporte en el formato especificado
    // Por ahora simulamos la generación
    switch ($tipo_reporte) {
        case 'promociones':
            $mensaje = "Reporte de promociones generado exitosamente en formato " . strtoupper($formato);
            break;
        case 'usuarios':
            $mensaje = "Reporte de usuarios generado exitosamente en formato " . strtoupper($formato);
            break;
        case 'locales':
            $mensaje = "Reporte de locales generado exitosamente en formato " . strtoupper($formato);
            break;
        case 'usos':
            $mensaje = "Reporte de usos generado exitosamente en formato " . strtoupper($formato);
            break;
        default:
            $mensaje = "Tipo de reporte no válido";
    }

    $_SESSION['reporte_generado'] = $mensaje;
    header('Location: reportes.php');
    exit();
}

$pageTitle = "Reportes y Estadísticas";
require_once '../includes/header-panel.php';
?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Reportes y Estadísticas</h1>
            <p class="text-muted mb-0">Análisis completo del sistema PromoShopping</p>
        </div>
    </div>

    <!-- Alertas -->
    <?php if (isset($_SESSION['reporte_generado'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= $_SESSION['reporte_generado'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['reporte_generado']); ?>
    <?php endif; ?>

    <!-- Estadísticas generales -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card card-panel h-100">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h3 class="text-primary"><?= $stats['total_clientes'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Total Clientes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-panel h-100">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="fas fa-store fa-2x"></i>
                    </div>
                    <h3 class="text-success"><?= $stats['total_duenos'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Dueños de Locales</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-panel h-100">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="fas fa-tags fa-2x"></i>
                    </div>
                    <h3 class="text-info"><?= $stats['promociones_activas'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Promociones Activas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-panel h-100">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="fas fa-ticket-alt fa-2x"></i>
                    </div>
                    <h3 class="text-warning"><?= $stats['usos_hoy'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Usos Hoy</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Promociones -->
        <div class="col-lg-6 mb-4">
            <div class="card card-panel h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy me-2"></i>Top 5 Promociones Más Usadas
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($top_promociones)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay datos de uso</h5>
                            <p class="text-muted">Las estadísticas de uso aparecerán aquí</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Promoción</th>
                                        <th>Local</th>
                                        <th>Usos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($top_promociones as $promo): ?>
                                        <tr>
                                            <td>
                                                <small><?= htmlspecialchars($promo['textoPromo']) ?></small>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-info"><?= htmlspecialchars($promo['nombreLocal']) ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-fire me-1"></i>
                                                    <?= $promo['total_usos'] ?>
                                                </span>
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

        <!-- Generar Reportes -->
        <div class="col-lg-6 mb-4">
            <div class="card card-panel h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-download me-2"></i>Generar Reportes
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" id="reporteForm">
                        <div class="mb-3">
                            <label class="form-label">Tipo de Reporte</label>
                            <select name="tipo_reporte" class="form-select" required>
                                <option value="">Seleccionar tipo...</option>
                                <option value="promociones">Promociones del Sistema</option>
                                <option value="usuarios">Usuarios Registrados</option>
                                <option value="locales">Locales Comerciales</option>
                                <option value="usos">Usos de Promociones</option>
                                <option value="estadisticas">Estadísticas Generales</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Formato</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="formato" value="pdf" id="pdf"
                                    checked>
                                <label class="form-check-label" for="pdf">
                                    <i class="fas fa-file-pdf text-danger me-1"></i> PDF
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="formato" value="excel" id="excel">
                                <label class="form-check-label" for="excel">
                                    <i class="fas fa-file-excel text-success me-1"></i> Excel
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="formato" value="csv" id="csv">
                                <label class="form-check-label" for="csv">
                                    <i class="fas fa-file-csv text-info me-1"></i> CSV
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rango de Fechas (Opcional)</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="date" name="fecha_desde" class="form-control" placeholder="Desde">
                                </div>
                                <div class="col-md-6">
                                    <input type="date" name="fecha_hasta" class="form-control" placeholder="Hasta">
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="generar_reporte" class="btn btn-primary w-100">
                            <i class="fas fa-download me-2"></i>Generar Reporte
                        </button>
                    </form>

                    <!-- Reportes Rápidos -->
                    <div class="mt-4">
                        <h6 class="text-muted mb-3">Reportes Rápidos</h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <form method="POST" class="d-inline w-100">
                                    <input type="hidden" name="tipo_reporte" value="estadisticas">
                                    <input type="hidden" name="formato" value="pdf">
                                    <button type="submit" name="generar_reporte"
                                        class="btn btn-outline-primary w-100 btn-sm">
                                        <i class="fas fa-chart-bar me-1"></i>Resumen PDF
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <form method="POST" class="d-inline w-100">
                                    <input type="hidden" name="tipo_reporte" value="usos">
                                    <input type="hidden" name="formato" value="excel">
                                    <button type="submit" name="generar_reporte"
                                        class="btn btn-outline-success w-100 btn-sm">
                                        <i class="fas fa-file-excel me-1"></i>Usos Excel
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Detalladas -->
    <div class="row">
        <div class="col-12">
            <div class="card card-panel">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Estadísticas Detalladas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="bg-light p-3 rounded">
                                <i class="fas fa-calendar-day fa-2x text-primary mb-2"></i>
                                <h5 class="text-primary"><?= $stats['usos_hoy'] ?? 0 ?></h5>
                                <p class="text-muted mb-0">Usos Hoy</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="bg-light p-3 rounded">
                                <i class="fas fa-calendar-week fa-2x text-success mb-2"></i>
                                <h5 class="text-success"><?= ($stats['usos_hoy'] ?? 0) * 7 ?></h5>
                                <p class="text-muted mb-0">Usos Esta Semana (estimado)</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="bg-light p-3 rounded">
                                <i class="fas fa-percentage fa-2x text-info mb-2"></i>
                                <h5 class="text-info">85%</h5>
                                <p class="text-muted mb-0">Tasa de Éxito</p>
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

        // Validación del formulario de reportes
        const reporteForm = document.getElementById('reporteForm');
        reporteForm.addEventListener('submit', function (e) {
            const tipoReporte = this.tipo_reporte.value;
            if (!tipoReporte) {
                e.preventDefault();
                alert('Por favor selecciona un tipo de reporte');
                return false;
            }

            // Mostrar indicador de carga
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generando...';
            submitBtn.disabled = true;

            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
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

        // Configuración de fechas por defecto
        const today = new Date();
        const lastWeek = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);

        document.querySelector('input[name="fecha_desde"]').valueAsDate = lastWeek;
        document.querySelector('input[name="fecha_hasta"]').valueAsDate = today;
    });
</script>

<?php require_once '../includes/footer-panel.php'; ?>