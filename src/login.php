<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/database.php';

$auth = new Auth();


if ($auth->isLoggedIn()) {
    $auth->redirectUser();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $result = $auth->login($email, $password);

    if ($result === true) {
        $auth->redirectUser();
        exit;
    } else {
        $error = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Bandera Shopping</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">

</head>


<body>
    <div class="login-wrapper">
        <div class="login-card">
            <!-- Header -->

            <div class="login-header">
                <div class="logo-icon">
                    <i class="fas fa-store"></i>
                </div>
                <h1 class="brand-title">Bandera Shopping</h1>
                <p class="brand-subtitle">Inicia sesión en tu cuenta</p>
            </div>
            <!-- Botón Volver al Inicio -->


            <!-- Formulario -->

            <div class="login-form">
                <?php if (!empty($error)): ?>
                    <div class="alert" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" novalidate>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" class="form-control" id="email" name="email" placeholder="tu@email.com"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••"
                            required>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Recuerda mi sesión
                        </label>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i>
                        Iniciar Sesión
                    </button>
                </form>
                <!-- Botón Volver al Inicio -->
                <div class="text-center mb-4">
                    <a href="index.php" class="btn-back-home">
                        <i class="fas fa-arrow-left"></i>
                        Volver al Inicio
                    </a>
                </div>


                <!-- Divider -->
                <div class="divider">
                    <span>o</span>
                </div>

                <!-- Links -->
                <div class="auth-links">
                    <p>¿No tienes cuenta?
                        <a href="<?php echo SITE_URL; ?>registro.php" class="auth-link">
                            <i class="fas fa-user-plus mb-1"></i>Regístrate aquí
                        </a>
                    </p>
                    <p>
                        <a href="#" class="auth-link">
                            <i class="fas fa-key mb-1"></i>¿Olvidaste tu contraseña?
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>