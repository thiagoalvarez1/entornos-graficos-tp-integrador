<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess([USER_CLIENT]);

// Obtener datos del cliente
$database = new Database();
$conn = $database->getConnection();
$user_id = $_SESSION['user_id'];

// Estadísticas del cliente
$query_stats = "SELECT 
    COUNT(DISTINCT up.codPromo) as promociones_usadas,
    COUNT(DISTINCT p.codLocal) as locales_visitados,
    COUNT(CASE WHEN up.estado = 'aceptada' THEN 1 END) as descuentos_obtenidos,
    COUNT(CASE WHEN up.estado = 'enviada' THEN 1 END) as solicitudes_pendientes
    FROM uso_promociones up
    JOIN promociones p ON up.codPromo = p.codPromo
    WHERE up.codCliente = :user_id";

$stmt_stats = $conn->prepare($query_stats);
$stmt_stats->bindParam(':user_id', $user_id);
$stmt_stats->execute();
$stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);

// Promociones recientes utilizadas
$query_recientes = "SELECT 
    p.textoPromo,
    p.categoriaCliente,
    l.nombreLocal,
    up.fechaUsoPromo,
    up.estado
    FROM uso_promociones up
    JOIN promociones p ON up.codPromo = p.codPromo
    JOIN locales l ON p.codLocal = l.codLocal
    WHERE up.codCliente = :user_id
    ORDER BY up.fechaUsoPromo DESC
    LIMIT 5";

$stmt_recientes = $conn->prepare($query_recientes);
$stmt_recientes->bindParam(':user_id', $user_id);
$stmt_recientes->execute();
$promociones_recientes = $stmt_recientes->fetchAll(PDO::FETCH_ASSOC);

// Promociones disponibles (nuevas)
$query_disponibles = "SELECT 
    p.codPromo,
    p.textoPromo,
    p.categoriaCliente,
    l.nombreLocal,
    l.ubicacionLocal
    FROM promociones p
    JOIN locales l ON p.codLocal = l.codLocal
    WHERE p.estadoPromo = 'aprobada' 
    AND p.categoriaCliente = (
        SELECT CASE 
            WHEN COUNT(*) >= 4 THEN 'Premium'
            WHEN COUNT(*) >= 2 THEN 'Medium'
            ELSE 'Inicial'
        END
        FROM uso_promociones up2
        WHERE up2.codCliente = :user_id AND up2.estado = 'aceptada'
    )
    AND p.codPromo NOT IN (
        SELECT up3.codPromo 
        FROM uso_promociones up3 
        WHERE up3.codCliente = :user_id
    )
    ORDER BY p.fechaCreacion DESC
    LIMIT 6";

$stmt_disponibles = $conn->prepare($query_disponibles);
$stmt_disponibles->bindParam(':user_id', $user_id);
$stmt_disponibles->execute();
$promociones_disponibles = $stmt_disponibles->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Panel Cliente";
require_once '../includes/header-panel.php';
?>

