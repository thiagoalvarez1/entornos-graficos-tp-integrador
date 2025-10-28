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
    <div class="filters-section mb-4">
        <form method="GET" class="filters-row">
            <div class="filter-group">
                <label class="filter-label">
                    <i class="fas fa-search"></i>
                    Buscar promoción
                </label>
                <input type="text" name="busqueda" class="filter-input" placeholder="Texto de la promoción o local..."
                    value="<?= htmlspecialchars($filtros['busqueda']) ?>">
            </div>

            <div class="filter-group">
                <label class="filter-label">
                    <i class="fas fa-star"></i>
                    Categoría
                </label>
                <select name="categoria" class="filter-select">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= htmlspecialchars($categoria) ?>" <?= $filtros['categoria'] === $categoria ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categoria) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">
                    <i class="fas fa-store"></i>
                    Local
                </label>
                <select name="local" class="filter-select">
                    <option value="">Todos los locales</option>
                    <?php foreach ($locales as $local): ?>
                        <option value="<?= htmlspecialchars($local) ?>" <?= $filtros['local'] === $local ? 'selected' : '' ?>>
                            <?= htmlspecialchars($local) ?>
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
                    <a href="promociones.php" class="btn-filter btn-clear">
                        <i class="fas fa-times"></i>
                        Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Barra de estadísticas -->
    <div class="stats-info">
        Mostrando <span class="stats-count"><?= count($promociones) ?></span>
        de <span class="stats-total"><?= $total_promociones ?></span>
        <?= $total_promociones === 1 ? 'promoción' : 'promociones' ?>
        <?php if ($total_paginas > 1): ?>
            - Página <?= $pagina_actual ?> de <?= $total_paginas ?>
        <?php endif; ?>
    </div>


    <!-- Grid de promociones -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php if (!empty($promociones)): ?>
            <?php foreach ($promociones as $promo): ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold text-primary"><?= htmlspecialchars($promo['nombreLocal']) ?></h5>
                                <span class="badge 
                                    <?= $promo['categoriaCliente'] == 'Premium' ? 'bg-warning' :
                                        ($promo['categoriaCliente'] == 'Medium' ? 'bg-info' : 'bg-secondary') ?>">
                                    <?= htmlspecialchars($promo['categoriaCliente']) ?>
                                </span>
                            </div>
                            <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($promo['rubroLocal']) ?></h6>
                            <p class="card-text"><?= htmlspecialchars($promo['textoPromo']) ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <ul class="list-unstyled mb-0 small">
                                <li class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?= htmlspecialchars($promo['ubicacionLocal']) ?>
                                </li>
                                <li class="text-muted">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    Válido hasta: <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?>
                                </li>
                                <li class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    Días: <?= htmlspecialchars(convertirDias($promo['diasSemana'])) ?>
                                </li>
                            </ul>
                            <div class="d-grid mt-3">
                                <a href="login.php?id=<?= $promo['codPromo'] ?>" class="btn btn-primary-modern">
                                    Solicitar Promoción <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
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

    <!-- PAGINACIÓN -->
    <?php if ($total_paginas > 1 && $total_promociones > 0): ?>
        <nav aria-label="Paginación de promociones" class="mt-5">
            <ul class="pagination justify-content-center">
                <!-- Botón Anterior -->
                <li class="page-item <?= $pagina_actual == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $url_base ?>&pagina=<?= $pagina_actual - 1 ?>" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <!-- Números de página -->
                <?php
                $inicio = max(1, $pagina_actual - 2);
                $fin = min($total_paginas, $pagina_actual + 2);

                if ($inicio > 1) {
                    echo '<li class="page-item"><a class="page-link" href="' . $url_base . '&pagina=1">1</a></li>';
                    if ($inicio > 2)
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }

                for ($i = $inicio; $i <= $fin; $i++):
                    ?>
                    <li class="page-item <?= $i == $pagina_actual ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $url_base ?>&pagina=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($fin < $total_paginas): ?>
                    <?php if ($fin < $total_paginas - 1): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                    <li class="page-item"><a class="page-link"
                            href="<?= $url_base ?>&pagina=<?= $total_paginas ?>"><?= $total_paginas ?></a></li>
                <?php endif; ?>

                <!-- Botón Siguiente -->
                <li class="page-item <?= $pagina_actual == $total_paginas ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $url_base ?>&pagina=<?= $pagina_actual + 1 ?>" aria-label="Siguiente">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>

            <!-- Navegación rápida -->
            <div class="text-center mt-3">
                <small class="text-muted">
                    Ir a página:
                    <select class="form-select d-inline-block w-auto mx-2 pagination-select"
                        onchange="window.location.href='<?= htmlspecialchars($url_base, ENT_QUOTES) ?>&pagina=' + this.value">
                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                            <option value="<?= $i ?>" <?= $i == $pagina_actual ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </small>
            </div>
        </nav>
    <?php endif; ?>


</div>

<?php
require_once 'includes/footer.php';
?>