<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

require_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="https://via.placeholder.com/150/3498db/ffffff?text=<?= substr($_SESSION['usuario']['nombre'], 0, 1) ?>" 
                         class="rounded-circle mb-3" alt="Avatar" style="width: 150px; height: 150px; object-fit: cover;">
                    <h4><?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></h4>
                    <p class="text-muted"><?= htmlspecialchars($_SESSION['usuario']['email']) ?></p>
                    
                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-<?= 
                            ($_SESSION['usuario']['rol'] == 'administrador') ? 'danger' : 
                            (($_SESSION['usuario']['rol'] == 'dueno') ? 'warning' : 'info') 
                        ?> fs-6">
                            <i class="fas fa-<?= 
                                ($_SESSION['usuario']['rol'] == 'administrador') ? 'cog' : 
                                (($_SESSION['usuario']['rol'] == 'dueno') ? 'store' : 'user') 
                            ?> me-1"></i>
                            <?= ucfirst($_SESSION['usuario']['rol']) ?>
                        </span>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="logout.php" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Editar Perfil</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre completo</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?= htmlspecialchars($_SESSION['usuario']['email']) ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nueva contraseña</label>
                                <input type="password" class="form-control" placeholder="Dejar vacío para no cambiar">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirmar contraseña</label>
                                <input type="password" class="form-control" placeholder="Confirmar nueva contraseña">
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Estadísticas -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Mis Estadísticas</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="border rounded p-3">
                                <h3 class="text-primary">12</h3>
                                <small class="text-muted">Promociones usadas</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-3">
                                <h3 class="text-success">8</h3>
                                <small class="text-muted">Aceptadas</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-3">
                                <h3 class="text-warning">4</h3>
                                <small class="text-muted">Pendientes</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>