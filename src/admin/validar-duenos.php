<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

$auth = new Auth();
$auth->checkAccess(['administrador']);

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

// ========== PROCESAR ACCIONES - DEBE IR ANTES DE CUALQUIER HTML ==========
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
        // Guardar el error para mostrar después
        $error_message = "Error: " . $conn->error;
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

// ========== AHORA SÍ PUEDE IR EL HTML ==========
$pageTitle = "Validar Dueños de Locales";
require_once '../includes/header-panel.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar Dueños de Locales</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../css/validar-duenos.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="validar-duenos-header">
            <h1><i class="fas fa-user-check me-2"></i>Validar Dueños</h1>
            <p>Gestión y aprobación de dueños de locales registrados</p>
        </div>

        <!-- Mostrar mensaje de éxito -->
        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($_GET['mensaje'] ?? 'Operación realizada correctamente'); ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar mensaje de error -->
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error_message); ?>
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