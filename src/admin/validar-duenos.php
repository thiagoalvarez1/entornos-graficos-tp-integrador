<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

$auth = new Auth();
$auth->checkAccess(['administrador']);

$pageTitle = "Reportes y Estadísticas";
require_once '../includes/header-panel.php';
?>

<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shopping_promos";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Mensaje de éxito
$mensaje_exito = '';

// Procesar acciones - ESTO DEBE IR AL PRINCIPIO DEL ARCHIVO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario_id']) && isset($_POST['accion'])) {
    $usuario_id = intval($_POST['usuario_id']);
    $accion = $_POST['accion'];

    error_log("=== PROCESANDO FORMULARIO ===");
    error_log("Usuario ID: $usuario_id, Acción: $accion");

    // Validar que el usuario_id sea válido
    if ($usuario_id <= 0) {
        die("Error: ID de usuario inválido");
    }
    error_log("ID que se intentará actualizar: " . $usuario_id);

    // Determinar el nuevo estado según la acción
    if ($accion === 'aprobar') {
        $nuevo_estado = 'activo';
        $mensaje_exito = "Dueño aprobado correctamente";
    } elseif ($accion === 'rechazar') {
        $nuevo_estado = 'inactivo';
        $mensaje_exito = "Dueño rechazado correctamente";
    } elseif ($accion === 'revocar') {
        $nuevo_estado = 'pendiente';
        $mensaje_exito = "Aprobación revocada correctamente";
    } else {
        die("Error: Acción no válida");
    }

    // Actualizar en la base de datos
    $sql = "UPDATE usuarios SET estado = '$nuevo_estado' WHERE codUsuario = $usuario_id AND tipoUsuario = 'dueño de local'";

    error_log("Ejecutando SQL: $sql");

    if ($conn->query($sql) === TRUE) {
        error_log("✅ Actualización exitosa. Filas afectadas: " . $conn->affected_rows);

        // Redirigir para evitar reenvío del formulario
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1&mensaje=" . urlencode($mensaje_exito));
        exit();
    } else {
        error_log("❌ Error en la consulta: " . $conn->error);
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

// Consultas para mostrar datos (DESPUÉS del procesamiento del POST)
$total = $pendientes = $aprobados = $rechazados = 0;

// Consulta para estadísticas
$sql_stats = "SELECT 
    COUNT(*) as total,
    SUM(estado = 'pendiente') as pendientes,
    SUM(estado = 'activo') as aprobados,
    SUM(estado = 'inactivo') as rechazados
    FROM usuarios 
    WHERE tipoUsuario = 'dueño de local'";

$result_stats = $conn->query($sql_stats);
if ($result_stats && $result_stats->num_rows > 0) {
    $stats = $result_stats->fetch_assoc();
    $total = $stats['total'];
    $pendientes = $stats['pendientes'];
    $aprobados = $stats['aprobados'];
    $rechazados = $stats['rechazados'];
}

// Consulta para obtener dueños pendientes de validación
$sql_pendientes = "SELECT codUsuario, nombreUsuario, fechaRegistro, estado 
                   FROM usuarios 
                   WHERE tipoUsuario = 'dueño de local' AND estado = 'pendiente'";
$result_pendientes = $conn->query($sql_pendientes);

// Consulta para obtener dueños aprobados
$sql_aprobados = "SELECT codUsuario, nombreUsuario, fechaRegistro, estado 
                  FROM usuarios 
                  WHERE tipoUsuario = 'dueño de local' AND estado = 'activo'";
$result_aprobados = $conn->query($sql_aprobados);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar Dueños de Locales</title>
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
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

        .stat-icon.approved {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .stat-icon.rejected {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        }

        .stat-icon.total {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }

        .stat-number {
            font-size: 2.2rem;
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

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .user-details {
            flex: 1;
        }

        .user-email {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 4px;
        }

        .user-id {
            font-size: 0.85rem;
            color: #718096;
        }

        .date-badge {
            background: linear-gradient(135deg, #f8f9ff, #f1f3ff);
            color: #4a5568;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            border: 1px solid #e2e8f0;
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

        .btn-success:hover {
            background: linear-gradient(135deg, #38a169, #2f855a);
        }

        .btn-danger {
            background: linear-gradient(135deg, #f56565, #e53e3e);
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #e53e3e, #c53030);
        }

        .actions-cell {
            white-space: nowrap;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
        }

        .empty-icon {
            font-size: 4rem;
            color: #e2e8f0;
            margin-bottom: 20px;
        }

        .empty-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
        }

        .empty-description {
            font-size: 1rem;
            line-height: 1.6;
        }

        .status-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .status-pending {
            background: #f093fb;
        }

        .status-approved {
            background: #4facfe;
        }

        .status-rejected {
            background: #ff9a9e;
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

            .user-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .btn {
                padding: 6px 12px;
                font-size: 0.8rem;
                margin: 2px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>
                <div class="header-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                Validar Dueños de Locales
            </h1>
        </div>

        <!-- Mostrar mensaje de éxito -->
        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($_GET['mensaje'] ?? 'Operación realizada correctamente'); ?>
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
                        <div class="stat-number"><?php echo $pendientes; ?></div>
                        <div class="stat-label">Pendientes</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon approved">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?php echo $aprobados; ?></div>
                        <div class="stat-label">Aprobados</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon rejected">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?php echo $rechazados; ?></div>
                        <div class="stat-label">Rechazados</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon total">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?php echo $total; ?></div>
                        <div class="stat-label">Total Dueños</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-hourglass-half"></i>
                Solicitudes Pendientes de Validación
            </div>
            <div class="card-body">
                <?php if ($result_pendientes && $result_pendientes->num_rows > 0): ?>
                            <div class="table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Fecha de Registro</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result_pendientes->fetch_assoc()): ?>
                                                    <tr>
                                                        <td>
                                                            <div class="user-info">
                                                                <div class="user-avatar">
                                                                    <?php echo strtoupper(substr(explode('@', $row['nombreUsuario'])[0], 0, 2)); ?>
                                                                </div>
                                                                <div class="user-details">
                                                                    <div class="user-email"><?php echo $row['nombreUsuario']; ?></div>
                                                                    <div class="user-id">ID: <?php echo $row['codUsuario']; ?></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="date-badge">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <?php echo date('d/m/Y', strtotime($row['fechaRegistro'])); ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="status-indicator status-pending"></span>
                                                            Pendiente
                                                        </td>
                                                        <td class="actions-cell">
                                                            <!-- FORMULARIOS QUE SÍ SE ENVIAN AL SERVIDOR -->
                                                            <form method="POST" style="display: inline;">
                                                                <input type="hidden" name="usuario_id"
                                                                    value="<?php echo $row['codUsuario']; ?>">
                                                                <input type="hidden" name="accion" value="aprobar">
                                                                <button type="submit" class="btn btn-success">
                                                                    <i class="fas fa-check"></i> Aprobar
                                                                </button>
                                                            </form>
                                                            <form method="POST" style="display: inline;">
                                                                <input type="hidden" name="usuario_id"
                                                                    value="<?php echo $row['codUsuario']; ?>">
                                                                <input type="hidden" name="accion" value="rechazar">
                                                                <button type="submit" class="btn btn-danger"
                                                                    onclick="return confirm('¿Estás seguro de rechazar este dueño?')">
                                                                    <i class="fas fa-times"></i> Rechazar
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div class="empty-title">¡Todo al día!</div>
                                <div class="empty-description">
                                    No hay dueños pendientes de validación en este momento.<br>
                                    Todas las solicitudes han sido procesadas.
                                </div>
                            </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sección de Dueños Aprobados -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-check-circle"></i>
                Dueños Aprobados
            </div>
            <div class="card-body">
                <?php if ($result_aprobados && $result_aprobados->num_rows > 0): ?>
                            <div class="table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Fecha de Registro</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result_aprobados->fetch_assoc()): ?>
                                                    <tr>
                                                        <td>
                                                            <div class="user-info">
                                                                <div class="user-avatar">
                                                                    <?php echo strtoupper(substr(explode('@', $row['nombreUsuario'])[0], 0, 2)); ?>
                                                                </div>
                                                                <div class="user-details">
                                                                    <div class="user-email"><?php echo $row['nombreUsuario']; ?></div>
                                                                    <div class="user-id">ID: <?php echo $row['codUsuario']; ?></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="date-badge">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <?php echo date('d/m/Y', strtotime($row['fechaRegistro'])); ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="status-indicator status-approved"></span>
                                                            Aprobado
                                                        </td>
                                                        <td class="actions-cell">
                                                            <form method="POST" style="display: inline;">
                                                                <input type="hidden" name="usuario_id"
                                                                    value="<?php echo $row['codUsuario']; ?>">
                                                                <input type="hidden" name="accion" value="revocar">
                                                                <button type="submit" class="btn btn-danger"
                                                                    onclick="return confirm('¿Estás seguro de revocar la aprobación?')">
                                                                    <i class="fas fa-undo"></i> Revocar
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-user-times"></i>
                                </div>
                                <div class="empty-title">No hay dueños aprobados</div>
                                <div class="empty-description">
                                    No hay dueños con aprobación en este momento.
                                </div>
                            </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- SOLO JAVASCRIPT PARA ANIMACIONES, SIN BLOQUEAR FORMULARIOS -->
    <script>
        // Solo animaciones, no interferir con formularios
        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.stat-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.6s ease';

                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 150);
            });

            // Efectos de hover mejorados
            document.querySelectorAll('.user-info').forEach(userInfo => {
                userInfo.addEventListener('mouseenter', function () {
                    this.querySelector('.user-avatar').style.transform = 'scale(1.1)';
                });

                userInfo.addEventListener('mouseleave', function () {
                    this.querySelector('.user-avatar').style.transform = 'scale(1)';
                });
            });

            // Mostrar alerta si hay un parámetro de éxito en la URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success') === '1') {
                // La alerta ya se muestra con PHP, este código es opcional
                console.log('Operación exitosa detectada en URL');
            }
        });
    </script>
</body>

</html>

<?php
// Cerrar conexión
$conn->close();
?>