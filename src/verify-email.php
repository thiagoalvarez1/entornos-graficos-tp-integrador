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
        body {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 50%, #ec4899 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }

        .verification-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .icon-success {
            color: #10b981;
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .icon-error {
            color: #ef4444;
            font-size: 3rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="verification-card">
            <?php if ($messageType === 'success'): ?>
                <i class="fas fa-check-circle icon-success"></i>
                <h2>¡Email Verificado!</h2>
            <?php else: ?>
                <i class="fas fa-exclamation-circle icon-error"></i>
                <h2>Error de Verificación</h2>
            <?php endif; ?>

            <p class="mt-3"><?php echo $message; ?></p>

            <div class="mt-4">
                <?php if ($messageType === 'success'): ?>
                    <a href="login.php" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        Iniciar Sesión
                    </a>
                <?php else: ?>
                    <a href="register.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i>
                        Registrarse Nuevamente
                    </a>
                <?php endif; ?>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-home"></i>
                    Ir al Inicio
                </a>
            </div>
        </div>
    </div>
</body>

</html>