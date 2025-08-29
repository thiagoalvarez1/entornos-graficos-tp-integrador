<?php
// includes/header-panel.php - Header especial para paneles de Admin y Dueño

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determinar si el usuario está logueado
$isLoggedIn = isset($_SESSION['user_id']);
$userType = $isLoggedIn ? $_SESSION['user_type'] : '';
$userEmail = $isLoggedIn ? $_SESSION['user_email'] : '';

// Determinar el tipo de panel
$isAdminPanel = strpos($_SERVER['REQUEST_URI'], '/admin/') !== false;
$isDuenoPanel = strpos($_SERVER['REQUEST_URI'], '/dueno/') !== false;
$panelType = $isAdminPanel ? 'admin' : ($isDuenoPanel ? 'dueno' : '');
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

    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #e74c3c;
            --accent: #3498db;
            --light: #ecf0f1;
            --dark: #2c3e50;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Panel Navbar */

        /* Sidebar */
        .sidebar {
            background: linear-gradient(135deg, var(--primary) 0%, #34495e 100%);
            color: white;
            height: 100vh;
            position: fixed;
            padding-top: 80px;
            width: 250px;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar.collapsed {
            width: 60px;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.3rem;
        }

        .sidebar.collapsed .sidebar-brand-text {
            display: none;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 15px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(5px);
        }

        .sidebar .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            transition: margin-right 0.3s ease;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 30px;
            padding-top: 90px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        .sidebar.collapsed~.main-content {
            margin-left: 60px;
        }

        /* Cards */
        .card-dashboard {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .card-dashboard:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        /* Badges */
        .badge-pendiente {
            background-color: #f39c12;
            color: white;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-aprobado {
            background-color: #2ecc71;
            color: white;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-rechazado {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
            }

            .sidebar.show {
                left: 0;
            }

            .panel-navbar {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
                padding-top: 80px;
            }

            .sidebar.collapsed~.main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-4 p-3">
            <h5 class="text-white mb-1">PromoShopping</h5>
            <p class="text-muted small sidebar-brand-text">
                <?php echo $panelType === 'admin' ? 'Panel de Administración' : 'Panel de Dueño'; ?>
            </p>
        </div>
        <ul class="nav flex-column">
            <!-- Menú común para ambos -->
            <li class="nav-item">
                <a class="nav-link active" href="panel.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <?php if ($panelType === 'admin'): ?>
                <!-- Menú específico para ADMIN -->
                <li class="nav-item">
                    <a class="nav-link" href="gestion-locales.php">
                        <i class="fas fa-store"></i> Gestión de Locales
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="validar-duenos.php">
                        <i class="fas fa-user-check"></i> Validar Dueños
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gestion_promociones.php">
                        <i class="fas fa-tags"></i> Gestión de Promociones
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gestion_novedades.php">
                        <i class="fas fa-bullhorn"></i> Novedades
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reportes.php">
                        <i class="fas fa-chart-bar"></i> Reportes
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a class="nav-link" href="#">
                        <i class="fas fa-cog"></i> Configuración
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($panelType === 'dueno'): ?>
                <!-- Menú específico para DUEÑO -->
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-tags"></i>
                        <span>Mis Promociones</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Solicitudes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-chart-line"></i>
                        <span>Estadísticas</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Menú común para ambos -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-bullhorn"></i>
                    <span>Novedades</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <a class="nav-link" href="<?php echo SITE_URL; ?>index.php">
                    <i class="fas fa-home"></i>
                    <span>Volver al Sitio</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo SITE_URL; ?>logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->

    <!-- Panel Navbar -->