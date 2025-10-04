<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess(['dueño de local']);

$database = new Database();
$conn = $database->getConnection();
$user_id = $_SESSION['user_id'];

// Obtener el local del dueño
$query = "SELECT codLocal, nombreLocal FROM locales WHERE codUsuario = :user_id AND estado = 'activo'";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$local = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$local) {
    header('Location: mis_promociones.php?error=no_local');
    exit();
}

$local_id = $local['codLocal'];
$local_nombre = $local['nombreLocal'];

// Obtener parámetros de filtro
$fecha_desde = $_GET['fecha_desde'] ?? date('Y-m-01'); // Primer día del mes
$fecha_hasta = $_GET['fecha_hasta'] ?? date('Y-m-d');
$filtro_promocion = $_GET['promocion'] ?? 'todas';

// Estadísticas generales
$query_stats = "SELECT 
    COUNT(DISTINCT up.codUso) as total_usos,
    COUNT(DISTINCT up.codCliente) as clientes_unicos,
    COUNT(DISTINCT CASE WHEN up.estado = 'aceptada' THEN up.codUso END) as usos_aceptados,
    COUNT(DISTINCT CASE WHEN up.estado = 'rechazada' THEN up.codUso END) as usos_rechazados,
    COUNT(DISTINCT CASE WHEN up.estado = 'enviada' THEN up.codUso END) as usos_pendientes
    FROM uso_promociones up
    JOIN promociones p ON up.codPromo = p.codPromo
    WHERE p.codLocal = :local_id
    AND DATE(up.fechaUsoPromo) BETWEEN :fecha_desde AND :fecha_hasta";

$stmt_stats = $conn->prepare($query_stats);
$stmt_stats->bindParam(':local_id', $local_id);
$stmt_stats->bindParam(':fecha_desde', $fecha_desde);
$stmt_stats->bindParam(':fecha_hasta', $fecha_hasta);
$stmt_stats->execute();
$stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);

// Estadísticas por promoción
$query_promociones = "SELECT 
    p.codPromo,
    p.textoPromo,
    p.estadoPromo,
    p.categoriaCliente,
    COUNT(up.codUso) as total_usos,
    COUNT(CASE WHEN up.estado = 'aceptada' THEN 1 END) as usos_aceptados,
    COUNT(CASE WHEN up.estado = 'rechazada' THEN 1 END) as usos_rechazados,
    COUNT(CASE WHEN up.estado = 'enviada' THEN 1 END) as usos_pendientes
    FROM promociones p
    LEFT JOIN uso_promociones up ON p.codPromo = up.codPromo 
        AND DATE(up.fechaUsoPromo) BETWEEN :fecha_desde AND :fecha_hasta
    WHERE p.codLocal = :local_id";

if ($filtro_promocion != 'todas') {
    $query_promociones .= " AND p.codPromo = :filtro_promocion";
}

$query_promociones .= " GROUP BY p.codPromo ORDER BY total_usos DESC";

$stmt_promociones = $conn->prepare($query_promociones);
$stmt_promociones->bindParam(':local_id', $local_id);
$stmt_promociones->bindParam(':fecha_desde', $fecha_desde);
$stmt_promociones->bindParam(':fecha_hasta', $fecha_hasta);
if ($filtro_promocion != 'todas') {
    $stmt_promociones->bindParam(':filtro_promocion', $filtro_promocion);
}
$stmt_promociones->execute();
$promociones_stats = $stmt_promociones->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de promociones para el filtro
$query_lista_promociones = "SELECT codPromo, textoPromo FROM promociones 
                           WHERE codLocal = :local_id ORDER BY textoPromo";
$stmt_lista = $conn->prepare($query_lista_promociones);
$stmt_lista->bindParam(':local_id', $local_id);
$stmt_lista->execute();
$lista_promociones = $stmt_lista->fetchAll(PDO::FETCH_ASSOC);

