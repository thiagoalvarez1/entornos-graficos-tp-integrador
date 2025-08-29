<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess(['administrador']);

$database = new Database();
$conn = $database->getConnection();

// Crear nueva novedad
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_novedad'])) {
    $texto = trim($_POST['texto']);
    $fecha_desde = $_POST['fecha_desde'];
    $fecha_hasta = $_POST['fecha_hasta'];
    $tipo_usuario = $_POST['tipo_usuario'];

    $query = "INSERT INTO novedades (textoNovedad, fechaDesdeNovedad, fechaHastaNovedad, tipoUsuario) 
              VALUES (:texto, :fecha_desde, :fecha_hasta, :tipo_usuario)";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':texto', $texto);
    $stmt->bindParam(':fecha_desde', $fecha_desde);
    $stmt->bindParam(':fecha_hasta', $fecha_hasta);
    $stmt->bindParam(':tipo_usuario', $tipo_usuario);

    if ($stmt->execute()) {
        $success = "Novedad creada exitosamente";
    } else {
        $error = "Error al crear la novedad";
    }
}

// Obtener novedades
$query = "SELECT * FROM novedades ORDER BY fechaDesdeNovedad DESC";
$novedades = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Gestión de Novedades";
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
        max-width: 1200px;
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

    .card-header h6::before {
        content: '';
        width: 8px;
        height: 8px;
        background: var(--primary-purple);
        border-radius: 50%;
        animation: pulse 2s infinite;
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
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid rgba(99, 102, 241, 0.1);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.8);
        font-size: 0.875rem;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-purple);
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
        background: white;
    }

    .form-control::placeholder {
        color: var(--text-secondary);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
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

    .btn-block {
        width: 100%;
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
        transform: translateX(2px);
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
    }

    .bg-success {
        background: linear-gradient(135deg, var(--accent-green), #059669);
        color: white;
    }

    .bg-secondary {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }

    /* Row and Column Styles */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -0.75rem;
    }

    .col-md-4,
    .col-md-8 {
        padding: 0 0.75rem;
    }

    .col-md-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }

    .col-md-8 {
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .col-md-4,
        .col-md-8 {
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
    }

    /* Animation for table rows */
    .table tbody tr {
        opacity: 0;
        animation: slideInUp 0.5s ease forwards;
    }

    .table tbody tr:nth-child(1) {
        animation-delay: 0.1s;
    }

    .table tbody tr:nth-child(2) {
        animation-delay: 0.2s;
    }

    .table tbody tr:nth-child(3) {
        animation-delay: 0.3s;
    }

    .table tbody tr:nth-child(4) {
        animation-delay: 0.4s;
    }

    .table tbody tr:nth-child(5) {
        animation-delay: 0.5s;
    }

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

    /* Form animations */
    .form-control {
        transform: translateY(0);
        transition: all 0.3s ease;
    }

    .form-control:focus {
        transform: translateY(-2px);
    }

    /* Card entrance animation */
    .card {
        opacity: 0;
        transform: translateY(20px);
        animation: cardSlideIn 0.6s ease forwards;
    }

    .card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .card:nth-child(2) {
        animation-delay: 0.3s;
    }

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

    /* Action buttons grouping */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    /* Status indicator */
    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-indicator::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        animation: statusPulse 2s infinite;
    }

    .bg-success::before {
        background: var(--accent-green);
    }

    .bg-secondary::before {
        background: #6b7280;
    }

    @keyframes statusPulse {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.7;
            transform: scale(0.9);
        }
    }
</style>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">Gestión de Novedades</h1>
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

    <!-- Formulario para crear novedad -->
    <div class="card">
        <div class="card-header">
            <h6><i class="fas fa-plus-circle"></i> Crear Nueva Novedad</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label><i class="fas fa-edit"></i> Texto de la Novedad *</label>
                            <textarea name="texto" class="form-control" rows="3" required
                                placeholder="Ej: ¡Nueva colección de verano disponible!"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><i class="fas fa-users"></i> Dirigido a *</label>
                            <select name="tipo_usuario" class="form-control" required>
                                <option value="todos">Todos los usuarios</option>
                                <option value="cliente">Solo clientes</option>
                                <option value="dueño de local">Solo dueños</option>
                                <option value="administrador">Solo administradores</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><i class="fas fa-calendar-plus"></i> Fecha Desde *</label>
                            <input type="date" name="fecha_desde" class="form-control" required
                                value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><i class="fas fa-calendar-minus"></i> Fecha Hasta *</label>
                            <input type="date" name="fecha_hasta" class="form-control" required
                                value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" name="crear_novedad" class="btn btn-primary btn-block">
                                <i class="fas fa-plus"></i> Crear Novedad
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de novedades -->
    <div class="card">
        <div class="card-header">
            <h6><i class="fas fa-list"></i> Novedades Existentes</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-newspaper"></i> Novedad</th>
                            <th><i class="fas fa-user-tag"></i> Dirigido a</th>
                            <th><i class="fas fa-calendar-alt"></i> Vigencia</th>
                            <th><i class="fas fa-signal"></i> Estado</th>
                            <th><i class="fas fa-cog"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($novedades as $novedad):
                            $vigente = (strtotime($novedad['fechaHastaNovedad']) >= strtotime(date('Y-m-d')));
                            ?>
                            <tr>
                                <td><strong><?= $novedad['codNovedad'] ?></strong></td>
                                <td>
                                    <div class="text-truncate" style="max-width: 300px;"
                                        title="<?= htmlspecialchars($novedad['textoNovedad']) ?>">
                                        <?= htmlspecialchars($novedad['textoNovedad']) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge"
                                        style="background: linear-gradient(135deg, var(--accent-blue), #0284c7); color: white;">
                                        <?= $novedad['tipoUsuario'] == 'todos' ? 'Todos' : ucfirst($novedad['tipoUsuario']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <i class="fas fa-play text-success"></i>
                                        <?= date('d/m/Y', strtotime($novedad['fechaDesdeNovedad'])) ?>
                                        <br>
                                        <i class="fas fa-stop text-danger"></i>
                                        <?= date('d/m/Y', strtotime($novedad['fechaHastaNovedad'])) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge status-indicator bg-<?= $vigente ? 'success' : 'secondary' ?>">
                                        <?= $vigente ? 'Vigente' : 'Expirada' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="editar_novedad.php?id=<?= $novedad['codNovedad'] ?>"
                                            class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="eliminar_novedad.php?id=<?= $novedad['codNovedad'] ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('¿Eliminar esta novedad?')" title="Eliminar">
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
    document.addEventListener('DOMContentLoaded', function () {
        // Add smooth hover effects to form inputs
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function () {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 8px 25px rgba(99, 102, 241, 0.15)';
            });

            input.addEventListener('blur', function () {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });

        // Add ripple effect to buttons
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('click', function (e) {
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

        // Auto-resize textarea
        const textarea = document.querySelector('textarea[name="texto"]');
        if (textarea) {
            textarea.addEventListener('input', function () {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        }
    });
</script>

<?php require_once '../includes/footer-panel.php'; ?>