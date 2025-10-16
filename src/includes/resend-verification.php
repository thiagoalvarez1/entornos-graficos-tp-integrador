<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$auth = new Auth();
$pageTitle = "Reenviar Verificación";

if ($auth->isLoggedIn()) {
    $auth->redirectUser();
}

$message = '';
$messageType = '';

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    if ($auth->resendVerificationEmail($email)) {
        $message = 'Se ha enviado un nuevo email de verificación. Revisa tu bandeja de entrada.';
        $messageType = 'success';
    } else {
        $message = 'Error al reenviar el email de verificación. El email puede no existir o ya estar verificado.';
        $messageType = 'error';
    }
} else {
    $message = 'Email no proporcionado.';
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

        .resend-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="resend-card">
            <?php if ($messageType === 'success'): ?>
                <i class="fas fa-check-circle text-success" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <h2>Email Enviado</h2>
            <?php else: ?>
                <i class="fas fa-exclamation-circle text-danger" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <h2>Error</h2>
            <?php endif; ?>

            <p class="mt-3"><?php echo $message; ?></p>

            <div class="mt-4">
                <a href="login.php" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Volver al Login
                </a>
            </div>
        </div>
    </div>
</body>

</html>