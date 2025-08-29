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
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>PromoShopping</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #e74c3c;
            --accent: #3498db;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --success: #27ae60;
            --warning: #f39c12;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        /* Header Styles */
        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, #34495e 100%);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 1rem;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .navbar-brand i {
            color: var(--secondary);
            margin-right: 0.5rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white !important;
            transform: translateY(-2px);
        }

        .navbar-toggler {
            border: none;
            color: white !important;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
        }

        .dropdown-item:hover {
            background-color: var(--light);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .badge-notification {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--secondary);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Main Content */
        .main-content {
            min-height: calc(100vh - 160px);
            padding: 2rem 0;
        }

        /* Footer Styles */
        .footer {
            background: linear-gradient(135deg, var(--dark) 0%, #2c3e50 100%);
            color: white;
            padding: 3rem 0 1.5rem;
            margin-top: auto;
        }

        .footer h5 {
            color: var(--secondary);
            font-weight: 600;
            margin-bottom: 1.2rem;
            font-size: 1.1rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.6rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--secondary);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--secondary);
            transform: translateY(-3px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
            margin-top: 2rem;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-nav {
                padding: 1rem 0;
            }

            .nav-link {
                margin: 0.2rem 0;
            }

            .footer {
                text-align: center;
            }

            .social-links {
                justify-content: center;
            }
        }

        /* Utility Classes */
        .btn-primary {
            background: linear-gradient(135deg, var(--secondary) 0%, #c0392b 100%);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.5rem 1.2rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-light:hover {
            background-color: white;
            color: var(--primary) !important;
            border-color: white;
        }

        /* ==================== */
        /* ESTILOS PARA LOGIN   */
        /* ==================== */
        .login-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            margin: 2rem auto;
        }

        .login-header {
            background: linear-gradient(rgba(44, 62, 80, 0.9), rgba(44, 62, 80, 0.9)), url('https://images.unsplash.com/photo-1563013546-72e6b2025c93?ixlib=rb-4.0.3') center/cover;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .login-form {
            padding: 30px;
        }

        /* Asegurar que el body del login se vea bien */
        body.login-page {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
        }

        /* ==================== */
        /* ESTILOS PARA PANEL ADMIN */
        /* ==================== */
        .sidebar {
            background-color: var(--primary);
            color: white;
            height: 100vh;
            position: fixed;
            padding-top: 60px;
            width: 250px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 4px 0;
            border-radius: 4px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .admin-content {
            margin-left: 250px;
            padding: 20px;
        }

        .card-dashboard {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .card-dashboard:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .stats-icon {
            font-size: 2.5rem;
            opacity: 0.2;
            position: absolute;
            right: 20px;
            top: 20px;
        }

        .badge-pendiente {
            background-color: #f39c12;
            color: white;
        }

        .badge-aprobado {
            background-color: #2ecc71;
            color: white;
        }

        .badge-rechazado {
            background-color: #e74c3c;
            color: white;
        }
    </style>
</head>

<body class="<?php
echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'login-page' : '';
echo basename($_SERVER['PHP_SELF']) == 'registro.php' ? 'login-page' : '';
?>">
    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo SITE_URL; ?>index.php">
                <i class="fas fa-store"></i>PromoShopping
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"
                            href="<?php echo SITE_URL; ?>index.php">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>index.php#promociones">
                            <i class="fas fa-tags"></i> Promociones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>index.php#locales">
                            <i class="fas fa-store"></i> Locales
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contacto.php' ? 'active' : ''; ?>"
                            href="<?php echo SITE_URL; ?>contacto.php">
                            <i class="fas fa-envelope"></i> Contacto
                        </a>
                    </li>

                    <?php if ($isLoggedIn): ?>
                        <!-- Menú para usuarios logueados -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> Mi Cuenta
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo SITE_URL . $userType . '/panel.php'; ?>">
                                        <i class="fas fa-tachometer-alt me-2"></i>Panel de Control
                                    </a></li>
                                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>perfil.php">
                                        <i class="fas fa-user me-2"></i>Mi Perfil
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>logout.php">
                                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                    </a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>

                <div class="d-flex align-items-center">
                    <?php if ($isLoggedIn): ?>
                        <!-- Usuario logueado -->
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle text-white text-decoration-none d-flex align-items-center"
                                id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($userEmail); ?>&background=random&size=32"
                                    class="user-avatar me-2">
                                <span class="d-none d-md-inline"><?php echo explode('@', $userEmail)[0]; ?></span>
                                <span class="badge-notification">3</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><span class="dropdown-item-text">
                                        <small>Conectado como</small><br>
                                        <strong><?php echo $userEmail; ?></strong>
                                    </span></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?php echo SITE_URL . $userType . '/panel.php'; ?>">
                                        <i class="fas fa-tachometer-alt me-2"></i>Panel
                                    </a></li>
                                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>perfil.php">
                                        <i class="fas fa-cog me-2"></i>Configuración
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>logout.php">
                                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                    </a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <!-- Usuario no logueado -->
                        <a href="<?php echo SITE_URL; ?>login.php" class="btn btn-outline-light me-2">
                            <i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
                        </a>
                        <a href="<?php echo SITE_URL; ?>registro.php" class="btn btn-primary">
                            <i class="fas fa-user-plus me-1"></i> Registrarse
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">