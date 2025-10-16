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
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #ec4899;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --white: #ffffff;
            --border-radius: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 50%, var(--secondary) 100%);
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
            z-index: -1;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .registro-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
        }

        .registro-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .registro-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 1.5rem;
            text-align: center;
            color: white;
        }

        .header-icon {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 0.2rem;
        }

        .header-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
            margin: 0;
        }

        .registro-form {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .form-control,
        .form-select {
            padding: 9px 10px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-control::placeholder {
            color: var(--text-light);
        }

        .form-text {
            font-size: 0.8rem;
            color: var(--text-light);
            margin-top: 0.3rem;
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 1rem;
            padding: 0.9rem;
            font-size: 0.9rem;
            border: none;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert i {
            margin-right: 0.5rem;
        }

        .btn-close {
            width: auto;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }

        .btn-close:hover {
            opacity: 1;
        }

        .btn-register {
            width: 100%;
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .btn-register:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--secondary));
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        .btn-register:active {
            transform: translateY(0px);
        }

        .auth-footer {
            text-align: center;
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            font-size: 0.9rem;
            color: var(--text-dark);
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .auth-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .registro-card {
                border-radius: 16px;
            }

            .registro-header {
                padding: 1.2rem;
            }

            .header-title {
                font-size: 1.3rem;
            }

            .registro-form {
                padding: 1.2rem;
            }

            .form-group {
                margin-bottom: 0.9rem;
            }

            .form-label {
                font-size: 0.8rem;
            }

            .form-control,
            .form-select {
                padding: 8px 9px;
                font-size: 0.88rem;
            }
        }

        @media (max-width: 576px) {
            .registro-wrapper {
                max-width: 100%;
            }

            .registro-card {
                border-radius: 16px;
                box-shadow: 0 6px 30px rgba(0, 0, 0, 0.2);
            }

            .registro-header {
                padding: 1rem;
            }

            .header-icon {
                font-size: 1.4rem;
                margin-bottom: 0.3rem;
            }

            .header-title {
                font-size: 1.1rem;
            }

            .header-subtitle {
                font-size: 0.8rem;
            }

            .registro-form {
                padding: 1rem;
            }

            .form-group {
                margin-bottom: 0.8rem;
            }

            .form-control,
            .form-select {
                padding: 7px 8px;
                font-size: 0.85rem;
            }

            .form-label {
                font-size: 0.75rem;
                margin-bottom: 0.3rem;
            }

            .form-text {
                font-size: 0.75rem;
                margin-top: 0.2rem;
            }

            .btn-register {
                padding: 9px 14px;
                font-size: 0.85rem;
                margin-top: 0.3rem;
            }

            .alert {
                padding: 0.8rem;
                font-size: 0.8rem;
            }

            .auth-footer {
                padding: 0.8rem 1rem;
                font-size: 0.85rem;
            }
        }
    </style>
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
                        <input type="password" class="form-control" id="password" name="password" required
                            placeholder="Mínimo 6 caracteres">
                    </div>

                    <div class="form-group">
                        <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm"
                            required placeholder="Repite tu contraseña">
                    </div>

                    <button type="submit" class="btn-register">
                        <i class="fas fa-user-plus"></i>
                        Registrarse
                    </button>
                </form>
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