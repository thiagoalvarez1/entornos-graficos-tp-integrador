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
            if ($result === true) {
                if (EMAIL_VERIFICATION_REQUIRED) {
                    // MOSTRAR LINK SIEMPRE
                    if (isset($_SESSION['debug_verification_url'])) {
                        $verificationUrl = $_SESSION['debug_verification_url'];
                        $userEmail = $_SESSION['debug_verification_email'] ?? $email;

                        // Usar una variable separada para el mensaje con HTML
                        $successHTML = true;
                        $verificationData = [
                            'url' => $verificationUrl,
                            'email' => $userEmail
                        ];

                        $success = ""; // Mensaje simple vacío

                        // Limpiar la sesión inmediatamente
                        unset($_SESSION['debug_verification_url']);
                        unset($_SESSION['debug_verification_email']);
                        unset($_SESSION['debug_verification_token']);
                    } else {
                        $success = "¡Registro exitoso! Te hemos enviado un email de verificación. Revisa tu bandeja de entrada.";
                    }
                } else {
                    $success = "¡Registro exitoso! Ahora puedes iniciar sesión.";
                }

                if ($tipoUsuario === USER_OWNER && !isset($successHTML)) {
                    $success = "Registro de Dueño de Local exitoso. " . (EMAIL_VERIFICATION_REQUIRED ?
                        "Tu cuenta está pendiente de verificación y aprobación." :
                        "Tu cuenta está en estado pendiente de aprobación.");
                }

                $nombre = $email = $tipoUsuario = $categoria = '';
            } else {
                $error = $result;
            }
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/registro.css">
</head>


<body>
    <div class="registro-wrapper">
        <div class="registro-card">
            <!-- Header -->
            <div class="registro-header">
                <div class="header-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1 class="header-title">Bandera Shopping</h1>
                <p class="header-subtitle">Crear una nueva cuenta</p>
            </div>

            <!-- Formulario -->
            <div class="registro-form">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success) && !str_contains($success, '<div')): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo htmlspecialchars($success); ?></span>
                    </div>
                <?php elseif (!empty($success)): ?>
                    <?php echo $success; ?>
                <?php endif; ?>
                <?php if (isset($successHTML) && isset($verificationData)): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <strong>¡Registro exitoso!</strong> Tu cuenta ha sido creada correctamente.
                    </div>

                    <div class='alert alert-info' style='border-left: 4px solid #17a2b8;'>
                        <h5><i class='fas fa-envelope'></i> Verificación Requerida</h5>
                        <p><strong>Email registrado:</strong> <?php echo htmlspecialchars($verificationData['email']); ?>
                        </p>
                        <p><strong>Para activar tu cuenta, haz clic en este botón:</strong></p>
                        <div class='mt-2 p-3 bg-light rounded text-center'>
                            <a href='<?php echo $verificationData['url']; ?>' class='btn btn-success btn-lg fw-bold'
                                style='word-break: break-all; white-space: normal;'>
                                <i class='fas fa-check-circle'></i> VERIFICAR MI EMAIL AHORA
                            </a>
                        </div>
                        <p class='mt-3 mb-1'><strong>O copia esta URL en tu navegador:</strong></p>
                        <div class='p-2 bg-white border rounded small'
                            style='word-break: break-all; font-family: monospace;'>
                            <?php echo $verificationData['url']; ?>
                        </div>
                        <p class='mt-2 small text-muted'>
                            <i class='fas fa-clock'></i> Este link expirará en 24 horas.
                        </p>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" novalidate>
                    <div class="form-group">
                        <label for="tipo_usuario" class="form-label">Tipo de Cuenta</label>
                        <select class="form-select" id="tipo_usuario" name="tipo_usuario" required
                            onchange="toggleCategoryField(this.value)">
                            <option value="<?= USER_CLIENT ?>" <?= ($tipoUsuario == USER_CLIENT || empty($tipoUsuario)) ? 'selected' : '' ?>>
                                <?= ucfirst(USER_CLIENT) ?>
                            </option>
                            <option value="<?= USER_OWNER ?>" <?= $tipoUsuario == USER_OWNER ? 'selected' : '' ?>>
                                <?= ucfirst(USER_OWNER) ?>
                            </option>
                        </select>
                        <div class="form-text">
                            El dueño de local requiere aprobación.
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nombre" class="form-label">Nombre o Razón Social</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                            value="<?= htmlspecialchars($nombre) ?>" required placeholder="Tu nombre completo">
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= htmlspecialchars($email) ?>" required placeholder="ejemplo@correo.com">
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="password-input-container">
                            <input type="password" class="form-control password-input" id="password" name="password"
                                required placeholder="Mínimo 6 caracteres">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
                        <div class="password-input-container">
                            <input type="password" class="form-control password-input" id="password_confirm"
                                name="password_confirm" required placeholder="Repite tu contraseña">
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirm')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-register">
                        <i class="fas fa-user-plus"></i>
                        Registrarse
                    </button>
                </form>
            </div>
            <!-- Botón Volver al Inicio -->
            <div class="text-center mb-4">
                <a href="index.php" class="btn-back-home">
                    <i class="fas fa-arrow-left"></i>
                    Volver al Inicio
                </a>
            </div>

            <!-- Footer -->
            <div class="auth-footer">
                ¿Ya tienes cuenta?
                <a href="<?php echo SITE_URL; ?>login.php">
                    Inicia sesión aquí
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleCategoryField(userType) {
            const categoryField = document.getElementById('category-field');
            const USER_CLIENT_JS = '<?= USER_CLIENT ?>';

            if (userType === USER_CLIENT_JS) {
                categoryField.style.display = 'block';
            } else {
                categoryField.style.display = 'none';
                document.getElementById('categoria').value = '';
            }
        }

        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = passwordField.parentNode.querySelector('.password-toggle i');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const tipoUsuarioSelect = document.getElementById('tipo_usuario');
            toggleCategoryField(tipoUsuarioSelect.value);
        });
    </script>
    <script>
        // Auto-scroll al mensaje de verificación
        document.addEventListener('DOMContentLoaded', function () {
            const verificationSection = document.querySelector('.alert-info');
            if (verificationSection) {
                verificationSection.scrollIntoView({ behavior: 'smooth' });

                // Resaltar el botón
                const verifyButton = verificationSection.querySelector('.btn-success');
                if (verifyButton) {
                    verifyButton.focus();
                }
            }
        });
    </script>
</body>

</html>