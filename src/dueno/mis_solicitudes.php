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

// Procesar aceptar/rechazar solicitud
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['aceptar_solicitud'])) {
        $codUso = $_POST['codUso'];
        $query = "UPDATE uso_promociones SET estado = 'aceptada' WHERE codUso = :codUso AND codPromo IN (SELECT codPromo FROM promociones WHERE codLocal = :local_id)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':codUso', $codUso);
        $stmt->bindParam(':local_id', $local_id);

        if ($stmt->execute()) {
            $success = "Solicitud aceptada correctamente";
        } else {
            $error = "Error al aceptar la solicitud";
        }
    } elseif (isset($_POST['rechazar_solicitud'])) {
        $codUso = $_POST['codUso'];
        $query = "UPDATE uso_promociones SET estado = 'rechazada' WHERE codUso = :codUso AND codPromo IN (SELECT codPromo FROM promociones WHERE codLocal = :local_id)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':codUso', $codUso);
        $stmt->bindParam(':local_id', $local_id);

        if ($stmt->execute()) {
            $success = "Solicitud rechazada correctamente";
        } else {
            $error = "Error al rechazar la solicitud";
        }
    }
}

// Obtener solicitudes con filtros
$filtro_estado = $_GET['estado'] ?? 'enviada';
$busqueda = $_GET['busqueda'] ?? '';

$query = "SELECT 
            up.codUso, 
            up.codCliente, 
            up.codPromo, 
            up.fechaUsoPromo, 
            up.estado,
            u.nombreUsuario as email_cliente,
            p.textoPromo,
            p.categoriaCliente,
            p.estadoPromo as estado_promocion
          FROM uso_promociones up
          JOIN usuarios u ON up.codCliente = u.codUsuario
          JOIN promociones p ON up.codPromo = p.codPromo
          WHERE p.codLocal = :local_id";

$params = [':local_id' => $local_id];

// Aplicar filtros
if ($filtro_estado != 'todas') {
    $query .= " AND up.estado = :estado";
    $params[':estado'] = $filtro_estado;
}

if (!empty($busqueda)) {
    $query .= " AND (u.nombreUsuario LIKE :busqueda OR p.textoPromo LIKE :busqueda)";
    $params[':busqueda'] = "%$busqueda%";
}

$query .= " ORDER BY up.fechaUsoPromo DESC";

$stmt = $conn->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Estadísticas
$query_stats = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN estado = 'enviada' THEN 1 ELSE 0 END) as pendientes,
    SUM(CASE WHEN estado = 'aceptada' THEN 1 ELSE 0 END) as aceptadas,
    SUM(CASE WHEN estado = 'rechazada' THEN 1 ELSE 0 END) as rechazadas
    FROM uso_promociones up
    JOIN promociones p ON up.codPromo = p.codPromo
    WHERE p.codLocal = :local_id";
$stmt_stats = $conn->prepare($query_stats);
$stmt_stats->bindParam(':local_id', $local_id);
$stmt_stats->execute();
$stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);

