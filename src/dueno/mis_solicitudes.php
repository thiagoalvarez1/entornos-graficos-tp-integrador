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


<link rel="stylesheet" href="../css/mis_solicitudes.css">


<!-- Content Area -->
<div class="content-area">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Solicitudes</h1>
        <p class="text-muted">Gestiona las solicitudes de promociones de <?= htmlspecialchars($local_nombre) ?></p>
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
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
            <div class="stat-number"><?= $stats['total'] ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-label">Pendientes</div>
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-number"><?= $stats['pendientes'] ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-label">Aceptadas</div>
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-number"><?= $stats['aceptadas'] ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-label">Rechazadas</div>
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
            <div class="stat-number"><?= $stats['rechazadas'] ?></div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="content-section">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-filter"></i>
                Filtrar Solicitudes
            </h3>
        </div>

        <form method="GET" class="filter-row">
            <div class="filter-group">
                <label>Estado</label>
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
                <button type="submit" class="btn-gradient">
                    <i class="fas fa-filter"></i> Aplicar
                </button>
            </div>

            <div class="filter-group">
                <a href="mis_solicitudes.php" class="btn-gradient"
                    style="background: rgba(107, 114, 128, 0.1); color: var(--text-secondary);">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Lista de solicitudes -->
    <div class="content-section">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-list"></i>
                Solicitudes Recientes
            </h3>
            <div style="color: var(--text-secondary); font-size: 0.875rem;">
                <?= count($solicitudes) ?> solicitudes
            </div>
        </div>

        <?php if (empty($solicitudes)): ?>
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h4>No hay solicitudes</h4>
                <p>
                    <?php if (!empty($busqueda) || $filtro_estado != 'todas'): ?>
                        No se encontraron solicitudes con los filtros aplicados
                    <?php else: ?>
                        No hay solicitudes de promociones pendientes
                    <?php endif; ?>
                </p>
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
        // Animación de números en estadísticas
        const statNumbers = document.querySelectorAll('.stat-number');
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

        // Confirmación para rechazar solicitudes
        const rechazarBtns = document.querySelectorAll('button[name="rechazar_solicitud"]');
        rechazarBtns.forEach(btn => {
            btn.addEventListener('click', function (e) {
                if (!confirm('¿Estás seguro de que quieres rechazar esta solicitud?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>

<?php require_once __DIR__ . '/../includes/footer-panel.php'; ?>