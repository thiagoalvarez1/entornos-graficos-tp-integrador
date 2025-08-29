<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$auth = new Auth();

// Si ya está logueado, redirigir al panel correspondiente
if ($auth->isLoggedIn()) {
    $auth->redirectUser();
}

$error = '';

// Procesar formulario de login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $result = $auth->login($email, $password);

    if ($result === true) {
        $auth->redirectUser();
    } else {
        $error = $result;
    }
}

$pageTitle = "Iniciar Sesión - PromoShopping";
require_once 'includes/header.php';
?>

<!-- SOLO el contenido ÚNICO del login -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-container">
                <div class="login-header">
                    <h3><i class="fas fa-store me-2"></i>PromoShopping</h3>
                    <p class="mb-0">Iniciar sesión en tu cuenta</p>
                </div>

                <div class="login-form">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                    </form>

                    <hr>

                    <div class="text-center">
                        <p>¿No tienes una cuenta? <a href="<?php echo SITE_URL; ?>registro.php">Regístrate aquí</a></p>
                        <p><a href="#">¿Olvidaste tu contraseña?</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>