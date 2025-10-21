<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

$pageTitle = "Locales - Bandera";
require_once 'includes/header.php';

// Conexión a la base de datos
$database = new Database();
$conn = $database->getConnection();

$locales = [];
$filtros = [
    'rubro' => $_GET['rubro'] ?? '',
    'busqueda' => $_GET['busqueda'] ?? ''
];

try {
    // Query base
    $query = "SELECT l.*, COUNT(p.codPromo) as total_promociones 
              FROM locales l 
              LEFT JOIN promociones p ON l.codLocal = p.codLocal AND p.estadoPromo = 'aprobada'
              WHERE l.estado = 'activo'";
    $params = [];

    // Aplicar filtros
    if (!empty($filtros['rubro'])) {
        $query .= " AND l.rubroLocal = :rubro";
        $params[':rubro'] = $filtros['rubro'];
    }

    if (!empty($filtros['busqueda'])) {
        $query .= " AND (l.nombreLocal LIKE :busqueda OR l.rubroLocal LIKE :busqueda)";
        $params[':busqueda'] = '%' . $filtros['busqueda'] . '%';
    }

    $query .= " GROUP BY l.codLocal ORDER BY l.nombreLocal";

    $stmt = $conn->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    $locales = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener rubros únicos para el filtro
    $query_rubros = "SELECT DISTINCT rubroLocal FROM locales WHERE estado = 'activo' ORDER BY rubroLocal";
    $stmt_rubros = $conn->query($query_rubros);
    $rubros = $stmt_rubros->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>No se pudieron cargar los locales: " . $e->getMessage() . "</div>";
}
?>

<link rel="stylesheet" href="css/locales.css">

<div class="page-container">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">Locales del Shopping</h1>
        <p class="page-subtitle">
            Descubre todos los locales disponibles y explora sus promociones exclusivas.
            Encuentra exactamente lo que buscas en nuestro centro comercial.
        </p>
    </div>

    <!-- Filtros -->
    <div class="filters-section">
        <form method="GET" class="filters-row">
            <div class="filter-group">
                <label class="filter-label">
                    <i class="fas fa-search"></i>
                    Buscar local
                </label>
                <input type="text" name="busqueda" class="filter-input" placeholder="Nombre del local..."
                    value="<?= htmlspecialchars($filtros['busqueda']) ?>">
            </div>

            <div class="filter-group">
                <label class="filter-label">
                    <i class="fas fa-tags"></i>
                    Filtrar por rubro
                </label>
                <select name="rubro" class="filter-select">
                    <option value="">Todos los rubros</option>
                    <?php foreach ($rubros as $rubro): ?>
                        <option value="<?= htmlspecialchars($rubro) ?>" <?= $filtros['rubro'] === $rubro ? 'selected' : '' ?>>
                            <?= htmlspecialchars($rubro) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <div class="filter-buttons">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i>
                        Aplicar filtros
                    </button>
                    <a href="locales.php" class="btn-filter btn-clear">
                        <i class="fas fa-times"></i>
                        Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Barra de estadísticas -->
    <div class="stats-bar">
        <div class="stats-info">
            Mostrando <span class="stats-count"><?= count($locales) ?></span>
            <?= count($locales) === 1 ? 'local' : 'locales' ?>
            <?php if (!empty($filtros['rubro']) || !empty($filtros['busqueda'])): ?>
                con los filtros aplicados
            <?php endif; ?>
        </div>
        <div class="stats-info">
            <i class="fas fa-store"></i>
            Centro Comercial Bandera Shopping
        </div>
    </div>

    <!-- Grid de locales -->
    <?php if (!empty($locales)): ?>
        <div class="locales-grid">
            <?php foreach ($locales as $index => $local): ?>
                <div class="local-card" style="animation-delay: <?= ($index * 0.1) ?>s">
                    <div class="local-header">
                        <div class="local-icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <h3 class="local-title">
                            <?= htmlspecialchars($local['nombreLocal']) ?>
                        </h3>
                    </div>

                    <div class="local-info">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <span><?= htmlspecialchars($local['ubicacionLocal']) ?></span>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <span><?= htmlspecialchars($local['direccion'] ?? 'Dirección no disponible') ?></span>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <span class="rubro-badge">
                                <?= htmlspecialchars($local['rubroLocal']) ?>
                            </span>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-gift"></i>
                            </div>
                            <span>
                                <span class="promociones-count">
                                    <?= $local['total_promociones'] ?>
                                </span>
                                promociones activas
                            </span>
                        </div>
                    </div>

                    <div class="local-actions">
                        <?php if ($local['total_promociones'] > 0): ?>
                            <a href="promociones.php?id=<?= $local['codLocal'] ?>" class="btn-promociones">
                                <i class="fas fa-eye"></i>
                                Ver promociones
                            </a>
                        <?php else: ?>
                            <button class="btn-promociones" disabled>
                                <i class="fas fa-info-circle"></i>
                                Sin promociones activas
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-store-slash"></i>
            </div>
            <h3 class="empty-title">No se encontraron locales</h3>
            <p class="empty-text">
                <?php if (!empty($filtros['busqueda']) || !empty($filtros['rubro'])): ?>
                    No hay locales que coincidan con los filtros aplicados.<br>
                    Intenta modificar los criterios de búsqueda.
                <?php else: ?>
                    No hay locales disponibles en este momento.<br>
                    ¡Pronto habrá nuevas opciones para ti!
                <?php endif; ?>
            </p>
        </div>
    <?php endif; ?>
