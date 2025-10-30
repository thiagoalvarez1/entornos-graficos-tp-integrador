<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

$pageTitle = "Promociones";
require_once 'includes/header.php';

// Función para convertir números a nombres de días
function convertirDias($diasSemana)
{
    $diasMap = [
        '1' => 'Lunes',
        '2' => 'Martes',
        '3' => 'Miércoles',
        '4' => 'Jueves',
        '5' => 'Viernes',
        '6' => 'Sábado',
        '7' => 'Domingo'
    ];

    if (strpos($diasSemana, ',') !== false) {
        $numerosDias = explode(',', $diasSemana);
        $nombresDias = [];
        foreach ($numerosDias as $numero) {
            $numero = trim($numero);
            if (isset($diasMap[$numero])) {
                $nombresDias[] = $diasMap[$numero];
            }
        }
        return implode(', ', $nombresDias);
    }

    return isset($diasMap[$diasSemana]) ? $diasMap[$diasSemana] : $diasSemana;
}

$database = new Database();
$conn = $database->getConnection();

// Configuración de paginación - CORREGIDO
$por_pagina = 5;
$pagina_actual = isset($_GET['pagina']) ? max(1, (int) $_GET['pagina']) : 1; // Asegurar mínimo 1

// Filtros
$filtros = [
    'busqueda' => $_GET['busqueda'] ?? '',
    'categoria' => $_GET['categoria'] ?? '',
    'local' => $_GET['local'] ?? ''
];

$promociones = [];
$total_promociones = 0;
$total_paginas = 1;

