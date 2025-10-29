<?php
// includes/header.php - SOLO el código del header

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determinar si el usuario está logueado
$isLoggedIn = isset($_SESSION['user_id']);
$userType = $isLoggedIn ? $_SESSION['user_type'] : '';
$userEmail = $isLoggedIn ? $_SESSION['user_email'] : '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Bandera</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="css/header.css">


</head>

<body class="<?php
$currentPage = basename($_SERVER['PHP_SELF']);
echo ($currentPage == 'login.php' || $currentPage == 'registro.php') ? 'login-page' : '';
?>">

    <?php if ($currentPage != 'login.php' && $currentPage != 'registro.php'): ?>
        <!-- Navigation Header - Solo mostrar si NO es login o registro -->
        <nav class="navbar navbar-expand-lg navbar-modern">
            <div class="container">
                <a class="navbar-brand-modern" href="<?php echo SITE_URL; ?>index.php">
                    <div class="brand-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <span>Bandera</span>
                </a>

                <button class="navbar-toggler-modern d-md-none" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav"> 
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link-modern <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>"
                                href="<?php echo SITE_URL; ?>index.php">
                                <i class="fas fa-home"></i>
                                <span>Inicio</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link-modern <?php echo $currentPage == 'promociones.php' ? 'active' : ''; ?>"
                                href="<?php echo SITE_URL; ?>promociones.php">
                                <i class="fas fa-tags"></i>
                                <span>Promociones</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link-modern <?php echo $currentPage == 'locales.php' ? 'active' : ''; ?>"
                                href="<?php echo SITE_URL; ?>locales.php">
                                <i class="fas fa-store"></i>
                                <span>Locales</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link-modern <?php echo $currentPage == 'contacto.php' ? 'active' : ''; ?>"
                                href="<?php echo SITE_URL; ?>contacto.php">
                                <i class="fas fa-envelope"></i>
                                <span>Contacto</span>
                            </a>
                        </li>

                        <?php if ($isLoggedIn): ?>
                            <!-- Menú para usuarios logueados -->
                            <li class="nav-item dropdown">
                                <a class="nav-link-modern dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Mi Cuenta</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-modern">
                                    <li><a class="dropdown-item-modern" href="<?php echo SITE_URL; ?>perfil.php">
                                            <i class="fas fa-user-edit"></i>Mi Perfil
                                        </a></li>
                                    <li>
                                        <hr class="dropdown-divider-modern">
                                    </li>
                                    <li><a class="dropdown-item-modern" href="<?php echo SITE_URL; ?>logout.php">
                                            <i class="fas fa-sign-out-alt"></i>Cerrar Sesión
                                        </a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>

                    <div class="d-flex align-items-center gap-3">
                        <?php if ($isLoggedIn): ?>
                            <!-- Usuario logueado -->
                            <div class="dropdown">


                            </div>
                        <?php else: ?>
                            <!-- Usuario no logueado -->
                            <a href="<?php echo SITE_URL; ?>login.php" class="btn btn-outline-modern">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Iniciar Sesión</span>
                            </a>
                            <a href="<?php echo SITE_URL; ?>registro.php" class="btn btn-primary-modern">
                                <i class="fas fa-user-plus"></i>
                                <span>Registrarse</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="main-content">
        <?php if ($currentPage != 'login.php' && $currentPage != 'registro.php' && strpos($_SERVER['REQUEST_URI'], '/panel.php') === false): ?>
            <div class="container">
            <?php endif; ?>