</div>

<!-- Botón flotante -->
<div class="floating-action" onclick="scrollToTop()" title="Volver arriba">
    <i class="fas fa-chevron-up"></i>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Smooth scroll to top
        window.scrollToTop = function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        };

        // Show/hide floating action button
        const floatingBtn = document.querySelector('.floating-action');

        window.addEventListener('scroll', function () {
            if (window.scrollY > 300) {
                floatingBtn.style.opacity = '1';
                floatingBtn.style.transform = 'translateY(0) scale(1)';
            } else {
                floatingBtn.style.opacity = '0';
                floatingBtn.style.transform = 'translateY(20px) scale(0.8)';
            }
        });

        // Enhanced hover effects
        const localCards = document.querySelectorAll('.local-card');
        localCards.forEach(card => {
            card.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-12px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Auto-focus search input when typing
        document.addEventListener('keydown', function (e) {
            const searchInput = document.querySelector('input[name="busqueda"]');
            if (e.key.match(/^[a-zA-Z0-9]$/) && document.activeElement !== searchInput) {
                searchInput.focus();
                searchInput.value = e.key;
            }
        });

        // Filter form enhancements
        const filterForm = document.querySelector('.filters-section form');
        const searchInput = document.querySelector('input[name="busqueda"]');

        // Auto-submit after typing delay
        let searchTimeout;
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Auto-submit could be enabled here if desired
                // filterForm.submit();
            }, 1000);
        });

        // Loading state for buttons
        const filterBtn = document.querySelector('.btn-filter[type="submit"]');
        filterForm.addEventListener('submit', function () {
            filterBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Filtrando...';
            filterBtn.disabled = true;
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all cards for scroll animations
        localCards.forEach(card => {
            observer.observe(card);
        });

        // Keyboard navigation
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                // Clear search when pressing escape
                if (searchInput.value) {
                    searchInput.value = '';
                    searchInput.focus();
                }
            }
        });

        // Stats counter animation
        const statsCount = document.querySelector('.stats-count');
        if (statsCount) {
            const finalValue = parseInt(statsCount.textContent);
            let currentValue = 0;
            const increment = Math.max(1, Math.ceil(finalValue / 30));

            const counter = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    statsCount.textContent = finalValue;
                    clearInterval(counter);
                } else {
                    statsCount.textContent = currentValue;
                }
            }, 50);
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>