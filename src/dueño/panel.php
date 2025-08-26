<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'cliente') {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/header.php';
?>

<div class="container mt-4">
    <h2>Mi Panel de Cliente</h2>
    
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h5>Mi Categoría</h5>
                    <h3>Inicial</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h5>Promociones Usadas</h5>
                    <h3>3</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h5>Último uso</h5>
                    <h3>25/08/2025</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Mis Promociones Usadas</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Promoción</th>
                        <th>Local</th>
                        <th>Fecha uso</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>20% descuento en efectivo</td>
                        <td>Tienda X</td>
                        <td>25/08/2025</td>
                        <td><span class="badge bg-success">Aceptada</span></td>
                    </tr>
                    <tr>
                        <td>2x1 en productos seleccionados</td>
                        <td>Tienda Y</td>
                        <td>20/08/2025</td>
                        <td><span class="badge bg-warning">Pendiente</span></td>
                    </tr>
                    <tr>
                        <td>30% off en segunda unidad</td>
                        <td>Tienda Z</td>
                        <td>15/08/2025</td>
                        <td><span class="badge bg-success">Aceptada</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Novedades para mí</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <h6>Promoción Especial</h6>
                <p>Descuentos del 50% en todos los locales este fin de semana</p>
                <small>Válido hasta: 28/08/2025</small>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>