try {
    // Query para contar total con filtros
    $query_count = "
        SELECT COUNT(*) as total
        FROM promociones p
        INNER JOIN locales l ON p.codLocal = l.codLocal
        WHERE p.estadoPromo = 'aprobada'
        AND p.fechaHastaPromo >= CURDATE()
    ";
    $params = [];

    // Aplicar filtros al count
    if (!empty($filtros['busqueda'])) {
        $query_count .= " AND (p.textoPromo LIKE :busqueda OR l.nombreLocal LIKE :busqueda)";
        $params[':busqueda'] = '%' . $filtros['busqueda'] . '%';
    }

    if (!empty($filtros['categoria'])) {
        $query_count .= " AND p.categoriaCliente = :categoria";
        $params[':categoria'] = $filtros['categoria'];
    }

    if (!empty($filtros['local'])) {
        $query_count .= " AND l.nombreLocal = :local";
        $params[':local'] = $filtros['local'];
    }

    // Obtener total de promociones
    $stmt_count = $conn->prepare($query_count);
    foreach ($params as $key => $value) {
        $stmt_count->bindValue($key, $value);
    }
    $stmt_count->execute();
    $total_promociones = $stmt_count->fetch(PDO::FETCH_ASSOC)['total'];
    $total_paginas = ceil($total_promociones / $por_pagina);

    // CORRECCIÓN: Asegurar que la página actual esté dentro del rango válido
    if ($pagina_actual < 1)
        $pagina_actual = 1;
    if ($total_paginas > 0 && $pagina_actual > $total_paginas) {
        $pagina_actual = $total_paginas;
    }

    // CORRECCIÓN: Calcular offset solo si hay resultados
    $offset = ($pagina_actual - 1) * $por_pagina;
    if ($offset < 0)
        $offset = 0;

    // Query para obtener promociones con filtros y paginación - CORREGIDO
    $query = "
        SELECT 
            p.codPromo, 
            p.textoPromo, 
            p.fechaDesdePromo, 
            p.fechaHastaPromo, 
            p.diasSemana,
            p.categoriaCliente,
            l.nombreLocal,
            l.ubicacionLocal,
            l.rubroLocal
        FROM 
            promociones p
        INNER JOIN 
            locales l ON p.codLocal = l.codLocal
        WHERE 
            p.estadoPromo = 'aprobada'
        AND 
            p.fechaHastaPromo >= CURDATE()
    ";

    // Aplicar filtros
    if (!empty($filtros['busqueda'])) {
        $query .= " AND (p.textoPromo LIKE :busqueda OR l.nombreLocal LIKE :busqueda)";
    }

    if (!empty($filtros['categoria'])) {
        $query .= " AND p.categoriaCliente = :categoria";
    }

    if (!empty($filtros['local'])) {
        $query .= " AND l.nombreLocal = :local";
    }

    $query .= " ORDER BY p.fechaCreacion DESC LIMIT :limit OFFSET :offset";

    $stmt = $conn->prepare($query);

    // Vincular parámetros de manera segura
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    // Vincular parámetros de paginación de manera segura
    $stmt->bindValue(':limit', $por_pagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $promociones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener categorías únicas para el filtro
    $query_categorias = "SELECT DISTINCT categoriaCliente FROM promociones WHERE estadoPromo = 'aprobada' ORDER BY categoriaCliente";
    $stmt_categorias = $conn->query($query_categorias);
    $categorias = $stmt_categorias->fetchAll(PDO::FETCH_COLUMN);

    // Obtener locales únicos para el filtro
    $query_locales = "SELECT DISTINCT l.nombreLocal 
                     FROM locales l 
                     INNER JOIN promociones p ON l.codLocal = p.codLocal 
                     WHERE p.estadoPromo = 'aprobada' 
                     ORDER BY l.nombreLocal";
    $stmt_locales = $conn->query($query_locales);
    $locales = $stmt_locales->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Error al cargar promociones: " . $e->getMessage() . "</div></div>";
    $promociones = [];
}

// Construir URL base para paginación manteniendo filtros
$url_base = "promociones.php?" . http_build_query($filtros);
?>


<link rel="stylesheet" href="css/promociones.css">

<div class="container mt-4 mb-5">
    <!-- Header -->
    <div class="row">
        <div class="col-12 text-center mb-4">
            <h1 class="fw-bold">Promociones Disponibles</h1>
            <p class="lead text-muted">Aprovecha estas ofertas exclusivas de tus locales favoritos.</p>
        </div>
    </div>

    <!-- Filtros -->
    <section aria-labelledby="filtros-titulo">
        <h2 id="filtros-titulo" class="visually-hidden">Filtros de búsqueda</h2>
        <div class="filters-section mb-4">
            <form method="GET" class="filters-row" role="search" aria-label="Filtrar promociones">
                <div class="filter-group">
                    <label for="busqueda" class="filter-label">
                        <i class="fas fa-search" aria-hidden="true"></i>
                        Buscar promoción
                    </label>
                    <input type="text" id="busqueda" name="busqueda" class="filter-input" 
                           placeholder="Texto de la promoción o local..."
                           value="<?= htmlspecialchars($filtros['busqueda']) ?>"
                           aria-describedby="busqueda-help">
                    <small id="busqueda-help" class="visually-hidden">
                        Busca por texto de promoción o nombre del local
                    </small>
                </div>

                <div class="filter-group">
                    <label for="categoria" class="filter-label">
                        <i class="fas fa-star" aria-hidden="true"></i>
                        Categoría
                    </label>
                    <select id="categoria" name="categoria" class="filter-select" aria-describedby="categoria-help">
                        <option value="">Todas las categorías</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= htmlspecialchars($categoria) ?>" <?= $filtros['categoria'] === $categoria ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categoria) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small id="categoria-help" class="visually-hidden">
                        Filtra por categoría de cliente
                    </small>
                </div>

                <div class="filter-group">
                    <label for="local" class="filter-label">
                        <i class="fas fa-store" aria-hidden="true"></i>
                        Local
                    </label>
                    <select id="local" name="local" class="filter-select" aria-describedby="local-help">
                        <option value="">Todos los locales</option>
                        <?php foreach ($locales as $local): ?>
                            <option value="<?= htmlspecialchars($local) ?>" <?= $filtros['local'] === $local ? 'selected' : '' ?>>
                                <?= htmlspecialchars($local) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small id="local-help" class="visually-hidden">
                        Filtra por nombre del local
                    </small>
                </div>

                <div class="filter-group">
                    <div class="filter-buttons">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-filter" aria-hidden="true"></i>
                            Aplicar filtros
                        </button>
                        <a href="promociones.php" class="btn-filter btn-clear">
                            <i class="fas fa-times" aria-hidden="true"></i>
                            Limpiar filtros
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Barra de estadísticas -->
    <div class="stats-info" aria-live="polite" aria-atomic="true">
        Mostrando <span class="stats-count"><?= count($promociones) ?></span>
        de <span class="stats-total"><?= $total_promociones ?></span>
        <?= $total_promociones === 1 ? 'promoción' : 'promociones' ?>
        <?php if ($total_paginas > 1): ?>
            - Página <?= $pagina_actual ?> de <?= $total_paginas ?>
        <?php endif; ?>
    </div>

    <!-- Grid de promociones -->
    <section aria-labelledby="resultados-titulo">
        <h2 id="resultados-titulo" class="visually-hidden">Resultados de promociones</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php if (!empty($promociones)): ?>
                <?php foreach ($promociones as $index => $promo): ?>
                    <div class="col">
                        <article class="card h-100 border-0 shadow-sm" aria-labelledby="promo-<?= $promo['codPromo'] ?>-titulo">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h3 id="promo-<?= $promo['codPromo'] ?>-titulo" class="card-title fw-bold text-primary h5">
                                        <?= htmlspecialchars($promo['nombreLocal']) ?>
                                    </h3>
                                    <span class="badge 
                                        <?= $promo['categoriaCliente'] == 'Premium' ? 'bg-warning' :
                                            ($promo['categoriaCliente'] == 'Medium' ? 'bg-info' : 'bg-secondary') ?>"
                                        aria-label="Categoría: <?= htmlspecialchars($promo['categoriaCliente']) ?>">
                                        <?= htmlspecialchars($promo['categoriaCliente']) ?>
                                    </span>
                                </div>
                                <p class="card-subtitle mb-2 text-muted h6"><?= htmlspecialchars($promo['rubroLocal']) ?></p>
                                <p class="card-text"><?= htmlspecialchars($promo['textoPromo']) ?></p>
                            </div>
                            <div class="card-footer bg-transparent border-0 pt-0">
                                <ul class="list-unstyled mb-0 small" aria-label="Detalles de la promoción">
                                    <li class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1" aria-hidden="true"></i>
                                        <?= htmlspecialchars($promo['ubicacionLocal']) ?>
                                    </li>
                                    <li class="text-muted">
                                        <i class="far fa-calendar-alt me-1" aria-hidden="true"></i>
                                        Válido hasta: <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?>
                                    </li>
                                    <li class="text-muted">
                                        <i class="fas fa-clock me-1" aria-hidden="true"></i>
                                        Días: <?= htmlspecialchars(convertirDias($promo['diasSemana'])) ?>
                                    </li>
                                </ul>
                                <div class="d-grid mt-3">
                                    <a href="login.php?id=<?= $promo['codPromo'] ?>" class="btn btn-primary-modern"
                                       aria-label="Solicitar promoción de <?= htmlspecialchars($promo['nombreLocal']) ?> - <?= htmlspecialchars($promo['textoPromo']) ?>">
                                        Solicitar Promoción <i class="fas fa-arrow-right ms-1" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        <?php if (!empty($filtros['busqueda']) || !empty($filtros['categoria']) || !empty($filtros['local'])): ?>
                            No hay promociones que coincidan con los filtros aplicados.
                        <?php else: ?>
                            No hay promociones activas en este momento. ¡Vuelve pronto!
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- PAGINACIÓN -->
    <?php if ($total_paginas > 1 && $total_promociones > 0): ?>
        <nav aria-label="Paginación de promociones" class="mt-5">
            <h3 class="visually-hidden">Navegación entre páginas</h3>
            <ul class="pagination justify-content-center">
                <!-- Botón Anterior -->
                <li class="page-item <?= $pagina_actual == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $url_base ?>&pagina=<?= $pagina_actual - 1 ?>" 
                       aria-label="Página anterior" <?= $pagina_actual == 1 ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <!-- Números de página -->
                <?php
                $inicio = max(1, $pagina_actual - 2);
                $fin = min($total_paginas, $pagina_actual + 2);

                if ($inicio > 1) {
                    echo '<li class="page-item"><a class="page-link" href="' . $url_base . '&pagina=1" aria-label="Ir a página 1">1</a></li>';
                    if ($inicio > 2)
                        echo '<li class="page-item disabled"><span class="page-link" aria-hidden="true">...</span></li>';
                }

                for ($i = $inicio; $i <= $fin; $i++):
                    ?>
                    <li class="page-item <?= $i == $pagina_actual ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $url_base ?>&pagina=<?= $i ?>" 
                           aria-label="Página <?= $i ?>" <?= $i == $pagina_actual ? 'aria-current="page"' : '' ?>>
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php if ($fin < $total_paginas): ?>
                    <?php if ($fin < $total_paginas - 1): ?>
                        <li class="page-item disabled"><span class="page-link" aria-hidden="true">...</span></li>
                    <?php endif; ?>
                    <li class="page-item"><a class="page-link" href="<?= $url_base ?>&pagina=<?= $total_paginas ?>"
                           aria-label="Ir a página <?= $total_paginas ?>"><?= $total_paginas ?></a></li>
                <?php endif; ?>

                <!-- Botón Siguiente -->
                <li class="page-item <?= $pagina_actual == $total_paginas ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $url_base ?>&pagina=<?= $pagina_actual + 1 ?>" 
                       aria-label="Página siguiente" <?= $pagina_actual == $total_paginas ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>

            <!-- Navegación rápida -->
            <div class="text-center mt-3">
                <label for="pagination-select" class="text-muted me-2">Ir a página:</label>
                <select id="pagination-select" class="form-select d-inline-block w-auto mx-2 pagination-select"
                    onchange="window.location.href='<?= htmlspecialchars($url_base, ENT_QUOTES) ?>&pagina=' + this.value"
                    aria-label="Seleccionar página">
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <option value="<?= $i ?>" <?= $i == $pagina_actual ? 'selected' : '' ?>>
                            <?= $i ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
        </nav>
    <?php endif; ?>
</div>

<?php
require_once 'includes/footer.php';
?>