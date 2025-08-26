<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Promociones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Shopping Promociones</a>
            <div class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['usuario'])): ?>
                    <span class="nav-link">Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre'] ?? $_SESSION['usuario']['email']) ?></span>
                    <a class="nav-link" href="logout.php">Cerrar sesión</a>
                    
                    <!-- Enlace al panel según rol -->
                    <?php if ($_SESSION['usuario']['rol'] === 'administrador'): ?>
                        <a class="nav-link" href="admin/panel.php">Panel Admin</a>
                    <?php elseif ($_SESSION['usuario']['rol'] === 'dueno'): ?>
                        <a class="nav-link" href="dueño/panel.php">Mi Local</a>
                    <?php else: ?>
                        <a class="nav-link" href="cliente/panel.php">Mi Cuenta</a>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <a class="nav-link" href="login.php">Login</a>
                    <a class="nav-link" href="register.php">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Mostrar mensajes de error/success -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <!-- Después de los alerts de error/success -->
<?php if (isset($_SESSION['usuario'])): ?>
<!-- Notificaciones -->
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
        🔔 <span class="badge bg-danger">3</span>
    </a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#">Nueva promoción aprobada</a></li>
        <li><a class="dropdown-item" href="#">Tu solicitud fue aceptada</a></li>
        <li><a class="dropdown-item" href="#">Novedad importante</a></li>
    </ul>
</li>
<?php endif; ?>