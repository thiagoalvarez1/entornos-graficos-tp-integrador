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

<link rel="stylesheet" href="../css/gestion_novedades.css">

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