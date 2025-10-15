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

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom CSS -->
    <style>
        :root {
            /* Sistema de Colores Moderno */
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #8b5cf6;
            --secondary: #ec4899;
            --secondary-dark: #be185d;
            --accent: #f59e0b;
            --accent-dark: #d97706;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;

            /* Colores Neutros */
            --white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;

            /* Espaciado y Efectos */
            --border-radius: 12px;
            --border-radius-lg: 16px;
            --border-radius-xl: 20px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --shadow-2xl: 0 25px 50px -12px rgb(0 0 0 / 0.25);

            /* Transiciones */
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-fast: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Reset y Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, var(--gray-50) 0%, #fefefe 100%);
            color: var(--gray-800);
            line-height: 1.6;
            font-feature-settings: "cv02", "cv03", "cv04", "cv11";
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ==================== */
        /* NAVBAR MODERNO       */
        /* ==================== */
        .navbar-modern {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 0.5rem 0;
            transition: var(--transition);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-modern.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.12);
            padding: 0.4rem 0;
        }

        .navbar-brand-modern {
            font-weight: 800;
            font-size: 1.3rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: var(--transition);
            white-space: nowrap;
        }

        .navbar-brand-modern:hover {
            transform: scale(1.02);
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            box-shadow: var(--shadow-md);
        }

        .nav-link-modern {
            color: var(--gray-700) !important;
            font-weight: 500;
            padding: 0.5rem 0.9rem !important;
            margin: 0 0.1rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
            position: relative;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.95rem;
        }

        .nav-link-modern::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transition: var(--transition);
            transform: translateX(-50%);
        }

        .nav-link-modern:hover,
        .nav-link-modern.active {
            color: var(--primary) !important;
            background: linear-gradient(135deg,
                    rgba(99, 102, 241, 0.1),
                    rgba(236, 72, 153, 0.05));
            transform: translateY(-2px);
        }

        .nav-link-modern:hover::before,
        .nav-link-modern.active::before {
            width: 80%;
        }

        .navbar-toggler-modern {
            border: none;
            background: none;
            color: var(--gray-700);
            font-size: 1.25rem;
            padding: 0.5rem;
            transition: var(--transition);
        }

        .navbar-toggler-modern:hover {
            color: var(--primary);
            transform: scale(1.1);
        }

        .navbar-toggler-modern:focus {
            box-shadow: none;
        }

        /* Dropdown Mejorado */
        .dropdown-menu-modern {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-xl);
            padding: 0.75rem 0;
            margin-top: 0.5rem;
            min-width: 220px;
        }

        .dropdown-item-modern {
            color: var(--gray-700);
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .dropdown-item-modern:hover {
            background: linear-gradient(135deg,
                    rgba(99, 102, 241, 0.1),
                    rgba(236, 72, 153, 0.05));
            color: var(--primary);
            padding-left: 2rem;
        }

        .dropdown-divider-modern {
            margin: 0.5rem 0;
            border-color: var(--gray-200);
        }

        /* User Avatar y Notificaciones */
        .user-avatar-modern {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--primary);
            transition: var(--transition);
            cursor: pointer;
        }

        .user-avatar-modern:hover {
            transform: scale(1.1);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: linear-gradient(135deg, var(--danger), #dc2626);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border: 2px solid white;
            animation: pulse-notification 2s infinite;
        }

        @keyframes pulse-notification {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* Botones Modernos */
        .btn-modern {
            font-weight: 600;
            border-radius: var(--border-radius);
            padding: 0.75rem 1.5rem;
            transition: var(--transition);
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary-modern:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--secondary));
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-outline-modern {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline-modern:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Efectos de brillo */
        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-modern:hover::before {
            left: 100%;
        }

        /* ==================== */
        /* FOOTER MODERNO       */
        /* ==================== */
        .footer-modern {
            background: linear-gradient(135deg, var(--gray-900) 0%, var(--gray-800) 100%);
            color: var(--gray-300);
            position: relative;
            overflow: hidden;
        }

        .footer-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--primary), var(--secondary), transparent);
        }

        .footer-content {
            padding: 4rem 0 2rem;
            position: relative;
            z-index: 1;
        }

        .footer-section h5 {
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .footer-section h5::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 0;
            width: 40px;
            height: 2px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        .footer-links-modern {
            list-style: none;
            padding: 0;
        }

        .footer-links-modern li {
            margin-bottom: 0.75rem;
        }

        .footer-link {
            color: var(--gray-300);
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-link:hover {
            color: var(--primary);
            transform: translateX(5px);
        }

        .footer-link i {
            width: 16px;
            color: var(--gray-500);
            transition: var(--transition);
        }

        .footer-link:hover i {
            color: var(--primary);
        }

        .social-links-modern {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .social-link {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--gray-800), var(--gray-700));
            border: 1px solid var(--gray-600);
            color: var(--gray-300);
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .social-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            opacity: 0;
            transition: var(--transition);
        }

        .social-link:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .social-link:hover::before {
            opacity: 1;
        }

        .social-link:hover {
            color: white;
            border-color: transparent;
        }

        .social-link i {
            position: relative;
            z-index: 1;
            font-size: 1.1rem;
        }

        .footer-bottom {
            border-top: 1px solid var(--gray-700);
            padding: 2rem 0 1.5rem;
            text-align: center;
        }

        .footer-bottom-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 1rem;
        }

        .footer-bottom-link {
            color: var(--gray-400);
            text-decoration: none;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .footer-bottom-link:hover {
            color: var(--primary);
        }

        /* ==================== */
        /* MAIN CONTENT         */
        /* ==================== */
        .main-content {
            min-height: calc(100vh - 200px);
        }

        /* ==================== */
        /* LOGIN STYLES         */
        /* ==================== */
        .login-container {
            background: white;
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-2xl);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            margin: 2rem auto;
            border: 1px solid var(--gray-200);
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1563013546-72e6b2025c93?ixlib=rb-4.0.3') center/cover;
            opacity: 0.1;
        }

        .login-header h2 {
            position: relative;
            z-index: 1;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .login-form {
            padding: 2.5rem 2rem;
        }

        body.login-page {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        /* ==================== */
        /* RESPONSIVE DESIGN    */
        /* ==================== */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                margin: 1rem -1rem -1rem -1rem;
                padding: 1rem;
                border-radius: var(--border-radius-lg);
                margin-top: 1rem;
                box-shadow: var(--shadow-lg);
            }
        }

        @media (max-width: 768px) {
            .navbar-brand-modern {
                font-size: 1.5rem;
            }

            .brand-icon {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }

            .footer-content {
                padding: 3rem 0 1.5rem;
                text-align: center;
            }

            .footer-bottom-links {
                flex-direction: column;
                gap: 1rem;
            }

            .social-links-modern {
                justify-content: center;
            }

            .nav-link-modern {
                padding: 0.5rem 0.75rem !important;
                font-size: 0.95rem;
            }

            .navbar-brand-modern {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 576px) {
            .navbar-brand-modern {
                font-size: 1.1rem;
            }

            .brand-icon {
                width: 30px;
                height: 30px;
                font-size: 0.9rem;
            }

            .nav-link-modern {
                padding: 0.5rem !important;
                font-size: 0.9rem;
            }

            .btn-modern {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .footer-section h5 {
                font-size: 1.1rem;
            }

            .user-avatar-modern {
                width: 35px;
                height: 35px;
            }
        }

        /* Clases Bootstrap corregidas */
        .fw-bold {
            font-weight: 700 !important;
        }

        .text-gray-800 {
            color: var(--gray-800) !important;
        }

        .me-2 {
            margin-right: 0.5rem !important;
        }

        .ms-auto {
            margin-left: auto !important;
        }

        .me-1 {
            margin-right: 0.25rem !important;
        }

        .fw-600 {
            font-weight: 600;
        }

        /* Focus states mejorados */
        .btn-modern:focus,
        .nav-link-modern:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
        }
    </style>
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
                            <a class="nav-link-modern" href="<?php echo SITE_URL; ?>promociones.php">
                                <i class="fas fa-tags"></i>
                                <span>Promociones</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link-modern" href="<?php echo SITE_URL; ?>locales.php">
                                <i class="fas fa-store"></i>
                                <span>Locales</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link-modern" href="<?php echo SITE_URL; ?>contacto.php">
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
                                    <li><a class="dropdown-item-modern" href="admin/panel.php">
                                            <i class="fas fa-tachometer-alt"></i>Panel de Control
                                        </a></li>
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