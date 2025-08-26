<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/header.php';
?>

<div class="container mt-4">
    <h2>Gestión de Locales</h2>
    
    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoLocalModal">
            + Nuevo Local
        </button>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th>Rubro</th>
                <th>Dueño</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Tienda X</td>
                <td>Planta Baja - Local 12</td>
                <td>Indumentaria</td>
                <td>Juan Pérez</td>
                <td>
                    <button class="btn btn-sm btn-warning">Editar</button>
                    <button class="btn btn-sm btn-danger">Eliminar</button>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Tienda Y</td>
                <td>Primer Piso - Local 45</td>
                <td>Calzado</td>
                <td>María García</td>
                <td>
                    <button class="btn btn-sm btn-warning">Editar</button>
                    <button class="btn btn-sm btn-danger">Eliminar</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Modal Nuevo Local -->
<div class="modal fade" id="nuevoLocalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Local</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="procesar_local.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nombre del local</label>
                        <input type="text" class="form-control" name="nombreLocal" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ubicación</label>
                        <input type="text" class="form-control" name="ubicacionLocal" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rubro</label>
                        <select class="form-select" name="rubroLocal" required>
                            <option value="">Seleccionar rubro</option>
                            <option value="indumentaria">Indumentaria</option>
                            <option value="calzado">Calzado</option>
                            <option value="perfumeria">Perfumería</option>
                            <option value="optica">Óptica</option>
                            <option value="comida">Comida</option>
                            <option value="electronica">Electrónica</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dueño asignado</label>
                        <select class="form-select" name="codUsuario">
                            <option value="">Sin asignar</option>
                            <option value="1">Juan Pérez (juan@mail.com)</option>
                            <option value="2">María García (maria@mail.com)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear Local</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>