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

    // Si es un string con números separados por comas
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

    // Si es un solo número
    return isset($diasMap[$diasSemana]) ? $diasMap[$diasSemana] : $diasSemana;
}

$database = new Database();
$conn = $database->getConnection();

$promociones = [];
try {
    $query = "
        SELECT 
            p.codPromo, 
            p.textoPromo, 
            p.fechaDesdePromo, 
            p.fechaHastaPromo, 
            p.diasSemana,
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
        ORDER BY 
            p.fechaCreacion DESC
    ";
    $stmt = $conn->query($query);
    $promociones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Error al cargar promociones: " . $e->getMessage() . "</div></div>";
    $promociones = [];
}
?>

<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-12 text-center mb-4">
            <h1 class="fw-bold">Promociones Disponibles</h1>
            <p class="lead text-muted">Aprovecha estas ofertas exclusivas de tus locales favoritos.</p>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php if (!empty($promociones)): ?>
            <?php foreach ($promociones as $promo): ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-primary"><?= htmlspecialchars($promo['nombreLocal']) ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($promo['rubroLocal']) ?></h6>
                            <p class="card-text"><?= htmlspecialchars($promo['textoPromo']) ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <ul class="list-unstyled mb-0 small">
                                <li class="text-muted"><i
                                        class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($promo['ubicacionLocal']) ?>
                                </li>
                                <li class="text-muted"><i class="far fa-calendar-alt me-1"></i>Válido hasta:
                                    <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?>
                                </li>
                                <li class="text-muted"><i class="fas fa-clock me-1"></i>Días:
                                    <?= htmlspecialchars(convertirDias($promo['diasSemana'])) ?>
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
                    No hay promociones activas en este momento. ¡Vuelve pronto!
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>