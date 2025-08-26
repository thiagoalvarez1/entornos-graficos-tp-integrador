<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/header.php';
?>

<div class="container mt-4">
    <h1>Panel de Administrador</h1>
    <p class="text-muted">Bienvenido, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></p>
    
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Locales</h5>
                    <p class="card-text">Gestionar locales del shopping</p>
                    <a href="gestion_locales.php" class="btn btn-primary">Administrar</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Promociones</h5>
                    <p class="card-text">Gestionar promociones</p>
                    <a href="gestion_promociones.php" class="btn btn-primary">Administrar</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Validar Dueños</h5>
                    <p class="card-text">Aprobar cuentas de dueños</p>
                    <a href="validar_duenos.php" class="btn btn-primary">Validar</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Novedades</h5>
                    <p class="card-text">Gestionar novedades</p>
                    <a href="gestion_novedades.php" class="btn btn-primary">Administrar</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Reportes</h5>
                    <p class="card-text">Ver reportes del sistema</p>
                    <a href="reportes.php" class="btn btn-primary">Ver Reportes</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>