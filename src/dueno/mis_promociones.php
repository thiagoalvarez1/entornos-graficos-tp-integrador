<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess(['dueño de local']);

$database = new Database();
$conn = $database->getConnection();
$user_id = $_SESSION['user_id'];

// Obtener el local del dueño
$query = "SELECT codLocal, nombreLocal FROM locales WHERE codUsuario = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$local = $stmt->fetch(PDO::FETCH_ASSOC);

$local_id = $local ? $local['codLocal'] : null;
$local_nombre = $local ? $local['nombreLocal'] : '';

// Crear nueva promoción
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_promocion']) && $local_id) {
    $texto = trim($_POST['texto']);
    $fecha_desde = $_POST['fecha_desde'];
    $fecha_hasta = $_POST['fecha_hasta'];
    $categoria = $_POST['categoria'];
    $dias_semana = implode(',', $_POST['dias_semana'] ?? []);

    // Validar fechas
    if (strtotime($fecha_hasta) <= strtotime($fecha_desde)) {
        $error = "La fecha de fin debe ser posterior a la fecha de inicio";
    } elseif (strtotime($fecha_desde) < strtotime(date('Y-m-d'))) {
        $error = "La fecha de inicio no puede ser anterior a hoy";
    } else {
        $query = "INSERT INTO promociones (textoPromo, fechaDesdePromo, fechaHastaPromo, 
                  categoriaCliente, diasSemana, codLocal, estadoPromo, fechaCreacion) 
                  VALUES (:texto, :fecha_desde, :fecha_hasta, :categoria, :dias_semana, :local_id, 'pendiente', NOW())";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':texto', $texto);
        $stmt->bindParam(':fecha_desde', $fecha_desde);
        $stmt->bindParam(':fecha_hasta', $fecha_hasta);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':dias_semana', $dias_semana);
        $stmt->bindParam(':local_id', $local_id);

        if ($stmt->execute()) {
            $success = "Promoción creada exitosamente. Esperando aprobación del administrador.";
            $_POST = array();
        } else {
            $error = "Error al crear la promoción";
        }
    }
}

// Editar promoción
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_promocion']) && $local_id) {
    $promo_id = $_POST['promo_id'];
    $texto = trim($_POST['texto_edit']);
    $fecha_desde = $_POST['fecha_desde_edit'];
    $fecha_hasta = $_POST['fecha_hasta_edit'];
    $categoria = $_POST['categoria_edit'];
    $dias_semana = implode(',', $_POST['dias_semana_edit'] ?? []);

    // Verificar que la promoción pertenece al dueño
    $query = "SELECT codPromo FROM promociones WHERE codPromo = :promo_id AND codLocal = :local_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':promo_id', $promo_id);
    $stmt->bindParam(':local_id', $local_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        if (strtotime($fecha_hasta) <= strtotime($fecha_desde)) {
            $error = "La fecha de fin debe ser posterior a la fecha de inicio";
        } else {
            $query = "UPDATE promociones SET 
                      textoPromo = :texto, 
                      fechaDesdePromo = :fecha_desde, 
                      fechaHastaPromo = :fecha_hasta, 
                      categoriaCliente = :categoria, 
                      diasSemana = :dias_semana,
                      estadoPromo = 'pendiente'
                      WHERE codPromo = :promo_id";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':texto', $texto);
            $stmt->bindParam(':fecha_desde', $fecha_desde);
            $stmt->bindParam(':fecha_hasta', $fecha_hasta);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':dias_semana', $dias_semana);
            $stmt->bindParam(':promo_id', $promo_id);

            if ($stmt->execute()) {
                $success = "Promoción actualizada. Esperando nueva aprobación del administrador.";
            } else {
                $error = "Error al actualizar la promoción";
            }
        }
    } else {
        $error = "No tienes permisos para editar esta promoción";
    }
}

