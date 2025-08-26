<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/header.php';
?>

<div class="container mt-4">
    <h2>Gestión de Promociones</h2>
    
    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaPromoModal">
            + Nueva Promoción
        </button>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Promoción</th>
                <th>Local</th>
                <th>Categoría</th>
                <th>Vigencia</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>20% descuento en efectivo</td>
                <td>Tienda X</td>
                <td><span class="badge bg-secondary">Inicial</span></td>
                <td>30/09/2025</td>
                <td><span class="badge bg-success">Aprobada</span></td>
                <td>
                    <button class="btn btn-sm btn-warning">Editar</button>
                    <button class="btn btn-sm btn-danger">Eliminar</button>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>2x1 en productos seleccionados</td>
                <td>Tienda Y</td>
                <td><span class="badge bg-info">Medium</span></td>
                <td>15/10/2025</td>
                <td><span class="badge bg-warning">Pendiente</span></td>
                <td>
                    <button class="btn btn-sm btn-warning">Editar</button>
                    <button class="btn btn-sm btn-danger">Eliminar</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Modal Nueva Promoción -->
<div class="modal fade" id="nuevaPromoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Promoción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="procesar_promocion.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Texto de promoción</label>
                        <input type="text" class="form-control" name="textoPromo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Local</label>
                        <select class="form-select" name="codLocal" required>
                            <option value="">Seleccionar local</option>
                            <option value="1">Tienda X</option>
                            <option value="2">Tienda Y</option>
                            <option value="3">Tienda Z</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoría de cliente</label>
                        <select class="form-select" name="categoriaCliente" required>
                            <option value="Inicial">Inicial</option>
                            <option value="Medium">Medium</option>
                            <option value="Premium">Premium</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Fecha desde</label>
                            <input type="date" class="form-control" name="fechaDesde" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha hasta</label>
                            <input type="date" class="form-control" name="fechaHasta" required>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Días de la semana</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dias[]" value="1">
                            <label class="form-check-label">Lunes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dias[]" value="2">
                            <label class="form-check-label">Martes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dias[]" value="3">
                            <label class="form-check-label">Miércoles</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dias[]" value="4">
                            <label class="form-check-label">Jueves</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dias[]" value="5">
                            <label class="form-check-label">Viernes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dias[]" value="6">
                            <label class="form-check-label">Sábado</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dias[]" value="7">
                            <label class="form-check-label">Domingo</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear Promoción</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>