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

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Gestión de Locales</h1>

    <!-- Formulario para crear local -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Crear Nuevo Local</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nombre del Local *</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Ubicación *</label>
                            <input type="text" name="ubicacion" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Rubro *</label>
                            <select name="rubro" class="form-control" required>
                                <option value="">Seleccionar rubro</option>
                                <option value="indumentaria">Indumentaria</option>
                                <option value="calzado">Calzado</option>
                                <option value="tecnologia">Tecnología</option>
                                <option value="comida">Comida</option>
                                <option value="perfumeria">Perfumería</option>
                                <option value="joyeria">Joyería</option>
                                <option value="deportes">Deportes</option>
                                <option value="hogar">Hogar</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Dueño</label>
                            <select name="dueno_id" class="form-control">
                                <option value="">Sin dueño</option>
                                <?php foreach ($duenos as $dueno): ?>
                                    <option value="<?= $dueno['codUsuario'] ?>"><?= $dueno['email_dueno'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" name="crear_local" class="btn btn-primary">Crear Local</button>
            </form>
        </div>
    </div>

    <!-- Lista de locales -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Locales Registrados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Rubro</th>
                            <th>Dueño</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($locales as $local): ?>
                            <tr>
                                <td><?= $local['codLocal'] ?></td>
                                <td><?= htmlspecialchars($local['nombreLocal']) ?></td>
                                <td><?= htmlspecialchars($local['ubicacionLocal']) ?></td>
                                <td><?= ucfirst($local['rubroLocal']) ?></td>
                                <td><?= $local['email_dueno'] ?: 'Sin asignar' ?></td>
                                <td>
                                    <span class="badge badge-<?= $local['estado'] == 'activo' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($local['estado']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="editar_local.php?id=<?= $local['codLocal'] ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="eliminar_local.php?id=<?= $local['codLocal'] ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Estás seguro de eliminar este local?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer-panel.php'; ?>