$pageTitle = "Solicitudes";
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

    .alert {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        border-left: 4px solid var(--accent-green);
        background: rgba(16, 185, 129, 0.05);
    }

    .alert-danger {
        border-left: 4px solid var(--accent-red);
        background: rgba(239, 68, 68, 0.05);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--card-bg);
        border-radius: 20px;
        padding: 1.5rem;
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
        border-radius: 20px 20px 0 0;
    }

    .stat-card:nth-child(1)::before {
        background: var(--accent-blue);
    }

    .stat-card:nth-child(2)::before {
        background: var(--accent-orange);
    }

    .stat-card:nth-child(3)::before {
        background: var(--accent-green);
    }

    .stat-card:nth-child(4)::before {
        background: var(--accent-red);
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

    .filter-select,
    .filter-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid rgba(139, 92, 246, 0.1);
        border-radius: 12px;
        background: white;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .filter-select:focus,
    .filter-input:focus {
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
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--border-color);
        backdrop-filter: blur(20px);
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(139, 92, 246, 0.1);
    }

    .section-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
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

    .view-all-btn {
        background: rgba(139, 92, 246, 0.1);
        color: var(--primary-purple);
        border: 1px solid rgba(139, 92, 246, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .view-all-btn:hover {
        background: var(--primary-purple);
        color: white;
        transform: translateY(-1px);
    }

    .solicitud-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(139, 92, 246, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .solicitud-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--accent-orange);
    }

    .solicitud-card.aceptada::before {
        background: var(--accent-green);
    }

    .solicitud-card.rechazada::before {
        background: var(--accent-red);
    }

    .solicitud-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .solicitud-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .solicitud-info h6 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 0.25rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .solicitud-info p {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .estado-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .estado-enviada {
        background: rgba(245, 158, 11, 0.1);
        color: var(--accent-orange);
    }

    .estado-aceptada {
        background: rgba(16, 185, 129, 0.1);
        color: var(--accent-green);
    }

    .estado-rechazada {
        background: rgba(239, 68, 68, 0.1);
        color: var(--accent-red);
    }

    .solicitud-promocion {
        background: rgba(139, 92, 246, 0.03);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
    }

    .solicitud-promocion strong {
        color: var(--text-primary);
        display: block;
        margin-bottom: 0.5rem;
    }

    .solicitud-promocion .promo-text {
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }

    .solicitud-promocion .promo-details {
        font-size: 0.8rem;
        color: var(--text-secondary);
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .solicitud-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }

    .btn-action {
        padding: 0.625rem 1.25rem;
        border: none;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-aceptar {
        background: linear-gradient(135deg, var(--accent-green), #059669);
        color: white;
    }

    .btn-aceptar:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-rechazar {
        background: linear-gradient(135deg, var(--accent-red), #dc2626);
        color: white;
    }

    .btn-rechazar:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
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

    .empty-state h4 {
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .category-badge {
        padding: 0.25rem 0.6rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .category-inicial {
        background: rgba(16, 185, 129, 0.1);
        color: var(--accent-green);
    }

    .category-medium {
        background: rgba(245, 158, 11, 0.1);
        color: var(--accent-orange);
    }

    .category-premium {
        background: rgba(139, 92, 246, 0.1);
        color: var(--primary-purple);
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-row {
            grid-template-columns: 1fr;
        }

        .solicitud-header {
            flex-direction: column;
            gap: 1rem;
        }

        .solicitud-actions {
            justify-content: stretch;
        }

        .btn-action {
            flex: 1;
        }
    }
</style>

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="store-info">
            <div class="store-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="store-details">
                <h1>Solicitudes</h1>
                <p class="store-subtitle"><?php echo htmlspecialchars($local_nombre); ?></p>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <!-- Estadísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-label">Total Solicitudes</div>
                <div class="stat-icon" style="background: var(--accent-blue);">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
            <div class="stat-number"><?= $stats['total'] ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-label">Solicitudes Hoy</div>
                <div class="stat-icon" style="background: var(--accent-orange);">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-number"><?= $stats['pendientes'] ?></div>
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
                $tasa = $stats['total'] > 0 ? round(($stats['aceptadas'] / $stats['total']) * 100, 1) : 0;
                echo $tasa;
                ?>%
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-label">Clientes Únicos</div>
                <div class="stat-icon" style="background: var(--primary-purple);">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-number">
                <?php
                // Calcular clientes únicos
                $unique_clients = array_unique(array_column($solicitudes, 'codCliente'));
                echo count($unique_clients);
                ?>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filter-section">
        <form method="GET" class="filter-row">
            <div class="filter-group">
                <label>Filtrar por estado</label>
                <select name="estado" class="filter-select">
                    <option value="todas" <?= $filtro_estado == 'todas' ? 'selected' : '' ?>>Todas las solicitudes</option>
                    <option value="enviada" <?= $filtro_estado == 'enviada' ? 'selected' : '' ?>>Pendientes</option>
                    <option value="aceptada" <?= $filtro_estado == 'aceptada' ? 'selected' : '' ?>>Aceptadas</option>
                    <option value="rechazada" <?= $filtro_estado == 'rechazada' ? 'selected' : '' ?>>Rechazadas</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Buscar</label>
                <input type="text" name="busqueda" class="filter-input" placeholder="Email cliente o promoción..."
                    value="<?= htmlspecialchars($busqueda) ?>">
            </div>

            <div class="filter-group">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Aplicar
                </button>
            </div>

            <div class="filter-group">
                <a href="mis_solicitudes.php" class="view-all-btn">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Lista de solicitudes -->
    <div class="content-section">
        <div class="section-header">
            <div class="section-info">
                <div class="section-icon">
                    <i class="fas fa-list"></i>
                </div>
                <h3 class="section-title">Solicitudes Recientes</h3>
            </div>
            <div class="view-all-btn"><?= count($solicitudes) ?> solicitudes</div>
        </div>

        <?php if (empty($solicitudes)): ?>
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h4>No hay solicitudes</h4>
                <p>No se encontraron solicitudes con los filtros aplicados</p>
            </div>
        <?php else: ?>
            <?php foreach ($solicitudes as $solicitud): ?>
                <div class="solicitud-card <?= $solicitud['estado'] ?>">
                    <div class="solicitud-header">
                        <div class="solicitud-info">
                            <h6>
                                <i class="fas fa-user"></i>
                                <?= htmlspecialchars($solicitud['email_cliente']) ?>
                            </h6>
                            <p>
                                <i class="fas fa-calendar"></i>
                                <?= date('d/m/Y H:i', strtotime($solicitud['fechaUsoPromo'])) ?>
                            </p>
                        </div>
                        <span class="estado-badge estado-<?= $solicitud['estado'] ?>">
                            <?= ucfirst($solicitud['estado']) ?>
                        </span>
                    </div>

                    <div class="solicitud-promocion">
                        <strong>Promoción:</strong>
                        <div class="promo-text"><?= htmlspecialchars($solicitud['textoPromo']) ?></div>
                        <div class="promo-details">
                            <span>Categoría: <span
                                    class="category-badge category-<?= strtolower($solicitud['categoriaCliente']) ?>"><?= $solicitud['categoriaCliente'] ?></span></span>
                            <span>Estado promoción: <?= ucfirst($solicitud['estado_promocion']) ?></span>
                        </div>
                    </div>

                    <?php if ($solicitud['estado'] == 'enviada'): ?>
                        <div class="solicitud-actions">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="codUso" value="<?= $solicitud['codUso'] ?>">
                                <button type="submit" name="aceptar_solicitud" class="btn-action btn-aceptar">
                                    <i class="fas fa-check"></i> Aceptar
                                </button>
                            </form>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="codUso" value="<?= $solicitud['codUso'] ?>">
                                <button type="submit" name="rechazar_solicitud" class="btn-action btn-rechazar">
                                    <i class="fas fa-times"></i> Rechazar
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Animación de entrada para las tarjetas
        const cards = document.querySelectorAll('.solicitud-card');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                }
            });
        });

        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease';
            observer.observe(card);
        });

        // Animación de números en estadísticas
        const statNumbers = document.querySelectorAll('.stat-number');
        statNumbers.forEach(stat => {
            const text = stat.textContent;
            const finalValue = parseInt(text);

            if (!isNaN(finalValue) && finalValue > 0) {
                let currentValue = 0;
                const increment = Math.ceil(finalValue / 30);
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        stat.textContent = text; // Mantener formato original (ej: porcentaje)
                        clearInterval(timer);
                    } else {
                        stat.textContent = currentValue + (text.includes('%') ? '%' : '');
                    }
                }, 50);
            }
        });

        // Confirmación para rechazar solicitudes
        const rechazarBtns = document.querySelectorAll('button[name="rechazar_solicitud"]');
        rechazarBtns.forEach(btn => {
            btn.addEventListener('click', function (e) {
                if (!confirm('¿Estás seguro de que quieres rechazar esta solicitud?')) {
                    e.preventDefault();
                }
            });
        });

        // Auto-refresh para solicitudes pendientes cada 30 segundos
        if (window.location.search.includes('estado=enviada') || !window.location.search.includes('estado=')) {
            setInterval(() => {
                // Solo si hay solicitudes pendientes y no hay formularios siendo enviados
                const pendingRequests = document.querySelectorAll('.estado-enviada').length;
                if (pendingRequests > 0 && !document.querySelector('form[method="POST"]').classList.contains('submitting')) {
                    // Actualizar silenciosamente
                    fetch(window.location.href)
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const newDoc = parser.parseFromString(html, 'text/html');
                            const newContent = newDoc.querySelector('.content-section');
                            const currentContent = document.querySelector('.content-section');
                            if (newContent && currentContent) {
                                currentContent.innerHTML = newContent.innerHTML;
                            }
                        })
                        .catch(() => {
                            // Silenciar errores de red
                        });
                }
            }, 30000);
        }

        // Marcar formulario como enviándose
        document.querySelectorAll('form[method="POST"]').forEach(form => {
            form.addEventListener('submit', function () {
                this.classList.add('submitting');
                const btn = this.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
            });
        });
    });
</script>

<?php require_once '../includes/footer-panel.php'; ?>