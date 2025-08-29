<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess(['administrador']);

$database = new Database();
$conn = $database->getConnection();

// Aprobar o rechazar promociones
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    $promocion_id = $_POST['promocion_id'];
    $accion = $_POST['accion'];

    $estado = $accion == 'aprobar' ? 'aprobada' : 'denegada';

    $query = "UPDATE promociones SET estadoPromo = :estado WHERE codPromo = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':id', $promocion_id);

    if ($stmt->execute()) {
        $success = "Promoción $accion correctamente";
    } else {
        $error = "Error al procesar la promoción";
    }
}

// Obtener promociones pendientes
$query = "SELECT p.*, l.nombreLocal, l.ubicacionLocal, u.nombreUsuario as email_dueno
          FROM promociones p
          JOIN locales l ON p.codLocal = l.codLocal
          JOIN usuarios u ON l.codUsuario = u.codUsuario
          WHERE p.estadoPromo = 'pendiente'
          ORDER BY p.fechaDesdePromo ASC";
$promociones_pendientes = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Gestión de Promociones";
require_once '../includes/header-panel.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Gestión de Promociones</h1>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (empty($promociones_pendientes)): ?>
        <div class="alert alert-info">
            No hay promociones pendientes de aprobación.
        </div>
    <?php else: ?>
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Promociones Pendientes de Aprobación</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Promoción</th>
                                <th>Local</th>
                                <th>Ubicación</th>
                                <th>Dueño</th>
                                <th>Vigencia</th>
                                <th>Categoría</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($promociones_pendientes as $promo): ?>
                                <tr>
                                    <td><?= $promo['codPromo'] ?></td>
                                    <td><?= htmlspecialchars($promo['textoPromo']) ?></td>
                                    <td><?= htmlspecialchars($promo['nombreLocal']) ?></td>
                                    <td><?= htmlspecialchars($promo['ubicacionLocal']) ?></td>
                                    <td><?= htmlspecialchars($promo['email_dueno']) ?></td>
                                    <td>
                                        <?= date('d/m/Y', strtotime($promo['fechaDesdePromo'])) ?> - 
                                        <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?>
                                    </td>
                                    <td><?= $promo['categoriaCliente'] ?></td>
                                    <td>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="promocion_id" value="<?= $promo['codPromo'] ?>">
                                            <input type="hidden" name="accion" value="aprobar">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Aprobar
                                            </button>
                                        </form>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="promocion_id" value="<?= $promo['codPromo'] ?>">
                                            <input type="hidden" name="accion" value="rechazar">
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('¿Rechazar esta promoción?')">
                                                <i class="fas fa-times"></i> Rechazar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Promociones activas -->
    <?php
    $query = "SELECT p.*, l.nombreLocal, l.ubicacionLocal, u.nombreUsuario as email_dueno
              FROM promociones p
              JOIN locales l ON p.codLocal = l.codLocal
              JOIN usuarios u ON l.codUsuario = u.codUsuario
              WHERE p.estadoPromo = 'aprobada' AND p.fechaHastaPromo >= CURDATE()
              ORDER BY p.fechaHastaPromo ASC
              LIMIT 20";
    $promociones_activas = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="card shadow mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Promociones Activas (Últimas 20)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Promoción</th>
                            <th>Local</th>
                            <th>Vigencia</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($promociones_activas as $promo): ?>
                            <tr>
                                <td><?= $promo['codPromo'] ?></td>
                                <td><?= htmlspecialchars($promo['textoPromo']) ?></td>
                                <td><?= htmlspecialchars($promo['nombreLocal']) ?></td>
                                <td>
                                    Hasta <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?>
                                </td>
                                <td><span class="badge bg-success">Activa</span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer-panel.php'; ?>