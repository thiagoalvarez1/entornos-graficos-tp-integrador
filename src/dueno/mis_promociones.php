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
$query = "SELECT codLocal FROM locales WHERE codUsuario = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$local = $stmt->fetch(PDO::FETCH_ASSOC);

$local_id = $local ? $local['codLocal'] : null;

// Crear nueva promoción
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_promocion']) && $local_id) {
    $texto = trim($_POST['texto']);
    $fecha_desde = $_POST['fecha_desde'];
    $fecha_hasta = $_POST['fecha_hasta'];
    $categoria = $_POST['categoria'];
    $dias_semana = implode(',', $_POST['dias_semana'] ?? []);

    $query = "INSERT INTO promociones (textoPromo, fechaDesdePromo, fechaHastaPromo, 
              categoriaCliente, diasSemana, codLocal, estadoPromo) 
              VALUES (:texto, :fecha_desde, :fecha_hasta, :categoria, :dias_semana, :local_id, 'pendiente')";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':texto', $texto);
    $stmt->bindParam(':fecha_desde', $fecha_desde);
    $stmt->bindParam(':fecha_hasta', $fecha_hasta);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':dias_semana', $dias_semana);
    $stmt->bindParam(':local_id', $local_id);

    if ($stmt->execute()) {
        $success = "Promoción creada. Esperando aprobación del administrador.";
    } else {
        $error = "Error al crear la promoción";
    }
}

// Obtener promociones del local
$promociones = [];
if ($local_id) {
    $query = "SELECT * FROM promociones WHERE codLocal = :local_id ORDER BY fechaDesdePromo DESC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':local_id', $local_id);
    $stmt->execute();
    $promociones = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$pageTitle = "Mis Promociones";
require_once '../includes/header-panel.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Mis Promociones</h1>

    <?php if (!$local_id): ?>
    <div class="alert alert-warning">
        No tienes un local asignado. Contacta al administrador.
    </div>
    <?php else: ?>

    <!-- Formulario para crear promoción -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Crear Nueva Promoción</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Texto de la Promoción *</label>
                            <textarea name="texto" class="form-control" rows="3" required 
                                      placeholder="Ej: 20% de descuento en toda la colección de verano"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Fecha Desde *</label>
                            <input type="date" name="fecha_desde" class="form-control" required 
                                   min="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Fecha Hasta *</label>
                            <input type="date" name="fecha_hasta" class="form-control" required 
                                   min="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Categoría de Cliente *</label>
                            <select name="categoria" class="form-control" required>
                                <option value="Inicial">Inicial</option>
                                <option value="Medium">Medium</option>
                                <option value="Premium">Premium</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Días de la Semana *</label>
                            <select name="dias_semana[]" class="form-control" multiple required>
                                <option value="1">Lunes</option>
                                <option value="2">Martes</option>
                                <option value="3">Miércoles</option>
                                <option value="4">Jueves</option>
                                <option value="5">Viernes</option>
                                <option value="6">Sábado</option>
                                <option value="7">Domingo</option>
                            </select>
                            <small class="form-text text-muted">Mantén Ctrl para seleccionar múltiples días</small>
                        </div>
                    </div>
                </div>

                <button type="submit" name="crear_promocion" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Crear Promoción
                </button>
            </form>
        </div>
    </div>

    <!-- Lista de promociones -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Mis Promociones</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Promoción</th>
                            <th>Vigencia</th>
                            <th>Categoría</th>
                            <th>Días</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($promociones as $promo): ?>
                        <tr>
                            <td><?= $promo['codPromo'] ?></td>
                            <td><?= htmlspecialchars($promo['textoPromo']) ?></td>
                            <td>
                                <?= date('d/m/Y', strtotime($promo['fechaDesdePromo'])) ?> - 
                                <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?>
                            </td>
                            <td><?= $promo['categoriaCliente'] ?></td>
                            <td>
                                <?php
                                $dias = explode(',', $promo['diasSemana']);
                                $nombres_dias = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
                                foreach ($dias as $dia) {
                                    if (isset($nombres_dias[$dia-1])) {
                                        echo '<span class="badge bg-secondary me-1">' . $nombres_dias[$dia-1] . '</span>';
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $badge_class = [
                                    'pendiente' => 'warning',
                                    'aprobada' => 'success',
                                    'denegada' => 'danger'
                                ][$promo['estadoPromo']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $badge_class ?>">
                                    <?= ucfirst($promo['estadoPromo']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($promo['estadoPromo'] == 'pendiente'): ?>
                                <a href="eliminar_promocion.php?id=<?= $promo['codPromo'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('¿Eliminar esta promoción?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer-panel.php'; ?>