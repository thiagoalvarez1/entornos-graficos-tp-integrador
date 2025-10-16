<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$auth = new Auth();
$pageTitle = "Verificar Email";

if ($auth->isLoggedIn()) {
    $auth->redirectUser();
}

$message = '';
$messageType = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $result = $auth->verifyEmail($token);

    if ($result['success']) {
        $message = $result['message'];
        $messageType = 'success';
    } else {
        $message = $result['message'];
        $messageType = 'error';
    }
} else {
    $message = 'Token no proporcionado.';
    $messageType = 'error';
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Bandera Shopping</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #ec4899;
            --success: #10b981;
            --danger: #ef4444;
            --white: #ffffff;
        }

        body {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 50%, var(--secondary) 100%);
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .verification-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            width: 100%;
        }

        .verification-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 500px;
            width: 100%;
            text-align: center;
            position: relative;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .verification-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            display: block;
        }

        .icon-success {
            color: var(--success);
            animation: bounceIn 0.8s ease-out;
        }

        .icon-error {
            color: var(--danger);
            animation: shake 0.8s ease-out;
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes shake {
            0%, 100% {
                transform: translateX(0);
            }
            10%, 30%, 50%, 70%, 90% {
                transform: translateX(-5px);
            }
            20%, 40%, 60%, 80% {
                transform: translateX(5px);
            }
        }

        .verification-card h2 {
            font-size: 2rem;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .verification-message {
            font-size: 1.1rem;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .btn-verification {
            padding: 0.875rem 1.75rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 2px solid transparent;
            font-size: 1rem;
            margin: 0.25rem;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--secondary));
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
            color: var(--white);
        }

        .btn-secondary-custom {
            background: rgba(107, 114, 128, 0.1);
            color: #6b7280;
            border: 2px solid rgba(107, 114, 128, 0.2);
        }

        .btn-secondary-custom:hover {
            background: rgba(107, 114, 128, 0.2);
            border-color: rgba(107, 114, 128, 0.3);
            transform: translateY(-2px);
            color: #374151;
        }

        .verification-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .verification-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .verification-card h2 {
                font-size: 1.5rem;
            }

            .verification-message {
                font-size: 1rem;
            }

            .verification-actions {
                flex-direction: column;
            }

            .btn-verification {
                width: 100%;
                justify-content: center;
            }
        }

        /* Efectos de partículas de fondo */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            33% {
                transform: translateY(-20px) rotate(120deg);
            }
            66% {
                transform: translateY(-10px) rotate(240deg);
            }
        }
    </style>
</head>

<body>
    <!-- Partículas de fondo -->
    <div class="particles" id="particles"></div>

    <div class="verification-container">
        <div class="verification-card">
            <?php if ($messageType === 'success'): ?>
                    <i class="fas fa-check-circle verification-icon icon-success"></i>
                    <h2>¡Email Verificado Exitosamente!</h2>
            <?php else: ?>
                    <i class="fas fa-exclamation-circle verification-icon icon-error"></i>
                    <h2>Error en la Verificación</h2>
            <?php endif; ?>

            <p class="verification-message"><?php echo $message; ?></p>

            <div class="verification-actions">
                <?php if ($messageType === 'success'): ?>
                        <a href="login.php" class="btn-verification btn-primary-custom">
                            <i class="fas fa-sign-in-alt"></i>
                            Iniciar Sesión
                        </a>
                <?php else: ?>
                        <a href="register.php" class="btn-verification btn-primary-custom">
                            <i class="fas fa-user-plus"></i>
                            Registrarse Nuevamente
                        </a>
                <?php endif; ?>
                <a href="index.php" class="btn-verification btn-secondary-custom">
                    <i class="fas fa-home"></i>
                    Volver al Inicio
                </a>
            </div>
        </div>
    </div>

    <script>
        // Crear partículas de fondo
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 15;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                // Tamaño aleatorio entre 2px y 6px
                const size = Math.random() * 4 + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Posición aleatoria
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;
                
                // Retraso de animación aleatorio
                particle.style.animationDelay = `${Math.random() * 6}s`;
                
                particlesContainer.appendChild(particle);
            }
        }

        // Inicializar partículas cuando la página cargue
        document.addEventListener('DOMContentLoaded', createParticles);
    </script>
</body>

</html>