<?php
// includes/header-panel.php - Header optimizado para paneles

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determinar usuario y panel
$isLoggedIn = isset($_SESSION['user_id']);
$userType = $isLoggedIn ? $_SESSION['user_type'] : '';
$userEmail = $isLoggedIn ? $_SESSION['user_email'] : '';

$currentUri = $_SERVER['REQUEST_URI'];
$isAdminPanel = strpos($currentUri, '/admin/') !== false;
$isDuenoPanel = strpos($currentUri, '/dueno/') !== false;
$panelType = $isAdminPanel ? 'admin' : ($isDuenoPanel ? 'dueno' : '');

// Configuración del panel
$panelConfig = [
    'admin' => [
        'title' => 'Panel de Administración',
        'menu' => [
            ['icon' => 'fa-store', 'text' => 'Gestión de Locales', 'link' => 'gestion-locales.php'],
            ['icon' => 'fa-user-check', 'text' => 'Validar Dueños', 'link' => 'validar-duenos.php'],
            ['icon' => 'fa-tags', 'text' => 'Gestión de Promociones', 'link' => 'gestion_promociones.php'],
            ['icon' => 'fa-bullhorn', 'text' => 'Novedades', 'link' => 'gestion_novedades.php'],
            ['icon' => 'fa-chart-bar', 'text' => 'Reportes', 'link' => 'reportes.php']
        ]
    ],
    'dueno' => [
        'title' => 'Panel de Dueño',
        'menu' => [
            ['icon' => 'fa-tags', 'text' => 'Mis Promociones', 'link' => 'mis_promociones.php'],
            ['icon' => 'fa-clipboard-check', 'text' => 'Solicitudes', 'link' => 'mis_solicitudes.php'],
            ['icon' => 'fa-chart-line', 'text' => 'Estadísticas', 'link' => 'estadisticas.php']
        ]
    ]
];

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? "$pageTitle - " : '' ?>PromoShopping</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2c3e50;
            --primary-light: #34495e;
            --secondary: #e74c3c;
            --accent: #3498db;
            --text-light: #ecf0f1;
            --text-muted: rgba(255, 255, 255, 0.7);
            --hover-bg: rgba(255, 255, 255, 0.15);
            --border-radius: 8px;
            --transition: all 0.3s ease;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Layout Principal */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: var(--text-light);
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }

        .sidebar-header {
            padding: 1.5rem 1rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .sidebar-subtitle {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .nav-link {
            color: var(--text-muted);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0.5rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
            text-decoration: none;
            display: flex;
            align-items: center;
            border-left: 3px solid transparent;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: var(--hover-bg);
            color: var(--text-light);
            border-left-color: var(--accent);
            transform: translateX(5px);
        }

        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }

        .menu-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
            margin: 1rem 0.5rem;
        }

        /* Contenido Principal */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
            transition: var(--transition);
            min-height: 100vh;
        }

        /* Estados del Sidebar */
        .sidebar.collapsed {
            width: 60px;
        }

        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .sidebar-subtitle {
            display: none;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.1rem;
        }

        .sidebar.collapsed~.main-content {
            margin-left: 60px;
        }

        /* Componentes */
        .card-panel {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .card-panel:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .badge-status {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-pending {
            background: #f39c12;
            color: white;
        }

        .badge-approved {
            background: #2ecc71;
            color: white;
        }

        .badge-rejected {
            background: #e74c3c;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .mobile-toggle {
                display: block !important;
            }
        }

        .mobile-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            padding: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">PromoShopping</div>
                <?php if ($panelType && isset($panelConfig[$panelType])): ?>
                    <div class="sidebar-subtitle"><?= $panelConfig[$panelType]['title'] ?></div>
                <?php endif; ?>
            </div>

            <nav class="sidebar-menu">
                <!-- Dashboard común -->
                <a class="nav-link <?= $currentPage === 'panel.php' ? 'active' : '' ?>" href="panel.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Menú específico del panel -->
                <?php if ($panelType && isset($panelConfig[$panelType])): ?>
                    <?php foreach ($panelConfig[$panelType]['menu'] as $item): ?>
                        <a class="nav-link <?= $currentPage === $item['link'] ? 'active' : '' ?>" href="<?= $item['link'] ?>">
                            <i class="fas <?= $item['icon'] ?>"></i>
                            <span><?= $item['text'] ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="menu-divider"></div>

                <!-- Menú común -->
                <a class="nav-link" href="#">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </a>

                <a class="nav-link" href="<?= SITE_URL ?>index.php">
                    <i class="fas fa-home"></i>
                    <span>Volver al Sitio</span>
                </a>

                <a class="nav-link text-danger" href="<?= SITE_URL ?>logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </nav>
        </aside>

        <!-- Botón móvil -->
        <button class="mobile-toggle" id="mobileToggle">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Contenido Principal -->
        <main class="main-content">