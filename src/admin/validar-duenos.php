<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Usuario por defecto de XAMPP
$password = ""; // Contraseña por defecto de XAMPP
$dbname = "shopping_promos"; // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar variables para estadísticas
$total = 0;
$pendientes = 0;
$aprobados = 0;
$rechazados = 0;

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

// Consulta para estadísticas
$sql_stats = "SELECT 
    COUNT(*) as total,
    SUM(estado = 'pendiente') as pendientes,
    SUM(estado = 'activo') as aprobados,
    SUM(estado = 'rechazado') as rechazados
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

// Procesar acciones (aprobar, rechazar, revocar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $accion = $_POST['accion'];

    // Agrega depuración para ver qué se está enviando
    error_log("Usuario ID: $usuario_id, Acción: $accion");

    if ($accion === 'aprobar') {
        $sql = "UPDATE usuarios SET estado = 'activo' WHERE codUsuario = $usuario_id";
    } elseif ($accion === 'rechazar') {
        $sql = "UPDATE usuarios SET estado = 'rechazado' WHERE codUsuario = $usuario_id";
    } elseif ($accion === 'revocar') {
        $sql = "UPDATE usuarios SET estado = 'pendiente' WHERE codUsuario = $usuario_id";
    }
    // Agrega depuración para ver la consulta SQL
    error_log("SQL: $sql");

    if ($conn->query($sql) === TRUE) {
        error_log("Actualización exitosa");
        // Redirigir para evitar reenvío del formulario
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit();
    } else {
        error_log("Error: " . $conn->error);
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
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

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1, #bee5eb);
            color: #0c5460;
            border-left: 4px solid #17a2b8;
            text-align: center;
            padding: 40px;
        }

        .alert-info i {
            font-size: 3rem;
            margin-bottom: 16px;
            display: block;
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

        <!-- Estadísticas -->
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

        <!-- Alertas de ejemplo -->
        <div class="alert alert-success" style="display: none;">
            Usuario aprobado correctamente
        </div>

        <!-- Contenido principal -->
        <!-- Contenido principal -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-hourglass-half"></i>
                Solicitudes Pendientes de Validación
            </div>
            <div class="card-body">
                <?php if ($result_pendientes && $result_pendientes->num_rows > 0): ?>
                    <div class="table-container" id="data-table">
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
                                                    onclick="return confirm('¿Rechazar esta solicitud?')">
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
                    <div class="empty-state" id="empty-state">
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

        <!-- Estado vacío (oculto por defecto) -->
        <div class="empty-state" id="empty-state" style="display: none;">
            <div class="empty-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="empty-title">¡Todo al día!</div>
            <div class="empty-description">
                No hay dueños pendientes de validación en este momento.<br>
                Todas las solicitudes han sido procesadas.
            </div>
        </div>
    </div>
    </div>
    </div>
    <!-- Sección de Dueños Aprobados -->
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
                                            <input type="hidden" name="usuario_id" value="<?php echo $row['codUsuario']; ?>">
                                            <input type="hidden" name="accion" value="revocar">
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('¿Revocar aprobación de este dueño?')">
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

    <script>
        // Simulación de funcionalidad de botones
        document.querySelectorAll('button[type="submit"]').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const action = this.parentElement.querySelector('input[name="accion"]').value;

                if (action === 'rechazar') {
                    if (!confirm('¿Rechazar esta solicitud?')) {
                        return;
                    }
                }

                // Mostrar alerta de éxito
                const alert = document.querySelector('.alert-success');
                alert.style.display = 'block';
                alert.textContent = action === 'aprobar' ? 'Usuario aprobado correctamente' : 'Usuario rechazado correctamente';

                // Ocultar la alerta después de 3 segundos
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 3000);

                // Remover la fila de la tabla (simulación)
                const row = this.closest('tr');
                row.style.opacity = '0.5';

                setTimeout(() => {
                    row.remove();

                    // Actualizar estadísticas
                    updateStats(action);

                    // Verificar si ya no hay filas
                    const remainingRows = document.querySelectorAll('tbody tr').length;
                    if (remainingRows === 0) {
                        document.getElementById('data-table').style.display = 'none';
                        document.getElementById('empty-state').style.display = 'block';
                    }
                }, 1000);
            });
        });

        function updateStats(action) {
            const pendingElement = document.querySelector('.stat-icon.pending').nextElementSibling.querySelector('.stat-number');
            const currentPending = parseInt(pendingElement.textContent);
            pendingElement.textContent = currentPending - 1;

            if (action === 'aprobar') {
                const approvedElement = document.querySelector('.stat-icon.approved').nextElementSibling.querySelector('.stat-number');
                const currentApproved = parseInt(approvedElement.textContent);
                approvedElement.textContent = currentApproved + 1;
            } else {
                const rejectedElement = document.querySelector('.stat-icon.rejected').nextElementSibling.querySelector('.stat-number');
                const currentRejected = parseInt(rejectedElement.textContent);
                rejectedElement.textContent = currentRejected + 1;
            }
        }


        // Animación de entrada para las tarjetas
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
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success') === '1') {
                const alert = document.querySelector('.alert-success');
                alert.style.display = 'block';
                alert.textContent = 'Operación realizada correctamente';

                setTimeout(() => {
                    alert.style.display = 'none';
                }, 3000);
            }
        });
    </script>
</body>

</html>
<?php
// Cerrar conexión
if (isset($conn)) {
    $conn->close();
}
?>