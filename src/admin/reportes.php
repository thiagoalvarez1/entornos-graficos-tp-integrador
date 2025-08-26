<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/header.php';
?>

<div class="container mt-4">
    <h2>Reportes y Estadísticas</h2>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Promociones</h5>
                    <h2>47</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <h5 class="card-title">Promociones Activas</h5>
                    <h2>32</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Clientes</h5>
                    <h2>156</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body text-center">
                    <h5 class="card-title">Usos Hoy</h5>
                    <h2>18</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Promociones más utilizadas</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Promoción</th>
                        <th>Local</th>
                        <th>Usos totales</th>
                        <th>Último uso</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>20% descuento en efectivo</td>
                        <td>Tienda X</td>
                        <td>45</td>
                        <td>25/08/2025</td>
                    </tr>
                    <tr>
                        <td>2x1 en productos seleccionados</td>
                        <td>Tienda Y</td>
                        <td>32</td>
                        <td>24/08/2025</td>
                    </tr>
                    <tr>
                        <td>30% off en segunda unidad</td>
                        <td>Tienda Z</td>
                        <td>28</td>
                        <td>23/08/2025</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Distribución de clientes por categoría</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-secondary me-2">Inicial</span>
                        <span>85 clientes (54%)</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-info me-2">Medium</span>
                        <span>45 clientes (29%)</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-warning me-2">Premium</span>
                        <span>26 clientes (17%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>