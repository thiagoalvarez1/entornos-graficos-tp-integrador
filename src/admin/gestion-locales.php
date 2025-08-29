<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess(['administrador']);

$database = new Database();
$conn = $database->getConnection();

// Crear nuevo local
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_local'])) {
    $nombre = trim($_POST['nombre']);
    $ubicacion = trim($_POST['ubicacion']);
    $rubro = trim($_POST['rubro']);
    $dueno_id = trim($_POST['dueno_id']);

    $query = "INSERT INTO locales (nombreLocal, ubicacionLocal, rubroLocal, codUsuario) 
              VALUES (:nombre, :ubicacion, :rubro, :dueno_id)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':ubicacion', $ubicacion);
    $stmt->bindParam(':rubro', $rubro);
    $stmt->bindParam(':dueno_id', $dueno_id);

    if ($stmt->execute()) {
        $success = "Local creado exitosamente";
    } else {
        $error = "Error al crear el local";
    }
}

// Obtener lista de locales
$query = "SELECT l.*, u.nombreUsuario as email_dueno 
          FROM locales l 
          LEFT JOIN usuarios u ON l.codUsuario = u.codUsuario 
          ORDER BY l.nombreLocal";
$locales = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Obtener dueños disponibles
$query = "SELECT codUsuario, nombreUsuario FROM usuarios WHERE tipoUsuario = 'dueño de local' AND estado = 'activo'";
$duenos = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Gestión de Locales";
require_once '../includes/header-panel.php';
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    :root {
        --primary-purple: #6366f1;
        --secondary-purple: #8b5cf6;
        --accent-blue: #0ea5e9;
        --accent-green: #10b981;
        --accent-orange: #f59e0b;
        --accent-red: #ef4444;
        --gradient-start: #667eea;
        --gradient-middle: #764ba2;
        --gradient-end: #f093fb;
        --dark-bg: #0f172a;
        --dark-surface: #1e293b;
        --glass-bg: rgba(255, 255, 255, 0.1);
        --glass-border: rgba(255, 255, 255, 0.2);
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --shadow-light: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-medium: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --shadow-heavy: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(-45deg, var(--gradient-start), var(--gradient-middle), var(--secondary-purple), var(--gradient-end));
        background-size: 400% 400%;
        animation: gradientShift 20s ease infinite;
        min-height: 100vh;
        overflow-x: hidden;
        position: relative;
    }

    @keyframes gradientShift {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 80%, rgba(14, 165, 233, 0.1) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
    }

    .container-fluid {
        position: relative;
        z-index: 1;
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title {
        color: white;
        font-size: 2rem;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-title::before {
        content: '';
        width: 12px;
        height: 12px;
        background: var(--accent-green);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.7;
            transform: scale(0.9);
        }
    }

    /* Alert Styles */
    .alert {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-medium);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        border-left: 4px solid var(--accent-green);
        color: var(--accent-green);
        background: rgba(16, 185, 129, 0.1);
    }

    .alert-danger {
        border-left: 4px solid var(--accent-red);
        color: var(--accent-red);
        background: rgba(239, 68, 68, 0.1);
    }

    /* Card Styles */
    .card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: var(--shadow-medium);
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-heavy);
    }

    .card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(139, 92, 246, 0.05));
        position: relative;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-purple), var(--secondary-purple));
    }

    .card-header h6 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-body {
        padding: 2rem;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid rgba(99, 102, 241, 0.1);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.8);
        font-size: 0.875rem;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary-purple);
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
        background: white;
        transform: translateY(-2px);
    }

    .form-control::placeholder {
        color: var(--text-secondary);
    }

    /* Button Styles */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
        font-family: inherit;
        position: relative;
        overflow: hidden;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        color: white;
        box-shadow: var(--shadow-medium);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-heavy);
        color: white;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }

    .btn-warning {
        background: linear-gradient(135deg, var(--accent-orange), #d97706);
        color: white;
        box-shadow: var(--shadow-light);
    }

    .btn-warning:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-medium);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--accent-red), #dc2626);
        color: white;
        box-shadow: var(--shadow-light);
    }

    .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-medium);
        color: white;
    }

    /* Table Styles */
    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
        background: white;
        box-shadow: var(--shadow-light);
    }

    .table {
        margin: 0;
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.05));
        border: none;
        font-weight: 700;
        color: var(--text-primary);
        padding: 1rem;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        position: relative;
    }

    .table thead th::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 1rem;
        right: 1rem;
        height: 2px;
        background: linear-gradient(90deg, var(--primary-purple), transparent);
    }

    .table tbody td {
        border: none;
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        color: var(--text-primary);
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background: rgba(99, 102, 241, 0.02);
        transform: translateX(4px);
    }

    /* Badge Styles */
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        position: relative;
        overflow: hidden;
    }

    .badge::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        animation: statusPulse 2s infinite;
    }

    .badge-success {
        background: linear-gradient(135deg, var(--accent-green), #059669);
        color: white;
    }

    .badge-success::before {
        background: rgba(255, 255, 255, 0.8);
    }

    .badge-danger {
        background: linear-gradient(135deg, var(--accent-red), #dc2626);
        color: white;
    }

    .badge-danger::before {
        background: rgba(255, 255, 255, 0.8);
    }

    @keyframes statusPulse {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.7;
            transform: scale(0.9);
        }
    }

    /* Row and Column Styles */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -0.75rem;
    }

    .col-md-2, .col-md-3, .col-md-4 {
        padding: 0 0.75rem;
    }

    .col-md-2 {
        flex: 0 0 16.666667%;
        max-width: 16.666667%;
    }

    .col-md-3 {
        flex: 0 0 25%;
        max-width: 25%;
    }

    .col-md-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }

    /* Rubro Icons */
    .rubro-icon {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .rubro-indumentaria {
        background: rgba(139, 92, 246, 0.1);
        color: var(--secondary-purple);
    }

    .rubro-calzado {
        background: rgba(245, 158, 11, 0.1);
        color: var(--accent-orange);
    }

    .rubro-tecnologia {
        background: rgba(14, 165, 233, 0.1);
        color: var(--accent-blue);
    }

    .rubro-comida {
        background: rgba(239, 68, 68, 0.1);
        color: var(--accent-red);
    }

    .rubro-perfumeria {
        background: rgba(236, 72, 153, 0.1);
        color: #ec4899;
    }

    .rubro-joyeria {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .rubro-deportes {
        background: rgba(16, 185, 129, 0.1);
        color: var(--accent-green);
    }

    .rubro-hogar {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
    }

    /* Action buttons grouping */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    /* Stats Cards */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-mini {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-mini::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-purple);
    }

    .stat-mini:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }

    .stat-mini-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.125rem;
    }

    .stat-mini-content h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .stat-mini-content p {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .col-md-2, .col-md-3, .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .card-body {
            padding: 1.5rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .table thead th,
        .table tbody td {
            padding: 0.75rem 0.5rem;
        }

        .stats-row {
            grid-template-columns: 1fr;
        }
    }

    /* Animation for table rows */
    .table tbody tr {
        opacity: 0;
        animation: slideInUp 0.5s ease forwards;
    }

    .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
    .table tbody tr:nth-child(2) { animation-delay: 0.2s; }
    .table tbody tr:nth-child(3) { animation-delay: 0.3s; }
    .table tbody tr:nth-child(4) { animation-delay: 0.4s; }
    .table tbody tr:nth-child(5) { animation-delay: 0.5s; }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Card entrance animation */
    .card {
        opacity: 0;
        transform: translateY(20px);
        animation: cardSlideIn 0.6s ease forwards;
    }

    .card:nth-child(2) { animation-delay: 0.1s; }
    .card:nth-child(3) { animation-delay: 0.3s; }

    @keyframes cardSlideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-store"></i>
            Gestión de Locales
        </h1>
    </div>

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

    <!-- Mini Stats -->
    <div class="stats-row">
        <div class="stat-mini">
            <div class="stat-mini-icon">
                <i class="fas fa-store"></i>
            </div>
            <div class="stat-mini-content">
                <h3><?= count($locales) ?></h3>
                <p>Total Locales</p>
            </div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-icon" style="background: linear-gradient(135deg, var(--accent-green), #059669);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-mini-content">
                <h3><?= count(array_filter($locales, fn($l) => $l['estado'] == 'activo')) ?></h3>
                <p>Locales Activos</p>
            </div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-icon" style="background: linear-gradient(135deg, var(--accent-blue), #0284c7);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-mini-content">
                <h3><?= count($duenos) ?></h3>
                <p>Dueños Disponibles</p>
            </div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-icon" style="background: linear-gradient(135deg, var(--accent-orange), #d97706);">
                <i class="fas fa-user-times"></i>
            </div>
            <div class="stat-mini-content">
                <h3><?= count(array_filter($locales, fn($l) => empty($l['email_dueno']))) ?></h3>
                <p>Sin Dueño</p>
            </div>
        </div>
    </div>

    <!-- Formulario para crear local -->
    <div class="card">
        <div class="card-header">
            <h6><i class="fas fa-plus-circle"></i> Crear Nuevo Local</h6>
        </div>
        <div class="card-body">
            <form method="POST" id="storeForm">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><i class="fas fa-store"></i> Nombre del Local *</label>
                            <input type="text" name="nombre" class="form-control" required 
                                placeholder="Ej: Fashion Store">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><i class="fas fa-map-marker-alt"></i> Ubicación *</label>
                            <input type="text" name="ubicacion" class="form-control" required
                                placeholder="Ej: Planta Baja, Local 15">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><i class="fas fa-tags"></i> Rubro *</label>
                            <select name="rubro" class="form-control" required>
                                <option value="">Seleccionar rubro</option>
                                <option value="indumentaria">👕 Indumentaria</option>
                                <option value="calzado">👟 Calzado</option>
                                <option value="tecnologia">📱 Tecnología</option>
                                <option value="comida">🍔 Comida</option>
                                <option value="perfumeria">💄 Perfumería</option>
                                <option value="joyeria">💎 Joyería</option>
                                <option value="deportes">⚽ Deportes</option>
                                <option value="hogar">🏠 Hogar</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label><i class="fas fa-user-tie"></i> Dueño</label>
                            <select name="dueno_id" class="form-control">
                                <option value="">Sin dueño</option>
                                <?php foreach ($duenos as $dueno): ?>
                                        <option value="<?= $dueno['codUsuario'] ?>"><?= $dueno['nombreUsuario'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" name="crear_local" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Crear Local
                </button>
            </form>
        </div>
    </div>

    <!-- Lista de locales -->
    <div class="card">
        <div class="card-header">
            <h6><i class="fas fa-list"></i> Locales Registrados (<?= count($locales) ?>)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="dataTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-store"></i> Nombre</th>
                            <th><i class="fas fa-map-marker-alt"></i> Ubicación</th>
                            <th><i class="fas fa-tags"></i> Rubro</th>
                            <th><i class="fas fa-user-tie"></i> Dueño</th>
                            <th><i class="fas fa-signal"></i> Estado</th>
                            <th><i class="fas fa-cog"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($locales as $local): ?>
                                <tr>
                                    <td><strong><?= $local['codLocal'] ?></strong></td>
                                    <td>
                                        <div style="font-weight: 600; color: var(--text-primary);">
                                            <?= htmlspecialchars($local['nombreLocal']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <i class="fas fa-map-pin text-muted"></i>
                                            <?= htmlspecialchars($local['ubicacionLocal']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $rubro_icons = [
                                            'indumentaria' => 'fas fa-tshirt',
                                            'calzado' => 'fas fa-shoe-prints',
                                            'tecnologia' => 'fas fa-laptop',
                                            'comida' => 'fas fa-hamburger',
                                            'perfumeria' => 'fas fa-spray-can',
                                            'joyeria' => 'fas fa-gem',
                                            'deportes' => 'fas fa-football-ball',
                                            'hogar' => 'fas fa-home'
                                        ];
                                        ?>
                                        <span class="rubro-icon rubro-<?= $local['rubroLocal'] ?>">
                                            <i class="<?= $rubro_icons[$local['rubroLocal']] ?? 'fas fa-store' ?>"></i>
                                            <?= ucfirst($local['rubroLocal']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($local['email_dueno']): ?>
                                                <div class="d-flex align-items-center gap-1">
                                                    <i class="fas fa-user-check text-success"></i>
                                                    <?= $local['email_dueno'] ?>
                                                </div>
                                        <?php else: ?>
                                                <div class="d-flex align-items-center gap-1">
                                                    <i class="fas fa-user-times text-warning"></i>
                                                    <em>Sin asignar</em>
                                                </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= $local['estado'] == 'activo' ? 'success' : 'danger' ?>">
                                            <?= ucfirst($local['estado']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="editar_local.php?id=<?= $local['codLocal'] ?>" 
                                               class="btn btn-sm btn-warning" title="Editar local">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="eliminar_local.php?id=<?= $local['codLocal'] ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('¿Estás seguro de eliminar este local?')"
                                               title="Eliminar local">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth hover effects to form inputs
        const inputs = document.querySelectorAll('.form-control, .form-select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 8px 25px rgba(99, 102, 241, 0.15)';
            });
            
            input.addEventListener('blur', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });

        // Add ripple effect to buttons
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('div');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255, 255, 255, 0.3)';
                ripple.style.animation = 'ripple-animation 0.6s ease-out';
                ripple.style.pointerEvents = 'none';

                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple-animation {
                0% {
                    transform: scale(0);
                    opacity: 1;
                }
                100% {
                    transform: scale(2);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Form validation enhancements
        const storeForm = document.getElementById('storeForm');
        if (storeForm) {
            storeForm.addEventListener('submit', function(e) {
                const nombre = document.querySelector('input[name="nombre"]').value.trim();
                const ubicacion = document.querySelector('input[name="ubicacion"]').value.trim();
                const rubro = document.querySelector('select[name="rubro"]').value;

                if (!nombre || !ubicacion || !rubro) {
                    e.preventDefault();
                    alert('Por favor, completa todos los campos obligatorios.');
                    return false;
                }

                // Add loading state to submit button
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando...';
                
                // Re-enable after 3 seconds (in case form doesn't redirect)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-plus"></i> Crear Local';
                }, 3000);
            });
        }

        // Enhanced table interactions
        const tableRows = document.querySelectorAll('.table tbody tr');
        tableRows.forEach((row, index) => {
            row.style.animationDelay = (index * 0.1) + 's';
            
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(8px)';
                this.style.boxShadow = '0 4px 12px rgba(99, 102, 241, 0.1)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(4px)';
                this.style.boxShadow = 'none';
            });
        });

        // Stats animation on scroll
        const statsCards = document.querySelectorAll('.stat-mini');
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'statSlideIn 0.6s ease forwards';
                }
            });
        }, observerOptions);

        statsCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.animationDelay = (index * 0.1) + 's';
            observer.observe(card);
        });

        // Add stat animation CSS
        const statStyle = document.createElement('style');
        statStyle.textContent = `
            @keyframes statSlideIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(statStyle);

        // Enhanced search functionality (if needed later)
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.placeholder = 'Buscar locales...';
        searchInput.className = 'form-control';
        searchInput.style.maxWidth = '300px';
        
        // Add search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    row.style.animation = 'fadeIn 0.3s ease';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // You can add this search input to the card header if needed
        // document.querySelector('.card-header h6').parentNode.appendChild(searchInput);
    });
</script>

<?php require_once '../includes/footer-panel.php'; ?>