<div class="container-fluid py-4">
    <!-- Header de bienvenida -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Panel del Cliente</h1>
            <p class="text-muted mb-0">¡Hola,
                <?= htmlspecialchars(explode('@', $auth->getCurrentUser()['email'])[0]) ?>! Bienvenido a tu panel
            </p>
        </div>
        <div class="bg-light p-3 rounded">
            <small class="text-muted">Tu categoría</small>
            <?php
            $categoria = 'Inicial';
            if ($stats['descuentos_obtenidos'] >= 4) {
                $categoria = 'Premium';
            } elseif ($stats['descuentos_obtenidos'] >= 2) {
                $categoria = 'Medium';
            }
            ?>
            <div
                class="badge-status <?= $categoria == 'Premium' ? 'badge-approved' : ($categoria == 'Medium' ? 'badge-pending' : 'badge-rejected') ?> mt-1">
                <?= $categoria ?>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card card-panel h-100">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="fas fa-percentage fa-2x"></i>
                    </div>
                    <h3 class="text-primary"><?= $stats['descuentos_obtenidos'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Descuentos Obtenidos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-panel h-100">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="fas fa-store fa-2x"></i>
                    </div>
                    <h3 class="text-success"><?= $stats['locales_visitados'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Locales Visitados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-panel h-100">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="fas fa-tags fa-2x"></i>
                    </div>
                    <h3 class="text-warning"><?= $stats['promociones_usadas'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Promociones Usadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-panel h-100">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <h3 class="text-info"><?= $stats['solicitudes_pendientes'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Solicitudes Pendientes</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Promociones disponibles -->
        <div class="col-lg-8 mb-4">
            <div class="card card-panel h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-gift me-2"></i>Promociones Disponibles
                    </h5>
                    <a href="promociones_disponibles.php" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body">
                    <?php if (empty($promociones_disponibles)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-gift fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay promociones disponibles</h5>
                            <p class="text-muted">¡Pronto habrá nuevas ofertas para ti!</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($promociones_disponibles as $promocion): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title"><?= htmlspecialchars($promocion['nombreLocal']) ?></h6>
                                                <span class="badge bg-info"><?= $promocion['categoriaCliente'] ?></span>
                                            </div>
                                            <p class="card-text small"><?= htmlspecialchars($promocion['textoPromo']) ?></p>
                                            <p class="small text-muted mb-2">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                <?= htmlspecialchars($promocion['ubicacionLocal']) ?>
                                            </p>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="solicitar_promocion.php?id=<?= $promocion['codPromo'] ?>"
                                                class="btn btn-success btn-sm w-100">
                                                <i class="fas fa-paper-plane me-2"></i>Solicitar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Actividad reciente -->
        <div class="col-lg-4 mb-4">
            <div class="card card-panel h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Actividad Reciente
                    </h5>
                    <a href="mis_promociones.php" class="btn btn-sm btn-outline-primary">Ver historial</a>
                </div>
                <div class="card-body">
                    <?php if (empty($promociones_recientes)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Sin actividad reciente</h5>
                            <p class="text-muted">Comienza a usar promociones para ver tu historial aquí</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($promociones_recientes as $reciente): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="mb-0"><?= htmlspecialchars($reciente['nombreLocal']) ?></h6>
                                        <span
                                            class="badge-status <?= $reciente['estado'] == 'aceptada' ? 'badge-approved' : ($reciente['estado'] == 'enviada' ? 'badge-pending' : 'badge-rejected') ?>">
                                            <?= ucfirst($reciente['estado']) ?>
                                        </span>
                                    </div>
                                    <p class="small text-muted mb-1"><?= htmlspecialchars($reciente['textoPromo']) ?></p>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= date('d/m/Y H:i', strtotime($reciente['fechaUsoPromo'])) ?>
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
                            <a href="promociones_disponibles.php"
                                class="btn btn-outline-primary btn-lg w-100 h-100 py-3">
                                <i class="fas fa-search fa-2x mb-2"></i>
                                <br>
                                <span>Explorar Promociones</span>
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
                            <a href="#" class="btn btn-outline-info btn-lg w-100 h-100 py-3">
                                <i class="fas fa-qrcode fa-2x mb-2"></i>
                                <br>
                                <span>Mi Código QR</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-outline-warning btn-lg w-100 h-100 py-3">
                                <i class="fas fa-star fa-2x mb-2"></i>
                                <br>
                                <span>Favoritos</span>
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

        // Actualización automática de estadísticas
        setInterval(() => {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    // Aquí podrías actualizar las estadísticas si fuera necesario
                    console.log('Página actualizada silenciosamente');
                })
                .catch(() => {
                    // Silenciar errores de red
                });
        }, 30000); // Cada 30 segundos
    });
</script>

<?php require_once '../includes/footer-panel.php'; ?>