// Eliminar promoción
if (isset($_GET['eliminar']) && $local_id) {
    $promo_id = $_GET['eliminar'];

    $query = "SELECT codPromo FROM promociones WHERE codPromo = :promo_id AND codLocal = :local_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':promo_id', $promo_id);
    $stmt->bindParam(':local_id', $local_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $query = "DELETE FROM promociones WHERE codPromo = :promo_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':promo_id', $promo_id);

        if ($stmt->execute()) {
            $success = "Promoción eliminada correctamente";
        } else {
            $error = "Error al eliminar la promoción";
        }
    } else {
        $error = "No tienes permisos para eliminar esta promoción";
    }
}

// Obtener promociones del local con filtros
$filtro_estado = $_GET['estado'] ?? 'todos';
$filtro_categoria = $_GET['categoria'] ?? 'todas';
$busqueda = $_GET['busqueda'] ?? '';

$promociones = [];
if ($local_id) {
    $query = "SELECT * FROM promociones WHERE codLocal = :local_id";
    $params = [':local_id' => $local_id];

    // Aplicar filtros
    if ($filtro_estado != 'todos') {
        $query .= " AND estadoPromo = :estado";
        $params[':estado'] = $filtro_estado;
    }

    if ($filtro_categoria != 'todas') {
        $query .= " AND categoriaCliente = :categoria";
        $params[':categoria'] = $filtro_categoria;
    }

    if (!empty($busqueda)) {
        $query .= " AND textoPromo LIKE :busqueda";
        $params[':busqueda'] = "%$busqueda%";
    }

    $query .= " ORDER BY fechaCreacion DESC";

    $stmt = $conn->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    $promociones = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$pageTitle = "Mis Promociones";
require_once '../includes/header-panel.php';
?>

<style>
    /* Solo estilos específicos de promociones */
    .page-title {
        color: white;
        font-size: 2rem;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        margin: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-medium);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        transition: all 0.3s ease;
    }

    .stat-card:nth-child(1)::before {
        background: linear-gradient(90deg, var(--primary-purple), var(--secondary-purple));
    }

    .stat-card:nth-child(2)::before {
        background: linear-gradient(90deg, var(--accent-green), #059669);
    }

    .stat-card:nth-child(3)::before {
        background: linear-gradient(90deg, var(--accent-orange), #d97706);
    }

    .stat-card:nth-child(4)::before {
        background: linear-gradient(90deg, var(--accent-red), #dc2626);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-heavy);
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-icon:nth-child(1) {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    }

    .stat-icon:nth-child(2) {
        background: linear-gradient(135deg, var(--accent-green), #059669);
    }

    .stat-icon:nth-child(3) {
        background: linear-gradient(135deg, var(--accent-orange), #d97706);
    }

    .stat-icon:nth-child(4) {
        background: linear-gradient(135deg, var(--accent-red), #dc2626);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .stat-change {
        font-size: 0.875rem;
        font-weight: 600;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        color: var(--text-secondary);
    }

    .content-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-medium);
        transition: all 0.3s ease;
    }

    .content-section:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-heavy);
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        align-items: end;
        margin-bottom: 2rem;
    }

    .filter-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .filter-select,
    .filter-input,
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid rgba(99, 102, 241, 0.1);
        border-radius: 12px;
        background: white;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .filter-select:focus,
    .filter-input:focus,
    .form-control:focus {
        outline: none;
        border-color: var(--primary-purple);
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    }

    .btn-gradient {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        border: none;
        border-radius: 12px;
        color: white;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-medium);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-heavy);
        color: white;
    }

    .promocion-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: var(--shadow-light);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .promocion-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
    }

    .promocion-card.pendiente::before {
        background: var(--accent-orange);
    }

    .promocion-card.aprobada::before {
        background: var(--accent-green);
    }

    .promocion-card.denegada::before {
        background: var(--accent-red);
    }

    .promocion-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }

    .promocion-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .promocion-text {
        background: rgba(99, 102, 241, 0.03);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(99, 102, 241, 0.1);
        font-size: 0.9rem;
        line-height: 1.4;
        color: var(--text-primary);
    }

    .promocion-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .detail-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-secondary);
    }

    .detail-value {
        font-size: 0.875rem;
        color: var(--text-primary);
        font-weight: 500;
    }

    .estado-badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .estado-pendiente {
        background: rgba(245, 158, 11, 0.1);
        color: var(--accent-orange);
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .estado-aprobada {
        background: rgba(16, 185, 129, 0.1);
        color: var(--accent-green);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .estado-denegada {
        background: rgba(239, 68, 68, 0.1);
        color: var(--accent-red);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .category-badge {
        padding: 0.25rem 0.6rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .category-inicial {
        background: rgba(16, 185, 129, 0.1);
        color: var(--accent-green);
    }

    .category-medium {
        background: rgba(245, 158, 11, 0.1);
        color: var(--accent-orange);
    }

    .category-premium {
        background: rgba(99, 102, 241, 0.1);
        color: var(--primary-purple);
    }

    .day-badges {
        display: flex;
        gap: 0.25rem;
        flex-wrap: wrap;
    }

    .day-badge {
        background: rgba(59, 130, 246, 0.1);
        color: var(--accent-blue);
        padding: 0.125rem 0.375rem;
        border-radius: 8px;
        font-size: 0.65rem;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .btn-action {
        padding: 0.5rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-edit {
        background: linear-gradient(135deg, var(--accent-blue), #0284c7);
    }

    .btn-delete {
        background: linear-gradient(135deg, var(--accent-red), #dc2626);
    }

    .btn-action:hover {
        transform: scale(1.1);
        color: white;
    }

    .expired-overlay {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: var(--accent-red);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state h4 {
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filter-row {
            grid-template-columns: 1fr;
        }

        .promocion-header {
            flex-direction: column;
            gap: 1rem;
        }

        .promocion-details {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            justify-content: stretch;
        }
    }
</style>

<!-- Content Area -->
<div class="content-area">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Mis Promociones</h1>
        <p class="text-muted">Gestiona las promociones de <?= htmlspecialchars($local_nombre ?: 'tu local') ?></p>
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

    <?php if (!$local_id): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                No tienes un local asignado. Contacta al administrador.
            </div>
    <?php else: ?>

            <!-- Estadísticas -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-label">Total Promociones</div>
                        <div class="stat-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                    </div>
                    <div class="stat-number"><?= count($promociones) ?></div>
                    <div class="stat-change">
                        <i class="fas fa-chart-line"></i>
                        Promociones creadas
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-label">Aprobadas</div>
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="stat-number"><?= count(array_filter($promociones, fn($p) => $p['estadoPromo'] == 'aprobada')) ?>
                    </div>
                    <div class="stat-change">
                        <i class="fas fa-thumbs-up"></i>
                        Activas
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-label">Pendientes</div>
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="stat-number">
                        <?= count(array_filter($promociones, fn($p) => $p['estadoPromo'] == 'pendiente')) ?>
                    </div>
                    <div class="stat-change">
                        <i class="fas fa-hourglass-half"></i>
                        En revisión
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-label">Denegadas</div>
                        <div class="stat-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                    <div class="stat-number"><?= count(array_filter($promociones, fn($p) => $p['estadoPromo'] == 'denegada')) ?>
                    </div>
                    <div class="stat-change">
                        <i class="fas fa-ban"></i>
                        Rechazadas
                    </div>
                </div>
            </div>

            <!-- Crear nueva promoción -->
            <div class="content-section">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-plus-circle"></i>
                        Crear Nueva Promoción
                    </h3>
                </div>

                <form method="POST" id="promotionForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><i class="fas fa-edit"></i> Texto de la Promoción *</label>
                                <textarea name="texto" class="form-control" rows="3" required
                                    placeholder="Ej: 20% de descuento en toda la colección de verano"
                                    style="resize: vertical;"><?= htmlspecialchars($_POST['texto'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-plus"></i> Fecha Desde *</label>
                                <input type="date" name="fecha_desde" class="form-control" required min="<?= date('Y-m-d') ?>"
                                    value="<?= $_POST['fecha_desde'] ?? date('Y-m-d') ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-minus"></i> Fecha Hasta *</label>
                                <input type="date" name="fecha_hasta" class="form-control" required min="<?= date('Y-m-d') ?>"
                                    value="<?= $_POST['fecha_hasta'] ?? date('Y-m-d', strtotime('+7 days')) ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="fas fa-user-tag"></i> Categoría de Cliente *</label>
                                <select name="categoria" class="form-control" required>
                                    <option value="Inicial" <?= ($_POST['categoria'] ?? '') == 'Inicial' ? 'selected' : '' ?>>
                                        Inicial
                                    </option>
                                    <option value="Medium" <?= ($_POST['categoria'] ?? '') == 'Medium' ? 'selected' : '' ?>>
                                        Medium
                                    </option>
                                    <option value="Premium" <?= ($_POST['categoria'] ?? '') == 'Premium' ? 'selected' : '' ?>>
                                        Premium
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-week"></i> Días de la Semana *</label>
                                <select name="dias_semana[]" class="form-control" multiple required>
                                    <option value="1" <?= in_array('1', $_POST['dias_semana'] ?? []) ? 'selected' : '' ?>>
                                        Lunes
                                    </option>
                                    <option value="2" <?= in_array('2', $_POST['dias_semana'] ?? []) ? 'selected' : '' ?>>
                                        Martes
                                    </option>
                                    <option value="3" <?= in_array('3', $_POST['dias_semana'] ?? []) ? 'selected' : '' ?>>
                                        Miércoles
                                    </option>
                                    <option value="4" <?= in_array('4', $_POST['dias_semana'] ?? []) ? 'selected' : '' ?>>
                                        Jueves
                                    </option>
                                    <option value="5" <?= in_array('5', $_POST['dias_semana'] ?? []) ? 'selected' : '' ?>>
                                        Viernes
                                    </option>
                                    <option value="6" <?= in_array('6', $_POST['dias_semana'] ?? []) ? 'selected' : '' ?>>
                                        Sábado
                                    </option>
                                    <option value="7" <?= in_array('7', $_POST['dias_semana'] ?? []) ? 'selected' : '' ?>>
                                        Domingo
                                    </option>
                                </select>
                                <small
                                    style="color: var(--text-secondary); font-size: 0.8rem; margin-top: 0.25rem; display: block;">
                                    <i class="fas fa-info-circle"></i>
                                    Mantén Ctrl para seleccionar múltiples días
                                </small>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: right; margin-top: 1rem;">
                        <button type="submit" name="crear_promocion" class="btn-gradient">
                            <i class="fas fa-plus"></i> Crear Promoción
                        </button>
                    </div>
                </form>
            </div>

            <!-- Filtros -->
            <div class="content-section">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-filter"></i>
                        Filtrar Promociones
                    </h3>
                </div>

                <form method="GET" class="filter-row">
                    <div class="filter-group">
                        <label>Estado</label>
                        <select name="estado" class="filter-select">
                            <option value="todos" <?= $filtro_estado == 'todos' ? 'selected' : '' ?>>Todos los estados</option>
                            <option value="pendiente" <?= $filtro_estado == 'pendiente' ? 'selected' : '' ?>>Pendientes</option>
                            <option value="aprobada" <?= $filtro_estado == 'aprobada' ? 'selected' : '' ?>>Aprobadas</option>
                            <option value="denegada" <?= $filtro_estado == 'denegada' ? 'selected' : '' ?>>Denegadas</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Categoría</label>
                        <select name="categoria" class="filter-select">
                            <option value="todas" <?= $filtro_categoria == 'todas' ? 'selected' : '' ?>>Todas las categorías
                            </option>
                            <option value="Inicial" <?= $filtro_categoria == 'Inicial' ? 'selected' : '' ?>>Inicial</option>
                            <option value="Medium" <?= $filtro_categoria == 'Medium' ? 'selected' : '' ?>>Medium</option>
                            <option value="Premium" <?= $filtro_categoria == 'Premium' ? 'selected' : '' ?>>Premium</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Buscar</label>
                        <input type="text" name="busqueda" class="filter-input" placeholder="Texto de la promoción..."
                            value="<?= htmlspecialchars($busqueda) ?>">
                    </div>

                    <div class="filter-group">
                        <button type="submit" class="btn-gradient">
                            <i class="fas fa-filter"></i> Aplicar
                        </button>
                    </div>

                    <div class="filter-group">
                        <a href="mis_promociones.php" class="btn-gradient"
                            style="background: rgba(107, 114, 128, 0.1); color: var(--text-secondary);">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Lista de promociones -->
            <div class="content-section">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-list"></i>
                        Mis Promociones
                    </h3>
                    <div style="color: var(--text-secondary); font-size: 0.875rem;">
                        <?= count($promociones) ?> promociones
                    </div>
                </div>

                <?php if (empty($promociones)): ?>
                        <div class="empty-state">
                            <i class="fas fa-tags"></i>
                            <h4>No hay promociones</h4>
                            <p>
                                <?php if (!empty($busqueda) || $filtro_estado != 'todos' || $filtro_categoria != 'todas'): ?>
                                        No se encontraron promociones con los filtros aplicados
                                <?php else: ?>
                                        Crea tu primera promoción para comenzar a atraer clientes
                                <?php endif; ?>
                            </p>
                        </div>
                <?php else: ?>
                        <?php foreach ($promociones as $promo):
                            $is_expired = strtotime($promo['fechaHastaPromo']) < strtotime(date('Y-m-d'));
                            ?>
                                <div class="promocion-card <?= $promo['estadoPromo'] ?>">
                                    <?php if ($is_expired): ?>
                                            <div class="expired-overlay">Expirada</div>
                                    <?php endif; ?>

                                    <div class="promocion-header">
                                        <div class="promocion-info">
                                            <h6>Promoción #<?= $promo['codPromo'] ?></h6>
                                            <div class="small">
                                                <i class="fas fa-calendar"></i>
                                                Creada el <?= date('d/m/Y', strtotime($promo['fechaCreacion'] ?? $promo['fechaDesdePromo'])) ?>
                                            </div>
                                        </div>
                                        <span class="estado-badge estado-<?= $promo['estadoPromo'] ?>">
                                            <?= ucfirst($promo['estadoPromo']) ?>
                                        </span>
                                    </div>

                                    <div class="promocion-text">
                                        <?= htmlspecialchars($promo['textoPromo']) ?>
                                    </div>

                                    <div class="promocion-details">
                                        <div class="detail-item">
                                            <div class="detail-label">Vigencia</div>
                                            <div class="detail-value">
                                                <i class="fas fa-play text-success"></i>
                                                <?= date('d/m/Y', strtotime($promo['fechaDesdePromo'])) ?>
                                                <br>
                                                <i class="fas fa-stop text-danger"></i>
                                                <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?>
                                            </div>
                                        </div>

                                        <div class="detail-item">
                                            <div class="detail-label">Categoría</div>
                                            <div class="detail-value">
                                                <span class="category-badge category-<?= strtolower($promo['categoriaCliente']) ?>">
                                                    <?= $promo['categoriaCliente'] ?>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="detail-item">
                                            <div class="detail-label">Días Válidos</div>
                                            <div class="detail-value">
                                                <div class="day-badges">
                                                    <?php
                                                    $dias = explode(',', $promo['diasSemana']);
                                                    $nombres_dias = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
                                                    foreach ($dias as $dia) {
                                                        if (isset($nombres_dias[$dia - 1])) {
                                                            echo '<span class="day-badge">' . $nombres_dias[$dia - 1] . '</span>';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($promo['estadoPromo'] == 'pendiente' || $is_expired): ?>
                                            <div class="action-buttons">
                                                <button class="btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-id="<?= $promo['codPromo'] ?>" data-texto="<?= htmlspecialchars($promo['textoPromo']) ?>"
                                                    data-fecha-desde="<?= $promo['fechaDesdePromo'] ?>"
                                                    data-fecha-hasta="<?= $promo['fechaHastaPromo'] ?>"
                                                    data-categoria="<?= $promo['categoriaCliente'] ?>" data-dias="<?= $promo['diasSemana'] ?>"
                                                    title="Editar promoción">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="mis_promociones.php?eliminar=<?= $promo['codPromo'] ?>" class="btn-action btn-delete"
                                                    onclick="return confirm('¿Estás seguro de eliminar esta promoción?')" title="Eliminar promoción">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                    <?php else: ?>
                                            <div style="text-align: center; padding: 0.5rem; color: var(--text-secondary); font-size: 0.8rem;">
                                                <i class="fas fa-lock"></i> Esta promoción no se puede editar porque está <?= $promo['estadoPromo'] ?>
                                            </div>
                                    <?php endif; ?>
                                </div>
                        <?php endforeach; ?>
                <?php endif; ?>
            </div>
    <?php endif; ?>
</div>

<!-- Modal para editar promoción -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="fas fa-edit"></i> Editar Promoción
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="editForm">
                <div class="modal-body" style="padding: 2rem;">
                    <input type="hidden" name="promo_id" id="edit_promo_id">

                    <div class="form-group">
                        <label><i class="fas fa-edit"></i> Texto de la Promoción *</label>
                        <textarea name="texto_edit" id="edit_texto" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-plus"></i> Fecha Desde *</label>
                                <input type="date" name="fecha_desde_edit" id="edit_fecha_desde" class="form-control"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-minus"></i> Fecha Hasta *</label>
                                <input type="date" name="fecha_hasta_edit" id="edit_fecha_hasta" class="form-control"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="fas fa-user-tag"></i> Categoría de Cliente *</label>
                                <select name="categoria_edit" id="edit_categoria" class="form-control" required>
                                    <option value="Inicial">Inicial</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Premium">Premium</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-calendar-week"></i> Días de la Semana *</label>
                        <select name="dias_semana_edit[]" id="edit_dias_semana" class="form-control" multiple required>
                            <option value="1">Lunes</option>
                            <option value="2">Martes</option>
                            <option value="3">Miércoles</option>
                            <option value="4">Jueves</option>
                            <option value="5">Viernes</option>
                            <option value="6">Sábado</option>
                            <option value="7">Domingo</option>
                        </select>
                        <small
                            style="color: var(--text-secondary); font-size: 0.8rem; margin-top: 0.25rem; display: block;">
                            <i class="fas fa-info-circle"></i>
                            Mantén Ctrl para seleccionar múltiples días
                        </small>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 1.5rem; border-top: 1px solid rgba(0, 0, 0, 0.1);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="editar_promocion" class="btn-gradient">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Animación de números en estadísticas
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

        // Inicializar el modal de edición
        const editModal = document.getElementById('editModal');
        if (editModal) {
            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const promoId = button.getAttribute('data-id');
                const texto = button.getAttribute('data-texto');
                const fechaDesde = button.getAttribute('data-fecha-desde');
                const fechaHasta = button.getAttribute('data-fecha-hasta');
                const categoria = button.getAttribute('data-categoria');
                const dias = button.getAttribute('data-dias').split(',');

                document.getElementById('edit_promo_id').value = promoId;
                document.getElementById('edit_texto').value = texto;
                document.getElementById('edit_fecha_desde').value = fechaDesde;
                document.getElementById('edit_fecha_hasta').value = fechaHasta;
                document.getElementById('edit_categoria').value = categoria;

                // Seleccionar los días
                const diasSelect = document.getElementById('edit_dias_semana');
                for (let i = 0; i < diasSelect.options.length; i++) {
                    diasSelect.options[i].selected = dias.includes(diasSelect.options[i].value);
                }
            });
        }

        // Validación de fechas
        function validateDates(form) {
            const fechaDesdeInput = form.querySelector('input[name*="fecha_desde"]');
            const fechaHastaInput = form.querySelector('input[name*="fecha_hasta"]');

            if (fechaDesdeInput && fechaHastaInput) {
                const fechaDesde = new Date(fechaDesdeInput.value);
                const fechaHasta = new Date(fechaHastaInput.value);

                if (fechaHasta <= fechaDesde) {
                    alert('La fecha de fin debe ser posterior a la fecha de inicio.');
                    return false;
                }
            }
            return true;
        }

        // Aplicar validación a formularios
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                if (!validateDates(this)) {
                    e.preventDefault();
                    return false;
                }
            });
        });

        // Animación de entrada para las tarjetas
        const cards = document.querySelectorAll('.promocion-card');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                }
            });
        });

        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease';
            observer.observe(card);
        });
    });
</script>

<?php require_once __DIR__ . '/../includes/footer-panel.php'; ?>