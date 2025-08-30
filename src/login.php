<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/config.php';
require_once 'includes/database.php';

$auth = new Auth();

// Si ya está logueado, redirigir según su tipo
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
    } else {
        $error = $result;
    }
}
?>

<!-- Tu formulario de login aquí -->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - PromoShopping</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        :root {
            --primary-purple: #6366f1;
            --secondary-purple: #8b5cf6;
            --accent-blue: #0ea5e9;
            --gradient-start: #667eea;
            --gradient-middle: #764ba2;
            --gradient-end: #f093fb;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
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

        .login-container {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
            position: relative;
        }

        .login-header {
            text-align: center;
            padding: 3rem 2.5rem 1rem;
            position: relative;
        }

        .logo-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            border-radius: 24px;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
            animation: logoFloat 3s ease-in-out infinite;
        }

        @keyframes logoFloat {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .logo-container i {
            color: white;
            font-size: 2rem;
        }

        .brand-name {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .brand-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            font-weight: 400;
            margin: 0;
        }

        .login-form {
            padding: 1rem 2.5rem 2.5rem;
        }

        .form-floating {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            padding: 1rem 1rem 1rem 3rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .form-control:focus {
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

        .form-check {
            margin: 1.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-check-input {
            width: 1.2rem;
            height: 1.2rem;
            border-radius: 6px;
            border: 2px solid rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.2);
        }

        .form-check-input:checked {
            background: var(--primary-purple);
            border-color: var(--primary-purple);
        }

        .form-check-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            cursor: pointer;
        }

        .btn-login {
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

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(99, 102, 241, 0.4);
        }

        .btn-login:active {
            transform: translateY(0px);
        }

        .divider {
            text-align: center;
            margin: 2rem 0;
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
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 12px;
            color: white;
            margin-bottom: 1.5rem;
            padding: 1rem;
            backdrop-filter: blur(10px);
        }

        @media (max-width: 768px) {
            .login-card {
                margin: 1rem;
                max-width: none;
                border-radius: 20px;
            }

            .login-header {
                padding: 2rem 1.5rem 1rem;
            }

            .login-form {
                padding: 1rem 1.5rem 2rem;
            }

            .brand-name {
                font-size: 1.75rem;
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
    <div class="particle" style="left: 10%; width: 4px; height: 4px; animation-delay: 0s;"></div>
    <div class="particle" style="left: 20%; width: 2px; height: 2px; animation-delay: 2s;"></div>
    <div class="particle" style="left: 30%; width: 3px; height: 3px; animation-delay: 4s;"></div>
    <div class="particle" style="left: 40%; width: 5px; height: 5px; animation-delay: 6s;"></div>
    <div class="particle" style="left: 50%; width: 2px; height: 2px; animation-delay: 8s;"></div>
    <div class="particle" style="left: 60%; width: 4px; height: 4px; animation-delay: 10s;"></div>
    <div class="particle" style="left: 70%; width: 3px; height: 3px; animation-delay: 12s;"></div>
    <div class="particle" style="left: 80%; width: 2px; height: 2px; animation-delay: 14s;"></div>
    <div class="particle" style="left: 90%; width: 4px; height: 4px; animation-delay: 16s;"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-container">
                    <i class="fas fa-store"></i>
                </div>
                <h1 class="brand-name">PromoShopping</h1>
                <p class="brand-subtitle">Iniciar sesión en tu cuenta</p>
            </div>

            <div class="login-form">
                <?php if (!empty($error)): ?>
                    <div class="alert" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo $error; ?>
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
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Contraseña" required>
                        <label for="password">Contraseña</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">
                            Recordar mi sesión
                        </label>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Iniciar Sesión
                    </button>
                </form>

                <div class="divider">
                    <span>o</span>
                </div>

                <div class="auth-links">
                    <p>¿No tienes una cuenta?
                        <a href="<?php echo SITE_URL; ?>registro.php">
                            <i class="fas fa-user-plus me-1"></i>Regístrate aquí
                        </a>
                    </p>
                    <p>
                        <a href="#">
                            <i class="fas fa-key me-1"></i>¿Olvidaste tu contraseña?
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animación adicional para los campos de entrada
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function () {
                this.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', function () {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
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

        // Generar partículas cada 2 segundos
        setInterval(createParticle, 2000);
    </script>
</body>

</html>