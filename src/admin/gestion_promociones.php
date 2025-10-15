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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            color: white;
        }

        .header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-icon {
            background: rgba(255, 255, 255, 0.2);
            padding: 12px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.15);
        }

        .stat-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .stat-icon.pending {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .stat-icon.active {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .stat-icon.rejected {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        }

        .stat-icon.total {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2d3748;
        }

        .stat-label {
            color: #718096;
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: none;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1, #bee5eb);
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 24px;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-body {
            padding: 24px;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: linear-gradient(135deg, #f8f9ff 0%, #f1f3ff 100%);
            padding: 16px 12px;
            text-align: left;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 16px 12px;
            border-bottom: 1px solid #f7fafc;
            vertical-align: middle;
        }

        tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #f1f3ff 100%);
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            margin: 0 4px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-success {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #f56565, #e53e3e);
            color: white;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
        }

        .badge-pending {
            background: linear-gradient(135deg, #ed8936, #dd6b20);
            color: white;
        }

        .badge-denied {
            background: linear-gradient(135deg, #f56565, #e53e3e);
            color: white;
        }

        .promo-text {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .date-range {
            font-size: 0.9rem;
            color: #666;
        }

        .local-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .local-name {
            font-weight: 600;
            color: #2d3748;
        }

        .local-location {
            font-size: 0.85rem;
            color: #718096;
        }

        .owner-info {
            font-size: 0.9rem;
            color: #4a5568;
        }

        .category-tag {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .category-inicial {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
        }

        .category-medium {
            background: linear-gradient(135deg, #ed8936, #dd6b20);
            color: white;
        }

        .category-premium {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .dias-semana {
            font-size: 0.8rem;
            color: #718096;
            margin-top: 4px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 1.8rem;
            }

            .table-container {
                font-size: 0.9rem;
            }

            th,
            td {
                padding: 12px 8px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>
                <div class="header-icon">
                    <i class="fas fa-tags"></i>
                </div>
                Gestión de Promociones
            </h1>
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
                                            <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
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
                                                        <div class="local-location"><?= htmlspecialchars($promo['ubicacionLocal']) ?></div>
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
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Rechazar esta promoción?')">
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
                                            <i class="fas fa-fire" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
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