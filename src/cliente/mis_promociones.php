<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$database = new Database();
$conn = $database->getConnection();

// Verificar que el usuario sea cliente
$auth->checkAccess([USER_CLIENT]);

$pageTitle = "Mis Promociones";
require_once '../includes/header-panel.php';

// Obtener el ID del usuario actual
$currentUser = $auth->getCurrentUser();
$codCliente = $currentUser['id'];

// Consulta para obtener las promociones tomadas por el cliente
$query = "SELECT p.textoPromo, l.nombreLocal, u.fechaUsoPromo, u.estado, u.codUso
          FROM uso_promociones u
          JOIN promociones p ON u.codPromo = p.codPromo
          JOIN locales l ON p.codLocal = l.codLocal
          WHERE u.codCliente = :codCliente
          ORDER BY u.fechaUsoPromo DESC";

$stmt = $conn->prepare($query);
$stmt->bindParam(':codCliente', $codCliente, PDO::PARAM_INT);
$stmt->execute();
$promociones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid py-4">
    <!-- Header de la página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Mis Promociones</h1>
            <p class="text-muted mb-0">Historial de promociones solicitadas</p>
        </div>
        <a href="promociones_disponibles.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nueva Promoción
        </a>
    </div>

    <!-- Tarjeta de contenido -->
    <div class="card card-panel">
        <div class="card-body">
            <?php if (empty($promociones)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay promociones</h4>
                    <p class="text-muted">Aún no has solicitado ninguna promoción.</p>
                    <a href="promociones_disponibles.php" class="btn btn-primary mt-2">
                        <i class="fas fa-search me-2"></i>Explorar Promociones
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha Solicitud</th>
                                <th>Local</th>
                                <th>Promoción</th>
                                <th>Estado</th>
                                <th>Código</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($promociones as $promo): ?>
                                <?php
                                $estadoClase = '';
                                switch ($promo['estado']) {
                                    case 'aceptada':
                                        $estadoClase = 'badge-approved';
                                        break;
                                    case 'rechazada':
                                        $estadoClase = 'badge-rejected';
                                        break;
                                    case 'enviada':
                                        $estadoClase = 'badge-pending';
                                        break;
                                }
                                ?>
                                <tr>
                                    <td>
                                        <i class="fas fa-calendar text-muted me-2"></i>
                                        <?= date('d/m/Y H:i', strtotime($promo['fechaUsoPromo'])) ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-store text-muted me-2"></i>
                                        <?= htmlspecialchars($promo['nombreLocal']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($promo['textoPromo']) ?></td>
                                    <td>
                                        <span class="badge-status <?= $estadoClase ?>">
                                            <?= ucfirst($promo['estado']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($promo['estado'] == 'aceptada'): ?>
                                            <span class="text-success fw-bold">
                                                <i class="fas fa-qrcode me-1"></i>
                                                <?= htmlspecialchars($promo['codUso']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">No disponible</span>
                                        <?php endif; ?>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animación para las filas de la tabla
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateX(-20px)';
            
            setTimeout(() => {
                row.style.transition = 'all 0.5s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateX(0)';
            }, index * 100);
        });
    });
</script>

<?php require_once '../includes/footer-panel.php'; ?>