// Estadísticas por categoría de cliente
$query_categorias = "SELECT 
    p.categoriaCliente,
    COUNT(up.codUso) as total_usos,
    COUNT(CASE WHEN up.estado = 'aceptada' THEN 1 END) as usos_aceptados
    FROM promociones p
    LEFT JOIN uso_promociones up ON p.codPromo = up.codPromo 
        AND DATE(up.fechaUsoPromo) BETWEEN :fecha_desde AND :fecha_hasta
    WHERE p.codLocal = :local_id
    GROUP BY p.categoriaCliente ORDER BY total_usos DESC";

$stmt_categorias = $conn->prepare($query_categorias);
$stmt_categorias->bindParam(':local_id', $local_id);
$stmt_categorias->bindParam(':fecha_desde', $fecha_desde);
$stmt_categorias->bindParam(':fecha_hasta', $fecha_hasta);
$stmt_categorias->execute();
$categorias_stats = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Estadísticas";
require_once '../includes/header-panel.php';
?>

<style>
    :root {
        --primary-purple: #8B5CF6;
        --secondary-purple: #A855F7;
        --accent-green: #10B981;
        --accent-blue: #3B82F6;
        --accent-orange: #F59E0B;
        --accent-red: #EF4444;
        --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --card-bg: rgba(255, 255, 255, 0.95);
        --text-primary: #1F2937;
        --text-secondary: #6B7280;
        --border-color: rgba(255, 255, 255, 0.2);
        --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.1);
        --shadow-medium: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    body {
        background: var(--bg-gradient);
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .dashboard-header {
        background: var(--card-bg);
        border-radius: 24px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--border-color);
        backdrop-filter: blur(20px);
    }

    .store-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .store-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .store-details h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .store-subtitle {
        color: var(--text-secondary);
        margin: 0.25rem 0 0 0;
        font-size: 0.875rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--card-bg);
        border-radius: 20px;
        padding: 1.75rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--border-color);
        backdrop-filter: blur(20px);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-purple), var(--secondary-purple));
        border-radius: 20px 20px 0 0;
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .stat-label {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-secondary);
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-change {
        font-size: 0.8rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .stat-change.positive {
        color: var(--accent-green);
    }

    .filter-section {
        background: var(--card-bg);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--border-color);
        backdrop-filter: blur(20px);
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        align-items: end;
    }

    .filter-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .filter-select, .filter-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid rgba(139, 92, 246, 0.1);
        border-radius: 12px;
        background: white;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .filter-select:focus, .filter-input:focus {
        outline: none;
        border-color: var(--primary-purple);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .btn-filter {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    }

    .content-section {
        background: var(--card-bg);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--border-color);
        backdrop-filter: blur(20px);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .section-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .progress-container {
        margin-bottom: 1.5rem;
    }

    .progress-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .progress-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .progress-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .progress-dot.accepted { background: var(--accent-green); }
    .progress-dot.rejected { background: var(--accent-red); }
    .progress-dot.pending { background: var(--accent-orange); }

    .progress-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-primary);
    }

    .progress-value {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: rgba(139, 92, 246, 0.1);
        border-radius: 4px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .progress-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.6s ease;
    }

    .progress-fill.accepted { background: var(--accent-green); }
    .progress-fill.rejected { background: var(--accent-red); }
    .progress-fill.pending { background: var(--accent-orange); }

    .table-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern thead th {
        background: rgba(139, 92, 246, 0.05);
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-primary);
        border-bottom: 2px solid rgba(139, 92, 246, 0.1);
    }

    .table-modern thead th:first-child {
        border-radius: 12px 0 0 0;
    }

    .table-modern thead th:last-child {
        border-radius: 0 12px 0 0;
    }

    .table-modern tbody td {
        padding: 1rem;
        border-bottom: 1px solid rgba(139, 92, 246, 0.05);
        font-size: 0.875rem;
        color: var(--text-primary);
    }

    .table-modern tbody tr:hover {
        background: rgba(139, 92, 246, 0.02);
    }

    .badge-modern {
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-success {
        background: rgba(16, 185, 129, 0.1);
        color: var(--accent-green);
    }

    .badge-warning {
        background: rgba(245, 158, 11, 0.1);
        color: var(--accent-orange);
    }

    .badge-danger {
        background: rgba(239, 68, 68, 0.1);
        color: var(--accent-red);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .filter-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="store-info">
            <div class="store-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="store-details">
                <h1>Estadísticas</h1>
                <p class="store-subtitle"><?php echo htmlspecialchars($local_nombre); ?></p>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filter-section">
        <form method="GET" class="filter-row">
            <div class="filter-group">
                <label><i class="fas fa-calendar"></i> Fecha Desde</label>
                <input type="date" name="fecha_desde" class="filter-select" value="<?= $fecha_desde ?>">
            </div>

            <div class="filter-group">
                <label><i class="fas fa-calendar"></i> Fecha Hasta</label>
                <input type="date" name="fecha_hasta" class="filter-select" value="<?= $fecha_hasta ?>">
            </div>

            <div class="filter-group">
                <label><i class="fas fa-tag"></i> Promoción</label>
                <select name="promocion" class="filter-select">
                    <option value="todas">Todas las promociones</option>
                    <?php foreach ($lista_promociones as $promo): ?>
                        <option value="<?= $promo['codPromo'] ?>" <?= $filtro_promocion == $promo['codPromo'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($promo['textoPromo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Aplicar
                </button>
            </div>
        </form>
    </div>

    <!-- Estadísticas Generales -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-label">Total Usos</div>
                <div class="stat-icon" style="background: var(--accent-blue);">
                    <i class="fas fa-chart-bar"></i>
                </div>
            </div>
            <div class="stat-number"><?= $stats['total_usos'] ?></div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                Este período
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-label">Clientes Únicos</div>
                <div class="stat-icon" style="background: var(--accent-green);">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-number"><?= $stats['clientes_unicos'] ?></div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                Activos
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-label">Tasa de Aceptación</div>
                <div class="stat-icon" style="background: var(--accent-green);">
                    <i class="fas fa-percentage"></i>
                </div>
            </div>
            <div class="stat-number">
                <?php 
                $tasa = $stats['total_usos'] > 0 ? round(($stats['usos_aceptados'] / $stats['total_usos']) * 100, 1) : 0;
                echo $tasa;
                ?>%
            </div>
            <div class="stat-change positive">
                <i class="fas fa-check"></i>
                <?= $stats['usos_aceptados'] ?> aceptados
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-label">Solicitudes Hoy</div>
                <div class="stat-icon" style="background: var(--accent-orange);">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-number"><?= $stats['usos_pendientes'] ?></div>
            <div class="stat-change">
                <i class="fas fa-hourglass-half"></i>
                Pendientes
            </div>
        </div>
    </div>

    <!-- Distribución de Estados -->
    <div class="content-section">
        <div class="section-header">
            <div class="section-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <h3 class="section-title">Distribución de Estados</h3>
        </div>
        
        <div class="progress-container">
            <?php
            $total = $stats['total_usos'];
            $porcentaje_aceptadas = $total > 0 ? ($stats['usos_aceptados'] / $total) * 100 : 0;
            $porcentaje_rechazadas = $total > 0 ? ($stats['usos_rechazados'] / $total) * 100 : 0;
            $porcentaje_pendientes = $total > 0 ? ($stats['usos_pendientes'] / $total) * 100 : 0;
            ?>

            <div class="progress-item">
                <div class="progress-info">
                    <div class="progress-dot accepted"></div>
                    <div class="progress-label">Aceptadas</div>
                </div>
                <div class="progress-value"><?= $stats['usos_aceptados'] ?> (<?= round($porcentaje_aceptadas, 1) ?>%)</div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill accepted" style="width: <?= $porcentaje_aceptadas ?>%"></div>
            </div>

            <div class="progress-item">
                <div class="progress-info">
                    <div class="progress-dot rejected"></div>
                    <div class="progress-label">Rechazadas</div>
                </div>
                <div class="progress-value"><?= $stats['usos_rechazados'] ?> (<?= round($porcentaje_rechazadas, 1) ?>%)</div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill rejected" style="width: <?= $porcentaje_rechazadas ?>%"></div>
            </div>

            <div class="progress-item">
                <div class="progress-info">
                    <div class="progress-dot pending"></div>
                    <div class="progress-label">Pendientes</div>
                </div>
                <div class="progress-value"><?= $stats['usos_pendientes'] ?> (<?= round($porcentaje_pendientes, 1) ?>%)</div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill pending" style="width: <?= $porcentaje_pendientes ?>%"></div>
            </div>
        </div>
    </div>

    <!-- Estadísticas por Promoción -->
    <div class="content-section">
        <div class="section-header">
            <div class="section-icon">
                <i class="fas fa-tags"></i>
            </div>
            <h3 class="section-title">Estadísticas por Promoción</h3>
        </div>
        
        <div style="overflow-x: auto;">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Promoción</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Total Usos</th>
                        <th>Aceptadas</th>
                        <th>Efectividad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($promociones_stats)): ?>
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fas fa-chart-bar"></i>
                                <p>No hay datos disponibles para el período seleccionado</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($promociones_stats as $promo): ?>
                            <?php
                            $total_promo = $promo['total_usos'];
                            $efectividad = $total_promo > 0 ? ($promo['usos_aceptados'] / $total_promo) * 100 : 0;
                            ?>
                            <tr>
                                <td>
                                    <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; font-weight: 500;">
                                        <?= htmlspecialchars($promo['textoPromo']) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-modern badge-<?= strtolower($promo['categoriaCliente']) == 'inicial' ? 'success' : (strtolower($promo['categoriaCliente']) == 'medium' ? 'warning' : 'danger') ?>">
                                        <?= $promo['categoriaCliente'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $badge_class = [
                                        'pendiente' => 'warning',
                                        'aprobada' => 'success',
                                        'denegada' => 'danger'
                                    ][$promo['estadoPromo']] ?? 'warning';
                                    ?>
                                    <span class="badge-modern badge-<?= $badge_class ?>">
                                        <?= ucfirst($promo['estadoPromo']) ?>
                                    </span>
                                </td>
                                <td><strong><?= $promo['total_usos'] ?></strong></td>
                                <td><?= $promo['usos_aceptados'] ?></td>
                                <td>
                                    <span class="badge-modern badge-<?= $efectividad >= 70 ? 'success' : ($efectividad >= 40 ? 'warning' : 'danger') ?>">
                                        <?= round($efectividad, 1) ?>%
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Estadísticas por Categoría -->
    <div class="content-section">
        <div class="section-header">
            <div class="section-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="section-title">Estadísticas por Categoría</h3>
        </div>
        
        <div style="overflow-x: auto;">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th>Total Usos</th>
                        <th>Usos Aceptados</th>
                        <th>Tasa de Aceptación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias_stats as $categoria): ?>
                        <?php
                        $tasa_aceptacion = $categoria['total_usos'] > 0 ?
                            ($categoria['usos_aceptados'] / $categoria['total_usos']) * 100 : 0;
                        ?>
                        <tr>
                            <td>
                                <span class="badge-modern badge-<?= strtolower($categoria['categoriaCliente']) == 'inicial' ? 'success' : (strtolower($categoria['categoriaCliente']) == 'medium' ? 'warning' : 'danger') ?>">
                                    <?= $categoria['categoriaCliente'] ?>
                                </span>
                            </td>
                            <td><strong><?= $categoria['total_usos'] ?></strong></td>
                            <td><?= $categoria['usos_aceptados'] ?></td>
                            <td>
                                <span class="badge-modern badge-<?= $tasa_aceptacion >= 70 ? 'success' : ($tasa_aceptacion >= 40 ? 'warning' : 'danger') ?>">
                                    <?= round($tasa_aceptacion, 1) ?>%
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animación de las barras de progreso
    const progressBars = document.querySelectorAll('.progress-fill');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const bar = entry.target;
                const originalWidth = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = originalWidth;
                }, 100);
            }
        });
    });

    progressBars.forEach(bar => observer.observe(bar));
    
    // Animación de números
    const statNumbers = document.querySelectorAll('.stat-number');
    statNumbers.forEach(stat => {
        const finalValue = parseInt(stat.textContent);
        if (!isNaN(finalValue)) {
            let currentValue = 0;
            const increment = finalValue / 30;
            const timer = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    stat.textContent = finalValue;
                    clearInterval(timer);
                } else {
                    stat.textContent = Math.floor(currentValue);
                }
            }, 50);
        }
    });
});
</script>

<?php require_once '../includes/footer-panel.php'; ?>