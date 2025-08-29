<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess(['administrador']);

$database = new Database();
$conn = $database->getConnection();

// Aprobar o rechazar dueños
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $accion = $_POST['accion'];

    $estado = $accion == 'aprobar' ? 'activo' : 'rechazado';

    $query = "UPDATE usuarios SET estado = :estado WHERE codUsuario = :id AND tipoUsuario = 'dueño de local'";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':id', $usuario_id);

    if ($stmt->execute()) {
        $success = "Usuario $accion correctamente";
    } else {
        $error = "Error al procesar la solicitud";
    }
}

// Obtener dueños pendientes de validación
$query = "SELECT * FROM usuarios WHERE tipoUsuario = 'dueño de local' AND estado = 'pendiente'";
$duenos_pendientes = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Validar Dueños de Locales";
require_once '../includes/header-panel.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Validar Dueños de Locales</h1>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (empty($duenos_pendientes)): ?>
        <div class="alert alert-info">
            No hay dueños pendientes de validación.
        </div>
    <?php else: ?>
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Solicitudes Pendientes</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Fecha Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($duenos_pendientes as $dueno): ?>
                                <tr>
                                    <td><?= $dueno['codUsuario'] ?></td>
                                    <td><?= htmlspecialchars($dueno['nombreUsuario']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($dueno['fechaRegistro'])) ?></td>
                                    <td>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="usuario_id" value="<?= $dueno['codUsuario'] ?>">
                                            <input type="hidden" name="accion" value="aprobar">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Aprobar
                                            </button>
                                        </form>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="usuario_id" value="<?= $dueno['codUsuario'] ?>">
                                            <input type="hidden" name="accion" value="rechazar">
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Rechazar esta solicitud?')">
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
</div>

<?php require_once '../includes/footer-panel.php'; ?>