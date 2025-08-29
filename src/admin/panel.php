<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

$auth = new Auth();
$auth->checkAccess(['administrador']);

$pageTitle = "Panel de Administrador";
require_once '../includes/header-panel.php';
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    :root {
        --primary-purple: #6366f1;
        --secondary-purple: #8b5cf6;
        --accent-blue: #0ea5e9;
        --accent-green: #10b981;
        --accent-orange: #f59e0b;
        --accent-red: #ef4444;
        --gradient-start: #667eea;
        --gradient-middle: #764ba2;
        --gradient-end: #f093fb;
        --dark-bg: #0f172a;
        --dark-surface: #1e293b;
        --glass-bg: rgba(255, 255, 255, 0.1);
        --glass-border: rgba(255, 255, 255, 0.2);
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --shadow-light: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-medium: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --shadow-heavy: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(-45deg, var(--gradient-start), var(--gradient-middle), var(--secondary-purple), var(--gradient-end));
        background-size: 400% 400%;
        animation: gradientShift 20s ease infinite;
        min-height: 100vh;
        overflow-x: hidden;
        position: relative;
    }

    @keyframes gradientShift {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    /* Efectos de fondo */
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 80%, rgba(14, 165, 233, 0.1) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
    }

    /* Sidebar Moderno */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 280px;
        height: 100vh;
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(20px);
        border-right: 1px solid rgba(255, 255, 255, 0.1);
        z-index: 1000;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-y: auto;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .sidebar.collapsed {
        transform: translateX(-280px);
    }

    .sidebar::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 2px;
    }

    .sidebar-header {
        padding: 2rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        text-align: center;
    }

    .sidebar-logo {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        border-radius: 16px;
        margin-bottom: 1rem;
        animation: logoFloat 3s ease-in-out infinite;
    }

    @keyframes logoFloat {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-5px);
        }
    }

    .sidebar-logo i {
        color: white;
        font-size: 1.5rem;
    }

    .sidebar-title {
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .sidebar-subtitle {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.875rem;
        font-weight: 400;
    }

    .sidebar-nav {
        padding: 1rem 0;
    }

    .nav-section {
        margin-bottom: 2rem;
    }

    .nav-section-title {
        color: rgba(255, 255, 255, 0.4);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.75rem;
        padding: 0 1.5rem;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 0.875rem 1.5rem;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        margin: 0.25rem 1rem;
        border-radius: 12px;
        font-weight: 500;
    }

    .nav-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: var(--primary-purple);
        border-radius: 0 2px 2px 0;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    .nav-link:hover,
    .nav-link.active {
        color: white;
        background: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
    }

    .nav-link.active::before {
        transform: scaleY(1);
    }

    .nav-link i {
        width: 20px;
        margin-right: 0.875rem;
        font-size: 1.125rem;
    }

    .nav-link .badge {
        margin-left: auto;
        background: var(--accent-orange);
        color: white;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }

    /* Main Content */
    .main-content {
        margin-left: 280px;
        min-height: 100vh;
        padding: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        z-index: 1;
    }

    .main-content.collapsed {
        margin-left: 0;
    }

    /* Top Navbar */
    .top-navbar {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        padding: 1rem 2rem;
        box-shadow: var(--shadow-light);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .navbar-brand {
        display: none;
    }

    .sidebar-toggle {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        padding: 0.75rem;
        color: var(--text-primary);
        transition: all 0.3s ease;
    }

    .sidebar-toggle:hover {
        background: rgba(99, 102, 241, 0.1);
        border-color: var(--primary-purple);
        color: var(--primary-purple);
        transform: scale(1.05);
    }

    .user-dropdown {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 1rem;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        text-decoration: none;
        color: var(--text-primary);
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .user-dropdown:hover {
        background: rgba(99, 102, 241, 0.1);
        border-color: var(--primary-purple);
        color: var(--primary-purple);
        transform: translateY(-2px);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }

    /* Content Area */
    .content-area {
        padding: 2rem;
    }

    .page-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title {
        color: white;
        font-size: 2rem;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        margin: 0;
    }

    .btn-gradient {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        border: none;
        border-radius: 12px;
        color: white;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-medium);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-heavy);
        color: white;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-medium);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-start);
        transition: all 0.3s ease;
    }

    .stat-card.primary::before {
        background: linear-gradient(90deg, var(--primary-purple), var(--secondary-purple));
    }

    .stat-card.success::before {
        background: linear-gradient(90deg, var(--accent-green), #059669);
    }

    .stat-card.info::before {
        background: linear-gradient(90deg, var(--accent-blue), #0284c7);
    }

    .stat-card.warning::before {
        background: linear-gradient(90deg, var(--accent-orange), #d97706);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-heavy);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .stat-card.primary .stat-icon {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
        color: var(--primary-purple);
    }

    .stat-card.success .stat-icon {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
        color: var(--accent-green);
    }

    .stat-card.info .stat-icon {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.1), rgba(2, 132, 199, 0.1));
        color: var(--accent-blue);
    }

    .stat-card.warning .stat-icon {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
        color: var(--accent-orange);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .stat-change {
        font-size: 0.875rem;
        font-weight: 600;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .stat-change.positive {
        color: var(--accent-green);
    }

    .stat-change.negative {
        color: var(--accent-red);
    }

    /* Content Cards */
    .content-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .content-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: var(--shadow-medium);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .content-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-heavy);
    }

    .card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: between;
        align-items: center;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .card-body {
        padding: 2rem;
    }

    /* Tables */
    .table-container {
        border-radius: 12px;
        overflow: hidden;
        background: white;
    }

    .table {
        margin: 0;
    }

    .table th {
        background: rgba(99, 102, 241, 0.05);
        border: none;
        font-weight: 600;
        color: var(--text-primary);
        padding: 1rem;
    }

    .table td {
        border: none;
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .table tr:hover {
        background: rgba(99, 102, 241, 0.02);
    }

    /* Badges */
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .badge-pending {
        background: rgba(245, 158, 11, 0.1);
        color: var(--accent-orange);
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .badge-approved {
        background: rgba(16, 185, 129, 0.1);
        color: var(--accent-green);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .badge-rejected {
        background: rgba(239, 68, 68, 0.1);
        color: var(--accent-red);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    /* Action Buttons */
    .btn-action {
        padding: 0.5rem;
        border-radius: 8px;
        border: 1px solid transparent;
        transition: all 0.3s ease;
        margin: 0 0.25rem;
    }

    .btn-action.success {
        background: rgba(16, 185, 129, 0.1);
        color: var(--accent-green);
        border-color: rgba(16, 185, 129, 0.2);
    }

    .btn-action.danger {
        background: rgba(239, 68, 68, 0.1);
        color: var(--accent-red);
        border-color: rgba(239, 68, 68, 0.2);
    }

    .btn-action:hover {
        transform: scale(1.1);
    }

    /* Chart Container */
    .chart-container {
        height: 300px;
        position: relative;
    }

    /* News List */
    .news-item {
        padding: 1.5rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .news-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .news-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .news-date {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .news-excerpt {
        color: var(--text-secondary);
        font-size: 0.875rem;
        line-height: 1.5;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-280px);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
        }

        .content-area {
            padding: 1rem;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Dropdown Menu */
    .dropdown-menu {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        box-shadow: var(--shadow-heavy);
        padding: 0.5rem;
    }

    .dropdown-item {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .dropdown-item:hover {
        background: rgba(99, 102, 241, 0.1);
        color: var(--primary-purple);
    }
</style>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="fas fa-store"></i>
        </div>
        <h2 class="sidebar-title">PromoShopping</h2>
        <p class="sidebar-subtitle">Panel de Administración</p>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Principal</div>
            <a class="nav-link active" href="panel.php">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Gestión</div>
            <a class="nav-link" href="gestion-locales.php">
                <i class="fas fa-store"></i>
                <span>Gestión de Locales</span>
            </a>
            <a class="nav-link" href="gestion_promociones.php">
                <i class="fas fa-tags"></i>
                <span>Gestión de Promociones</span>
            </a>
            <a class="nav-link" href="validar-duenos.php">
                <i class="fas fa-user-check"></i>
                <span>Validar Dueños</span>
                <span class="badge">3</span>
            </a>
            <a class="nav-link" href="gestion_novedades.php">
                <i class="fas fa-bullhorn"></i>
                <span>Novedades</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Análisis</div>
            <a class="nav-link" href="reportes.php">
                <i class="fas fa-chart-bar"></i>
                <span>Reportes</span>
            </a>
            <a class="nav-link" href="#">
                <i class="fas fa-analytics"></i>
                <span>Estadísticas</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Sistema</div>
            <a class="nav-link" href="#">
                <i class="fas fa-cog"></i>
                <span>Configuración</span>
            </a>
            <a class="nav-link" href="../logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Top Navbar -->
    <nav class="top-navbar">
        <div class="d-flex justify-content-between align-items-center w-100">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <div class="dropdown">
                <a href="#" class="user-dropdown" id="userDropdown" role="button" data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=6366f1&color=fff&size=40"
                        class="user-avatar" alt="Admin">
                    <span>Admin User</span>
                    <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i>Perfil</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i>Configuración</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt"></i>Cerrar
                            Sesión</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content Area -->
    <div class="content-area">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Dashboard</h1>
            <a href="#" class="btn-gradient">
                <i class="fas fa-download"></i>
                Generar Reporte
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="fas fa-store"></i>
                </div>
                <div class="stat-value">24</div>
                <div class="stat-label">Locales Registrados</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    +12% este mes
                </div>
            </div>

            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-value">18</div>
                <div class="stat-label">Promociones Activas</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    +8% esta semana
                </div>
            </div>

            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-value">5</div>
                <div class="stat-label">Solicitudes Pendientes</div>
                <div class="stat-change negative">
                    <i class="fas fa-arrow-down"></i>
                    -20% hoy
                </div>
            </div>

            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-value">3</div>
                <div class="stat-label">Dueños por Validar</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    +2 nuevos
                </div>
            </div>
        </div>

        <!-- Content Cards -->
        <div class="content-grid">
            <!-- Promociones Pendientes -->
            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">Dueños por Validar</h3>
                    <a href="#" class="btn-gradient btn-sm">Ver todos</a>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Local</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>María González</strong></td>
                                    <td>maria@fashionstore.com</td>
                                    <td>Fashion Store</td>
                                    <td>
                                        <button class="btn-action success">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn-action danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Carlos López</strong></td>
                                    <td>carlos@shoesmore.com</td>
                                    <td>Shoes & More</td>
                                    <td>
                                        <button class="btn-action success">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn-action danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Ana Martínez</strong></td>
                                    <td>ana@techworld.com</td>
                                    <td>TechWorld</td>
                                    <td>
                                        <button class="btn-action success">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn-action danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart and News Row -->
        <div class="content-grid">
            <!-- Chart -->
            <div class="content-card" style="grid-column: 1 / -1;">
                <div class="card-header">
                    <h3 class="card-title">Resumen de Actividad</h3>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option>Últimos 7 días</option>
                            <option>Último mes</option>
                            <option>Últimos 3 meses</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <!-- Novedades Recientes -->
            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">Novedades Recientes</h3>
                    <a href="#" class="btn-gradient btn-sm">
                        <i class="fas fa-plus"></i>
                        Nueva
                    </a>
                </div>
                <div class="card-body">
                    <div class="news-item">
                        <h6 class="news-title">Nueva colección de verano</h6>
                        <p class="news-date">
                            <i class="fas fa-calendar me-1"></i>
                            15 de Agosto, 2025
                        </p>
                        <p class="news-excerpt">
                            Descubre las nuevas tendencias de verano en todos nuestros locales participantes.
                        </p>
                    </div>
                    <div class="news-item">
                        <h6 class="news-title">Horario extendido</h6>
                        <p class="news-date">
                            <i class="fas fa-calendar me-1"></i>
                            10 de Agosto, 2025
                        </p>
                        <p class="news-excerpt">
                            A partir de esta semana, extendemos nuestro horario de atención hasta las 22hs.
                        </p>
                    </div>
                    <div class="news-item">
                        <h6 class="news-title">Promociones de fin de mes</h6>
                        <p class="news-date">
                            <i class="fas fa-calendar me-1"></i>
                            5 de Agosto, 2025
                        </p>
                        <p class="news-excerpt">
                            Aprovecha las increíbles ofertas de fin de mes en todos los locales adheridos.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">Acciones Rápidas</h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="#" class="btn-gradient d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-plus-circle"></i>
                                <div>
                                    <strong>Agregar Local</strong>
                                    <small class="d-block opacity-75">Registrar nuevo comercio</small>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>

                        <a href="#" class="btn-gradient d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-bullhorn"></i>
                                <div>
                                    <strong>Nueva Novedad</strong>
                                    <small class="d-block opacity-75">Publicar anuncio</small>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>

                        <a href="#" class="btn-gradient d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-chart-line"></i>
                                <div>
                                    <strong>Ver Reportes</strong>
                                    <small class="d-block opacity-75">Analíticas detalladas</small>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>

                        <a href="#" class="btn-gradient d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-cog"></i>
                                <div>
                                    <strong>Configuración</strong>
                                    <small class="d-block opacity-75">Ajustes del sistema</small>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        sidebarToggle.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('collapsed');
            }
        });

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Activity Chart
        const ctx = document.getElementById('activityChart');
        if (ctx) {
            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0.05)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sab', 'Dom'],
                    datasets: [{
                        label: 'Promociones Activas',
                        data: [12, 19, 15, 17, 14, 16, 18],
                        backgroundColor: gradient,
                        borderColor: '#6366f1',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#6366f1',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                    }, {
                        label: 'Nuevos Locales',
                        data: [8, 12, 10, 14, 11, 13, 15],
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        borderColor: '#10b981',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    weight: '600'
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    weight: '600'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    weight: '600'
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }

        // Animate stats on scroll
        const observerOptions = {
            threshold: 0.3
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statValue = entry.target.querySelector('.stat-value');
                    if (statValue && !statValue.classList.contains('animated')) {
                        statValue.classList.add('animated');
                        animateNumber(statValue);
                    }
                }
            });
        }, observerOptions);

        document.querySelectorAll('.stat-card').forEach(card => {
            observer.observe(card);
        });

        function animateNumber(element) {
            const finalValue = parseInt(element.textContent);
            const duration = 1500;
            const increment = finalValue / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += increment;
                if (current >= finalValue) {
                    current = finalValue;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current);
            }, 16);
        }

        // Action button hover effects
        document.querySelectorAll('.btn-action').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                // Create ripple effect
                const ripple = document.createElement('div');
                const rect = button.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');

                button.appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add CSS for ripple effect
        const style = document.createElement('style');
        style.textContent = `
            .btn-action {
                position: relative;
                overflow: hidden;
            }
            
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                animation: ripple-animation 0.6s ease-out;
                pointer-events: none;
            }
            
            @keyframes ripple-animation {
                0% {
                    transform: scale(0);
                    opacity: 1;
                }
                100% {
                    transform: scale(2);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    });
</script>

<?php
require_once __DIR__ . '/../includes/footer-panel.php';
?>">Promociones Pendientes</h3>
<a href="#" class="btn-gradient btn-sm">Ver todas</a>
</div>
<div class="card-body">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Local</th>
                    <th>Promoción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Fashion Store</strong></td>
                    <td>20% descuento en verano</td>
                    <td><span class="badge badge-pending">Pendiente</span></td>
                    <td>
                        <button class="btn-action success">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn-action danger">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td><strong>Shoes & More</strong></td>
                    <td>2x1 en calzado</td>
                    <td><span class="badge badge-pending">Pendiente</span></td>
                    <td>
                        <button class="btn-action success">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn-action danger">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td><strong>TechWorld</strong></td>
                    <td>30% + 10% off acumulable</td>
                    <td><span class="badge badge-pending">Pendiente</span></td>
                    <td>
                        <button class="btn-action success">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn-action danger">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>

<div class="content-card">
    <div class="card-header">
        <h3 class="card-title">Dueños por Validar</h3>
        <a href="#" class="btn-gradient btn-sm">Ver todos</a>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Local</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>María González</strong></td>
                        <td>maria@fashionstore.com</td>
                        <td>Fashion Store</td>
                        <td>
                            <button class="btn-action success">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn-action danger">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Carlos López</strong></td>
                        <td>carlos@shoesmore.com</td>
                        <td>Shoes & More</td>
                        <td>
                            <button class="btn-action success">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn-action danger">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Ana Martínez</strong></td>
                        <td>ana@techworld.com</td>
                        <td>TechWorld</td>
                        <td>
                            <button class="btn-action success">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn-action danger">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Promociones Pendientes -->
<div class="content-card">
    <div class="card-header">
        <h3 class="card-title">Promociones Pendientes</h3>
        <a href="#" class="btn-gradient btn-sm">Ver todas</a>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Local</th>
                        <th>Promoción</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Fashion Store</strong></td>
                        <td>20% descuento en verano</td>
                        <td><span class="badge badge-pending">Pendiente</span></td>
                        <td>
                            <button class="btn-action success">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn-action danger">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Shoes & More</strong></td>
                        <td>2x1 en calzado</td>
                        <td><span class="badge badge-pending">Pendiente</span></td>
                        <td>
                            <button class="btn-action success">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn-action danger">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>TechWorld</strong></td>
                        <td>30% + 10% off acumulable</td>
                        <td><span class="badge badge-pending">Pendiente</span></td>
                        <td>
                            <button class="btn-action success">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn-action danger">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<!-- Chart and News Row -->
<div class="content-grid">
    <!-- Chart -->
    <div class="content-card" style="grid-column: 1 / -1;">
        <div class="card-header">
            <h3 class="card-title">Resumen de Actividad</h3>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" style="width: auto;">
                    <option>Últimos 7 días</option>
                    <option>Último mes</option>
                    <option>Últimos 3 meses</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="content-grid">
    <!-- Novedades Recientes -->
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Novedades Recientes</h3>
            <a href="#" class="btn-gradient btn-sm">
                <i class="fas fa-plus"></i>
                Nueva
            </a>
        </div>
        <div class="card-body">
            <div class="news-item">
                <h6 class="news-title">Nueva colección de verano</h6>
                <p class="news-date">
                    <i class="fas fa-calendar"></i>
                    15 de Agosto, 2025
                </p>
                <p class="news-excerpt">
                    Descubre las nuevas tendencias de verano en todos nuestros locales participantes.
                </p>
            </div>
            <div class="news-item">
                <h6 class="news-title">Horario extendido</h6>
                <p class="news-date">
                    <i class="fas fa-calendar"></i>
                    10 de Agosto, 2025
                </p>
                <p class="news-excerpt">
                    A partir de esta semana, extendemos nuestro horario de atención hasta las 22hs.
                </p>
            </div>
            <div class="news-item">
                <h6 class="news-title">Promociones de fin de mes</h6>
                <p class="news-date">
                    <i class="fas fa-calendar"></i>
                    5 de Agosto, 2025
                </p>
                <p class="news-excerpt">
                    Aprovecha las increíbles ofertas de fin de mes en todos los locales adheridos.
                </p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Acciones Rápidas</h3>
        </div>
        <div class="card-body">
            <div class="d-grid gap-3">
                <a href="#" class="btn-gradient d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-plus-circle"></i>
                        <div>
                            <strong>Agregar Local</strong>
                            <small class="d-block opacity-75">Registrar nuevo comercio</small>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </a>

                <a href="#" class="btn-gradient d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-bullhorn"></i>
                        <div>
                            <strong>Nueva Novedad</strong>
                            <small class="d-block opacity-75">Publicar anuncio</small>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </a>

                <a href="#" class="btn-gradient d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-chart-line"></i>
                        <div>
                            <strong>Ver Reportes</strong>
                            <small class="d-block opacity-75">Analíticas detalladas</small>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </a>

                <a href="#" class="btn-gradient d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-cog"></i>
                        <div>
                            <strong>Configuración</strong>
                            <small class="d-block opacity-75">Ajustes del sistema</small>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        sidebarToggle.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('collapsed');
            }
        });

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Activity Chart
        const ctx = document.getElementById('activityChart');
        if (ctx) {
            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0.05)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sab', 'Dom'],
                    datasets: [{
                        label: 'Promociones Activas',
                        data: [12, 19, 15, 17, 14, 16, 18],
                        backgroundColor: gradient,
                        borderColor: '#6366f1',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#6366f1',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                    }, {
                        label: 'Nuevos Locales',
                        data: [8, 12, 10, 14, 11, 13, 15],
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        borderColor: '#10b981',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    weight: '600'
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    weight: '600'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    weight: '600'
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }

        // Animate stats on scroll
        const observerOptions = {
            threshold: 0.3
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statValue = entry.target.querySelector('.stat-value');
                    if (statValue && !statValue.classList.contains('animated')) {
                        statValue.classList.add('animated');
                        animateNumber(statValue);
                    }
                }
            });
        }, observerOptions);

        document.querySelectorAll('.stat-card').forEach(card => {
            observer.observe(card);
        });

        function animateNumber(element) {
            const finalValue = parseInt(element.textContent);
            const duration = 1500;
            const increment = finalValue / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += increment;
                if (current >= finalValue) {
                    current = finalValue;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current);
            }, 16);
        }

        // Action button hover effects
        document.querySelectorAll('.btn-action').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                // Create ripple effect
                const ripple = document.createElement('div');
                const rect = button.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');

                button.appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    });
</script>

<?php
require_once __DIR__ . '/../includes/footer-panel.php';
?>