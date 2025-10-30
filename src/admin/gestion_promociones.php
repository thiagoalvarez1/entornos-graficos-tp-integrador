<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess(['administrador']);

$database = new Database();
$conn = $database->getConnection();

$pageTitle = "Gestión de Promociones";
require_once '../includes/header-panel.php';

// Procesar aprobación/rechazo de promociones
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['accion']) && isset($_POST['promocion_id'])) {
        $codPromo = $_POST['promocion_id'];
        $accion = $_POST['accion'];

        if ($accion == 'aprobar') {
            $estado = 'aprobada';
            $mensaje = "Promoción aprobada correctamente";
        } elseif ($accion == 'rechazar') {
            $estado = 'denegada';
            $mensaje = "Promoción rechazada correctamente";
        }

        $query = "UPDATE promociones SET estadoPromo = :estado WHERE codPromo = :codPromo";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':codPromo', $codPromo);

        if ($stmt->execute()) {
            $success = $mensaje;
        } else {
            $error = "Error al procesar la promoción";
        }
    }
}

// Obtener estadísticas
$query_stats = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN estadoPromo = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
    SUM(CASE WHEN estadoPromo = 'aprobada' THEN 1 ELSE 0 END) as activas,
    SUM(CASE WHEN estadoPromo = 'denegada' THEN 1 ELSE 0 END) as rechazadas
    FROM promociones";
$stmt_stats = $conn->prepare($query_stats);
$stmt_stats->execute();
$stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);

// Obtener promociones pendientes con información del local
$query_pendientes = "SELECT 
    p.codPromo,
    p.textoPromo,
    p.fechaDesdePromo,
    p.fechaHastaPromo,
    p.categoriaCliente,
    p.diasSemana,
    p.estadoPromo,
    p.fechaCreacion,
    l.nombreLocal,
    l.ubicacionLocal,
    u.nombreUsuario as email_dueno
    FROM promociones p
    JOIN locales l ON p.codLocal = l.codLocal
    JOIN usuarios u ON l.codUsuario = u.codUsuario
    WHERE p.estadoPromo = 'pendiente'
    ORDER BY p.fechaCreacion DESC";
$stmt_pendientes = $conn->prepare($query_pendientes);
$stmt_pendientes->execute();
$promociones_pendientes = $stmt_pendientes->fetchAll(PDO::FETCH_ASSOC);

// Obtener promociones activas (últimas 20)
$query_activas = "SELECT 
    p.codPromo,
    p.textoPromo,
    p.fechaDesdePromo,
    p.fechaHastaPromo,
    p.categoriaCliente,
    p.estadoPromo,
    l.nombreLocal
    FROM promociones p
    JOIN locales l ON p.codLocal = l.codLocal
    WHERE p.estadoPromo = 'aprobada' 
    AND p.fechaHastaPromo >= CURDATE()
    ORDER BY p.fechaCreacion DESC
    LIMIT 20";
$stmt_activas = $conn->prepare($query_activas);
$stmt_activas->execute();
$promociones_activas = $stmt_activas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Promociones</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/gestion_promociones.css">
</head>

<body>
    <div class="container">
        <div class="validar-duenos-header">
            <h1><i class="fas fa-user-check me-2"></i>Gestión de promociones</h1>
            <p>Gestión y aprobación de promociones</p>
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
                    <div class="stat-icon pending">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?= $stats['pendientes'] ?></div>
                        <div class="stat-label">Pendientes</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon active">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?= $stats['activas'] ?></div>
                        <div class="stat-label">Activas</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon rejected">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?= $stats['rechazadas'] ?></div>
                        <div class="stat-label">Rechazadas</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon total">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?= $stats['total'] ?></div>
                        <div class="stat-label">Total</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promociones Pendientes -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-hourglass-half"></i>
                Promociones Pendientes de Aprobación
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Promoción</th>
                                <th>Local</th>
                                <th>Dueño</th>
                                <th>Vigencia</th>
                                <th>Categoría</th>
                                <th>Días</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($promociones_pendientes)): ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 40px; color: #718096;">
                                        <i class="fas fa-inbox"
                                            style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                                        <br>
                                        No hay promociones pendientes de aprobación
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($promociones_pendientes as $promo): ?>
                                    <tr>
                                        <td><strong><?= $promo['codPromo'] ?></strong></td>
                                        <td>
                                            <div class="promo-text" title="<?= htmlspecialchars($promo['textoPromo']) ?>">
                                                <?= htmlspecialchars($promo['textoPromo']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="local-info">
                                                <div class="local-name"><?= htmlspecialchars($promo['nombreLocal']) ?></div>
                                                <div class="local-location"><?= htmlspecialchars($promo['ubicacionLocal']) ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="owner-info"><?= htmlspecialchars($promo['email_dueno']) ?></td>
                                        <td class="date-range">
                                            <?= date('d/m/Y', strtotime($promo['fechaDesdePromo'])) ?> -
                                            <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?>
                                        </td>
                                        <td>
                                            <div class="category-tag category-<?= strtolower($promo['categoriaCliente']) ?>">
                                                <?= $promo['categoriaCliente'] ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dias-semana">
                                                <?= $promo['diasSemana'] ?>
                                            </div>
                                        </td>
                                        <td>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="promocion_id" value="<?= $promo['codPromo'] ?>">
                                                <input type="hidden" name="accion" value="aprobar">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check"></i> Aprobar
                                                </button>
                                            </form>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="promocion_id" value="<?= $promo['codPromo'] ?>">
                                                <input type="hidden" name="accion" value="rechazar">
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('¿Rechazar esta promoción?')">
                                                    <i class="fas fa-times"></i> Rechazar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Promociones Activas -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-fire"></i>
                Promociones Activas (Últimas 20)
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Promoción</th>
                                <th>Local</th>
                                <th>Vigencia</th>
                                <th>Categoría</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($promociones_activas)): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 40px; color: #718096;">
                                        <i class="fas fa-fire"
                                            style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                                        <br>
                                        No hay promociones activas
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($promociones_activas as $promo): ?>
                                    <tr>
                                        <td><strong><?= $promo['codPromo'] ?></strong></td>
                                        <td>
                                            <div class="promo-text" title="<?= htmlspecialchars($promo['textoPromo']) ?>">
                                                <?= htmlspecialchars($promo['textoPromo']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="local-info">
                                                <div class="local-name"><?= htmlspecialchars($promo['nombreLocal']) ?></div>
                                            </div>
                                        </td>
                                        <td class="date-range">
                                            Hasta <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?>
                                        </td>
                                        <td>
                                            <div class="category-tag category-<?= strtolower($promo['categoriaCliente']) ?>">
                                                <?= $promo['categoriaCliente'] ?>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-success">Activa</span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Confirmación para rechazar promociones
        document.querySelectorAll('button[type="submit"]').forEach(button => {
            button.addEventListener('click', function (e) {
                const action = this.parentElement.querySelector('input[name="accion"]').value;

                if (action === 'rechazar') {
                    if (!confirm('¿Estás seguro de que quieres rechazar esta promoción?')) {
                        e.preventDefault();
                    }
                }
            });
        });

        // Animación de números en estadísticas
        document.addEventListener('DOMContentLoaded', function () {
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
        });
    </script>
</body>

</html>