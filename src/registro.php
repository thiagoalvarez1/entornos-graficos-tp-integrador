<?php
require_once 'includes/auth.php';

$auth = new Auth();

// Si ya está logueado, redirigir al panel correspondiente
if ($auth->isLoggedIn()) {
    $auth->redirectUser();
}

$error = '';
$success = '';

// Procesar formulario de registro
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $user_type = trim($_POST['user_type']);

    // Validaciones
    if ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden";
    } else {
        // Establecer categoría por defecto para clientes
        $category = ($user_type == USER_CLIENT) ? CATEGORY_INITIAL : '';

        $result = $auth->register($email, $password, $user_type, '', $category);

        if ($result === true) {
            $success = "Usuario registrado correctamente. Ahora puedes iniciar sesión.";
        } else {
            $error = $result;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - PromoShopping</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        :root {
            --primary-purple: #6366f1;
            --secondary-purple: #8b5cf6;
            --accent-blue: #0ea5e9;
            --accent-green: #10b981;
            --gradient-start: #667eea;
            --gradient-middle: #764ba2;
            --gradient-end: #f093fb;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
            --success-gradient: linear-gradient(135deg, #10b981, #059669);
            --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(-45deg, var(--gradient-start), var(--gradient-middle), var(--secondary-purple), var(--gradient-end));
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            position: relative;
            overflow-x: hidden;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Efectos de fondo animados */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            33% {
                transform: translateY(-30px) rotate(0.5deg);
            }

            66% {
                transform: translateY(15px) rotate(-0.5deg);
            }
        }

        .register-container {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .register-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            position: relative;
        }

        .register-header {
            text-align: center;
            padding: 2.5rem 2.5rem 1rem;
            position: relative;
        }

        .logo-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            border-radius: 20px;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
            animation: logoFloat 3s ease-in-out infinite;
        }

        @keyframes logoFloat {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-5px) rotate(2deg);
            }
        }

        .logo-container i {
            color: white;
            font-size: 1.75rem;
        }

        .brand-name {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .brand-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-weight: 400;
            margin: 0;
        }

        .register-form {
            padding: 1rem 2.5rem 2.5rem;
        }

        .form-floating {
            margin-bottom: 1.25rem;
            position: relative;
        }

        .form-control,
        .form-select {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            padding: 1rem 1rem 1rem 3rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, 0.95);
            border-color: var(--primary-purple);
            box-shadow:
                0 0 0 3px rgba(99, 102, 241, 0.1),
                0 4px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: var(--text-light);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            z-index: 5;
            transition: color 0.3s ease;
        }

        .form-floating:focus-within .input-icon {
            color: var(--primary-purple);
        }

        .form-floating label {
            color: var(--text-light);
            font-weight: 500;
            padding-left: 3rem;
        }

        .form-text {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
            margin-top: 0.5rem;
            padding-left: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-text i {
            color: var(--accent-blue);
        }

        .btn-register {
            width: 100%;
            padding: 1rem 2rem;
            border: none;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            color: white;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(99, 102, 241, 0.4);
        }

        .btn-register:active {
            transform: translateY(0px);
        }

        .divider {
            text-align: center;
            margin: 2rem 0 1.5rem;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
        }

        .divider span {
            background: var(--glass-bg);
            padding: 0 1rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .auth-links {
            text-align: center;
        }

        .auth-links p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .auth-links a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
        }

        .auth-links a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: white;
            transition: width 0.3s ease;
        }

        .auth-links a:hover::after {
            width: 100%;
        }

        .alert {
            border-radius: 12px;
            margin-bottom: 1.5rem;
            padding: 1rem 1.25rem;
            backdrop-filter: blur(10px);
            border: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            color: white;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            color: white;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .alert i {
            font-size: 1.1rem;
        }

        /* Estilos especiales para el selector de tipo de usuario */
        .user-type-card {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 1rem;
            margin: 0.5rem 0;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-type-card:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-2px);
        }

        .user-type-card.active {
            background: rgba(99, 102, 241, 0.2);
            border-color: var(--primary-purple);
        }

        .user-type-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 1.2rem;
        }

        .user-type-info h6 {
            color: white;
            margin: 0;
            font-weight: 600;
        }

        .user-type-info p {
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
            font-size: 0.85rem;
        }

        /* Indicador de fuerza de contraseña */
        .password-strength {
            height: 3px;
            border-radius: 2px;
            margin-top: 0.5rem;
            transition: all 0.3s ease;
        }

        .password-strength.weak {
            background: #ef4444;
            width: 33%;
        }

        .password-strength.medium {
            background: #f59e0b;
            width: 66%;
        }

        .password-strength.strong {
            background: #10b981;
            width: 100%;
        }

        @media (max-width: 768px) {
            .register-card {
                margin: 1rem;
                max-width: none;
                border-radius: 20px;
            }

            .register-header {
                padding: 2rem 1.5rem 1rem;
            }

            .register-form {
                padding: 1rem 1.5rem 2rem;
            }

            .brand-name {
                font-size: 1.6rem;
            }
        }

        /* Partículas animadas */
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            pointer-events: none;
            animation: particleFloat 8s linear infinite;
        }

        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Partículas animadas -->
    <div class="particle" style="left: 15%; width: 3px; height: 3px; animation-delay: 0s;"></div>
    <div class="particle" style="left: 25%; width: 2px; height: 2px; animation-delay: 2s;"></div>
    <div class="particle" style="left: 35%; width: 4px; height: 4px; animation-delay: 4s;"></div>
    <div class="particle" style="left: 45%; width: 2px; height: 2px; animation-delay: 6s;"></div>
    <div class="particle" style="left: 55%; width: 3px; height: 3px; animation-delay: 8s;"></div>
    <div class="particle" style="left: 65%; width: 5px; height: 5px; animation-delay: 10s;"></div>
    <div class="particle" style="left: 75%; width: 2px; height: 2px; animation-delay: 12s;"></div>
    <div class="particle" style="left: 85%; width: 4px; height: 4px; animation-delay: 14s;"></div>

    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="logo-container">
                    <i class="fas fa-store"></i>
                </div>
                <h1 class="brand-name">PromoShopping</h1>
                <p class="brand-subtitle">Crear una nueva cuenta</p>
            </div>

            <div class="register-form">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo $success; ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-floating">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" class="form-control" id="email" name="email" placeholder="tu@email.com"
                            required>
                        <label for="email">Correo electrónico</label>
                    </div>

                    <div class="form-floating">
                        <i class="fas fa-users input-icon"></i>
                        <select class="form-select" id="user_type" name="user_type" required>
                            <option value="">Seleccionar tipo de usuario</option>
                            <option value="<?php echo USER_CLIENT; ?>">Cliente</option>
                            <option value="<?php echo USER_OWNER; ?>">Dueño de local</option>
                        </select>
                        <label for="user_type">Tipo de usuario</label>
                    </div>

                    <div class="form-text">
                        <i class="fas fa-info-circle"></i>
                        Los dueños de locales deben ser validados por un administrador
                    </div>

                    <div class="form-floating">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Contraseña" required>
                        <label for="password">Contraseña</label>
                        <div class="password-strength" id="passwordStrength"></div>
                    </div>

                    <div class="form-floating">
                        <i class="fas fa-shield-alt input-icon"></i>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                            placeholder="Confirmar contraseña" required>
                        <label for="confirm_password">Confirmar contraseña</label>
                    </div>

                    <button type="submit" class="btn-register">
                        <i class="fas fa-user-plus me-2"></i>
                        Crear Cuenta
                    </button>
                </form>

                <div class="divider">
                    <span>o</span>
                </div>

                <div class="auth-links">
                    <p>¿Ya tienes una cuenta?
                        <a href="<?php echo SITE_URL; ?>login.php">
                            <i class="fas fa-sign-in-alt me-1"></i>Inicia sesión aquí
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animación para los campos de entrada
        document.querySelectorAll('.form-control, .form-select').forEach(input => {
            input.addEventListener('focus', function () {
                this.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', function () {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });

        // Indicador de fuerza de contraseña
        const passwordInput = document.getElementById('password');
        const strengthIndicator = document.getElementById('passwordStrength');

        passwordInput.addEventListener('input', function () {
            const password = this.value;
            let strength = 0;

            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            strengthIndicator.className = 'password-strength';
            if (strength >= 3) {
                strengthIndicator.classList.add('strong');
            } else if (strength >= 2) {
                strengthIndicator.classList.add('medium');
            } else if (strength >= 1) {
                strengthIndicator.classList.add('weak');
            }
        });

        // Validación de confirmación de contraseña en tiempo real
        const confirmPasswordInput = document.getElementById('confirm_password');

        confirmPasswordInput.addEventListener('input', function () {
            const password = passwordInput.value;
            const confirmPassword = this.value;

            if (confirmPassword && password !== confirmPassword) {
                this.style.borderColor = '#ef4444';
                this.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
            } else {
                this.style.borderColor = '';
                this.style.boxShadow = '';
            }
        });

        // Crear más partículas dinámicamente
        function createParticle() {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.width = particle.style.height = (Math.random() * 5 + 1) + 'px';
            particle.style.animationDelay = Math.random() * 8 + 's';
            document.body.appendChild(particle);

            setTimeout(() => {
                particle.remove();
            }, 8000);
        }

        // Generar partículas cada 2.5 segundos
        setInterval(createParticle, 2500);

        // Animación especial para mensajes de éxito
        if (document.querySelector('.alert-success')) {
            setTimeout(() => {
                const successAlert = document.querySelector('.alert-success');
                if (successAlert) {
                    successAlert.style.animation = 'pulse 1s ease-in-out 3';
                }
            }, 500);
        }
    </script>

    <style>
        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.02);
            }
        }
    </style>
</body>

</html>