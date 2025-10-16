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

    <link rel="stylesheet" href="../css/header-panel.css">
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
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const sidebar = document.querySelector('.sidebar');
                    const sidebarToggle = document.querySelector('.sidebar-toggle');
                    const mobileToggle = document.querySelector('.mobile-toggle');
                    const sidebarOverlay = document.querySelector('.sidebar-overlay');

                    // Toggle sidebar en desktop
                    if (sidebarToggle) {
                        sidebarToggle.addEventListener('click', function () {
                            sidebar.classList.toggle('collapsed');
                            // Guardar estado en localStorage
                            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                        });
                    }

                    // Toggle sidebar en mobile
                    if (mobileToggle) {
                        mobileToggle.addEventListener('click', function () {
                            sidebar.classList.toggle('mobile-open');
                            sidebarOverlay.classList.toggle('mobile-open');
                        });
                    }

                    // Cerrar sidebar al hacer clic en overlay
                    if (sidebarOverlay) {
                        sidebarOverlay.addEventListener('click', function () {
                            sidebar.classList.remove('mobile-open');
                            sidebarOverlay.classList.remove('mobile-open');
                        });
                    }

                    // Cerrar sidebar en mobile al hacer clic en un link
                    const navLinks = document.querySelectorAll('.nav-link');
                    navLinks.forEach(link => {
                        link.addEventListener('click', function () {
                            if (window.innerWidth <= 768) {
                                sidebar.classList.remove('mobile-open');
                                sidebarOverlay.classList.remove('mobile-open');
                            }
                        });
                    });

                    // Detectar cambio de tamaño de ventana
                    window.addEventListener('resize', function () {
                        if (window.innerWidth > 768) {
                            sidebar.classList.remove('mobile-open');
                            sidebarOverlay.classList.remove('mobile-open');
                        }
                    });

                    // Cargar estado del sidebar desde localStorage
                    if (localStorage.getItem('sidebarCollapsed') === 'true') {
                        sidebar.classList.add('collapsed');
                    }
                });
            </script>
            <!-- ========== FIN DEL JAVASCRIPT ========== -->

</body>

</html>