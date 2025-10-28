<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/database.php';

$auth = new Auth();

if ($auth->isLoggedIn()) {
    $auth->redirectUser();
}

$error = '';
$success = '';
$nombre = '';
$email = '';
$tipoUsuario = '';
$categoria = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $tipoUsuario = $_POST['tipo_usuario'] ?? USER_CLIENT;
    $categoria = $_POST['categoria'] ?? '';

    if (empty($nombre) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = "Todos los campos son obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El formato del email no es válido.";
    } elseif ($password !== $password_confirm) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $result = $auth->register($email, $password, $tipoUsuario, $nombre, $categoria);

        if ($result === true) {
            if (EMAIL_VERIFICATION_REQUIRED) {
                if (isset($_SESSION['debug_verification_url'])) {
                    $verificationUrl = $_SESSION['debug_verification_url'];
                    $userEmail = $_SESSION['debug_verification_email'] ?? $email;
                    $successHTML = true;
                    $verificationData = [
                        'url' => $verificationUrl,
                        'email' => $userEmail
                    ];
                    unset($_SESSION['debug_verification_url']);
                    unset($_SESSION['debug_verification_email']);
                    unset($_SESSION['debug_verification_token']);
                } else {
                    $success = "¡Registro exitoso! Te hemos enviado un email de verificación.";
                }
            } else {
                $success = "¡Registro exitoso! Ahora puedes iniciar sesión.";
            }

            $nombre = $email = $tipoUsuario = $categoria = '';
        } else {
            $error = $result;
        }
    }
}
$pageTitle = "Registro";
$currentPage = 'registro.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Bandera Shopping</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/registro.css">
</head>

<body>
    <div class="registro-wrapper">
        <div class="registro-card">
            <div class="registro-header">
                <div class="header-icon"><i class="fas fa-user-plus"></i></div>
                <h1 class="header-title">Bandera Shopping</h1>
                <p class="header-subtitle">Crear una nueva cuenta</p>
            </div>

            <div class="registro-form">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <span><?= htmlspecialchars($success) ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" novalidate>
                    <div class="form-group">
                        <label for="tipo_usuario" class="form-label">Tipo de Cuenta</label>
                        <select class="form-select" id="tipo_usuario" name="tipo_usuario" required>
                            <option value="<?= USER_CLIENT ?>" <?= ($tipoUsuario == USER_CLIENT || empty($tipoUsuario)) ? 'selected' : '' ?>>Cliente</option>
                            <option value="<?= USER_OWNER ?>" <?= $tipoUsuario == USER_OWNER ? 'selected' : '' ?>>Dueño de Local</option>
                        </select>
                        <div class="form-text">El dueño de local requiere aprobación.</div>
                    </div>

                    <div class="form-group">
                        <label for="nombre" class="form-label">Nombre o Razón Social</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required placeholder="Tu nombre completo">
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required placeholder="ejemplo@correo.com">
                    </div>

                    <!-- Campo contraseña -->
                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Mínimo 6 caracteres">
                        <i class="fas fa-eye toggle-password" data-target="password"></i>
                    </div>

                    <!-- Campo confirmar contraseña -->
                    <div class="form-group">
                        <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required placeholder="Repite tu contraseña">
                        <i class="fas fa-eye toggle-password" data-target="password_confirm"></i>
                    </div>

                    <button type="submit" class="btn-register">
                        <i class="fas fa-user-plus"></i> Registrarse
                    </button>
                </form>
            </div>

            <div class="text-center mb-4">
                <a href="index.php" class="btn-back-home">
                    <i class="fas fa-arrow-left"></i> Volver al Inicio
                </a>
            </div>

            <div class="auth-footer">
                ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', () => {
                const target = document.getElementById(icon.dataset.target);
                const isPassword = target.getAttribute('type') === 'password';
                target.setAttribute('type', isPassword ? 'text' : 'password');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>

</html>
