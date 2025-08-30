<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/config.php';

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