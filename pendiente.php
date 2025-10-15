<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$auth = new Auth();

// Si el usuario no está logueado, redirigir al login
if (!$auth->isLoggedIn()) {
    header('Location: ' . SITE_URL . 'login.php');
    exit;
}

// Si el usuario está activo, redirigir según su tipo
if ($_SESSION['user_status'] === 'activo') {
    $auth->redirectUser();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Pendiente - Bandera</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }

        .pending-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
        }

        .pending-icon {
            font-size: 4rem;
            color: #f59e0b;
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>
    <div class="pending-container">
        <div class="pending-icon">
            <i class="fas fa-clock"></i>
        </div>
        <h2 class="mb-3">Cuenta Pendiente de Aprobación</h2>
        <p class="text-muted mb-4">
            Tu cuenta como dueño de local está pendiente de aprobación por los administradores del sistema.
            Recibirás un correo electrónico una vez que tu cuenta sea activada.
        </p>
        <div class="d-grid gap-2">
            <a href="logout.php" class="btn btn-primary">
                <i class="fas fa-sign-out-alt me-2"></i>
                Cerrar Sesión
            </a>
        </div>
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Pendiente - Shopping Promos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Cuenta Pendiente de Aprobación</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-clock me-2"></i>
                            Su cuenta está pendiente de aprobación por los administradores del sistema.
                        </div>
                        <p>Una vez que su cuenta sea aprobada, recibirá un correo electrónico de confirmación y podrá
                            acceder a todas las funcionalidades del sistema.</p>
                        <div class="text-center">
                            <a href="logout.php" class="btn btn-primary">Cerrar Sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>