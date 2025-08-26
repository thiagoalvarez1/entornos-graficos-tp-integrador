<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/header.php';
?>

<div class="container mt-4">
    <h2>Gestión de Novedades</h2>
    
    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaNovedadModal">
            + Nueva Novedad
        </button>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Promoción Especial</h5>
                    <p class="card-text">Descuentos del 50% en todos los locales este fin de semana</p>
                    <p class="card-text">
                        <small class="text-muted">Vigente: 26/08/2025 - 28/08/2025</small>
                    </p>
                    <p class="card-text">
                        <span class="badge bg-info">Para: Todos los clientes</span>
                    </p>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-warning">Editar</button>
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Evento Premium</h5>
                    <p class="card-text">Noche de compras exclusiva para clientes Premium</p>
                    <p class="card-text">
                        <small class="text-muted">Vigente: 30/08/2025 - 30/08/2025</small>
                    </p>
                    <p class="card-text">
                        <span class="badge bg-warning">Para: Clientes Premium</span>
                    </p>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-warning">Editar</button>
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Novedad -->
<div class="modal fade" id="nuevaNovedadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Novedad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="procesar_novedad.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Título de la novedad</label>
                        <input type="text" class="form-control" name="tituloNovedad" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="textoNovedad" rows="3" required></textarea>
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
                        <label class="form-label">Dirigido a</label>
                        <select class="form-select" name="categoriaDestino" required>
                            <option value="todos">Todos los clientes</option>
                            <option value="Inicial">Clientes Inicial</option>
                            <option value="Medium">Clientes Medium</option>
                            <option value="Premium">Clientes Premium</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear Novedad</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>