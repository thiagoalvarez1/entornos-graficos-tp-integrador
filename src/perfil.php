<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

require_once 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="https://via.placeholder.com/150" class="rounded-circle mb-3" alt="Avatar">
                    <h4><?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></h4>
                    <p class="text-muted"><?= htmlspecialchars($_SESSION['usuario']['email']) ?></p>
                    <span class="badge bg-<?= 
                        ($_SESSION['usuario']['rol'] == 'administrador') ? 'danger' : 
                        (($_SESSION['usuario']['rol'] == 'dueno') ? 'warning' : 'info') 
                    ?>">
                        <?= ucfirst($_SESSION['usuario']['rol']) ?>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Editar Perfil</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?= htmlspecialchars($_SESSION['usuario']['email']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nueva contraseña</label>
                            <input type="password" class="form-control" placeholder="Dejar vacío para no cambiar">
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>