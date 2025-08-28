<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'dueno') {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/header.php';
?>

<div class="container mt-4">
    <h2>Mis Promociones</h2>
    <p class="text-muted">Gestiona las promociones de tu local</p>
    
    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaPromoModal">
            + Nueva Promoción
        </button>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">20% descuento en efectivo</h5>
                    <p class="card-text">Válido: 01/09/2025 - 30/09/2025</p>
                    <p class="card-text">
                        <span class="badge bg-secondary">Inicial</span>
                        <span class="badge bg-success ms-1">Aprobada</span>
                    </p>
                    <p class="card-text">15 usos totales</p>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">2x1 en productos seleccionados</h5>
                    <p class="card-text">Válido: 15/09/2025 - 15/10/2025</p>
                    <p class="card-text">
                        <span class="badge bg-info">Medium</span>
                        <span class="badge bg-warning ms-1">Pendiente</span>
                    </p>
                    <p class="card-text">0 usos</p>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Promoción (similar al de admin pero simplificado) -->
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
                        <!-- Checkboxes de días igual que en admin -->
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