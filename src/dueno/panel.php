<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

$auth = new Auth();
$auth->checkAccess(['dueño de local']); // Usa 'dueño de local'

$pageTitle = "Panel de Dueño";
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

    .sidebar .text-center {
        padding: 2rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        position: relative;
    }

    .sidebar .text-center::before {
        content: '';
        position: absolute;
        top: 1rem;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: logoFloat 3s ease-in-out infinite;
    }

    @keyframes logoFloat {

        0%,
        100% {
            transform: translateX(-50%) translateY(0px);
        }

        50% {
            transform: translateX(-50%) translateY(-5px);
        }
    }

    .sidebar h4 {
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 4rem 0 0.25rem 0;
    }

    .sidebar .text-muted {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.875rem;
        font-weight: 400;
        margin: 0;
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
    .navbar {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        padding: 1rem 2rem;
        box-shadow: var(--shadow-light);
        margin-bottom: 0;
    }

    .btn-light {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        padding: 0.75rem;
        color: var(--text-primary);
        transition: all 0.3s ease;
    }

    .btn-light:hover {
        background: rgba(99, 102, 241, 0.1);
        border-color: var(--primary-purple);
        color: var(--primary-purple);
        transform: scale(1.05);
    }

    .dropdown-toggle {
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

    .dropdown-toggle:hover {
        background: rgba(99, 102, 241, 0.1);
        border-color: var(--primary-purple);
        color: var(--primary-purple);
        transform: translateY(-2px);
    }

    .rounded-circle {
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 12px;
    }

    /* Local Header */
    .local-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 2rem;
        margin: 2rem;
        box-shadow: var(--shadow-medium);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .local-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-purple), var(--secondary-purple));
    }

    .local-header:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-heavy);
    }

    .local-header h2 {
        color: var(--text-primary);
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .local-header h2::before {
        content: '';
        width: 12px;
        height: 12px;
        background: var(--accent-green);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.7;
            transform: scale(0.9);
        }
    }

    .local-header p {
        color: var(--text-secondary);
        font-size: 1rem;
        margin: 0;
    }

    .badge {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
        color: var(--primary-purple);
        border: 1px solid rgba(99, 102, 241, 0.2);
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    /* Stats Cards */
    .row {
        padding: 0 2rem;
        margin: 0;
    }

    .col-xl-3,
    .col-md-6 {
        padding: 0 0.75rem;
    }

    .card-dashboard {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: var(--shadow-medium);
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .card-dashboard:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-heavy);
    }

    .border-left-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, var(--primary-purple), var(--secondary-purple));
    }

    .border-left-success::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, var(--accent-green), #059669);
    }

    .border-left-info::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, var(--accent-blue), #0284c7);
    }

    .border-left-warning::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, var(--accent-orange), #d97706);
    }

    .card-body {
        padding: 2rem;
    }

    .stats-icon {
        font-size: 2rem;
        opacity: 0.2;
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
    }

    .text-xs {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .h5 {
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
    }

    .text-primary {
        color: var(--primary-purple) !important;
    }

    .text-success {
        color: var(--accent-green) !important;
    }

    .text-info {
        color: var(--accent-blue) !important;
    }

    .text-warning {
        color: var(--accent-orange) !important;
    }

    /* Content Cards */
    .card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: var(--shadow-medium);
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-heavy);
    }

    .card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(139, 92, 246, 0.05));
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-purple), var(--secondary-purple));
    }

    .card-header h6 {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header h6::before {
        content: '';
        width: 8px;
        height: 8px;
        background: var(--primary-purple);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
        font-family: inherit;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        color: white;
        box-shadow: var(--shadow-medium);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-heavy);
        color: white;
    }

    .btn-success {
        background: linear-gradient(135deg, var(--accent-green), #059669);
        color: white;
        box-shadow: var(--shadow-light);
    }

    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-medium);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--accent-red), #dc2626);
        color: white;
        box-shadow: var(--shadow-light);
    }

    .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-medium);
        color: white;
    }

    .btn-outline-danger {
        background: transparent;
        color: var(--accent-red);
        border: 2px solid var(--accent-red);
    }

    .btn-outline-danger:hover {
        background: var(--accent-red);
        color: white;
        transform: translateY(-1px);
    }

    /* Table Styles */
    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
        background: white;
    }

    .table {
        margin: 0;
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.05));
        border: none;
        font-weight: 600;
        color: var(--text-primary);
        padding: 1rem;
        font-size: 0.875rem;
    }

    .table tbody td {
        border: none;
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .table tbody tr:hover {
        background: rgba(99, 102, 241, 0.02);
    }

    /* Promotion Cards */
    .promotion-card {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(99, 102, 241, 0.1);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .promotion-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
        border-color: var(--primary-purple);
    }

    .promotion-card h6 {
        color: var(--text-primary);
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .promotion-card .small {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .promotion-card .badge {
        background: linear-gradient(135deg, var(--accent-green), #059669);
        color: white;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    /* Chart Container */
    .chart-area {
        height: 300px;
        position: relative;
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

    /* Responsive */
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

        .local-header,
        .row {
            padding: 0 1rem;
        }

        .local-header {
            margin: 1rem;
        }
    }

    /* Animations */
    .card-dashboard {
        opacity: 0;
        animation: slideInUp 0.6s ease forwards;
    }

    .card-dashboard:nth-child(1) {
        animation-delay: 0.1s;
    }

    .card-dashboard:nth-child(2) {
        animation-delay: 0.2s;
    }

    .card-dashboard:nth-child(3) {
        animation-delay: 0.3s;
    }

    .card-dashboard:nth-child(4) {
        animation-delay: 0.4s;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Utility Classes */
    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .align-items-center {
        align-items: center;
    }

    .text-md-end {
        text-align: right;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .p-3 {
        padding: 1rem;
    }

    .border {
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .rounded {
        border-radius: 8px;
    }

    .text-muted {
        color: var(--text-secondary);
    }

    .font-weight-bold {
        font-weight: 700;
    }
</style>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="text-center mb-4">
        <h4>Fashion Store</h4>
        <p class="text-muted">Panel de Dueño</p>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="panel.php">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="mis_promociones.php">
                <i class="fas fa-tags"></i> Mis Promociones
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="mi_local.php">
                <i class="fas fa-tags"></i> Mi local
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-clipboard-check"></i> Solicitudes
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-chart-bar"></i> Estadísticas
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
    </ul>
</div>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container-fluid">
            <button class="btn btn-sm btn-light" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle text-dark text-decoration-none" id="userDropdown" role="button"
                        data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name=Maria+Gonzalez&background=6366f1&color=fff&size=32"
                            class="rounded-circle me-2" width="32" height="32">
                        <span>María González</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Perfil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Configuración</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Local Header -->
    <div class="local-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2>Fashion Store</h2>
                <p class="mb-0">Moda actual y tendencias para todos los estilos</p>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="badge">Código: L-1025</span>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Estadísticas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card-dashboard card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Promociones Activas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">5</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags stats-icon text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card-dashboard card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Solicitudes Hoy</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list stats-icon text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card-dashboard card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasa de Aceptación</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">78%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percent stats-icon text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card-dashboard card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Clientes Únicos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">184</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users stats-icon text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Solicitudes Recientes -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6>Solicitudes Recientes</h6>
                    <a class="btn btn-sm btn-primary" href="#">Ver todas</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Promoción</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Laura Martínez</strong></td>
                                    <td>20% descuento en verano</td>
                                    <td><small>15/08/2025 14:30</small></td>
                                    <td>
                                        <button class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Juan Pérez</strong></td>
                                    <td>15% en accesorios</td>
                                    <td><small>15/08/2025 13:15</small></td>
                                    <td>
                                        <button class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Mónica Silva</strong></td>
                                    <td>2x1 en jeans</td>
                                    <td><small>15/08/2025 12:45</small></td>
                                    <td>
                                        <button class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promociones Activas -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6>Promociones Activas</h6>
                    <a class="btn btn-sm btn-primary" href="#">Nueva</a>
                </div>
                <div class="card-body">
                    <div class="promotion-card">
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-bold">20% descuento en verano</h6>
                            <span class="badge">Activa</span>
                        </div>
                        <p class="small mb-1">Válida: 01/08/2025 - 31/08/2025</p>
                        <p class="small mb-2">Categoría: Inicial • Días: Lunes a Viernes</p>
                        <div class="d-flex justify-content-between">
                            <span class="small text-muted">12 usos hoy</span>
                            <div>
                                <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                            </div>
                        </div>
                    </div>
                    <div class="promotion-card">
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-bold">15% en accesorios</h6>
                            <span class="badge">Activa</span>
                        </div>
                        <p class="small mb-1">Válida: 05/08/2025 - 25/08/2025</p>
                        <p class="small mb-2">Categoría: Medium • Días: Todos los días</p>
                        <div class="d-flex justify-content-between">
                            <span class="small text-muted">8 usos hoy</span>
                            <div>
                                <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                            </div>
                        </div>
                    </div>
                    <div class="promotion-card">
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-bold">2x1 en jeans</h6>
                            <span class="badge">Activa</span>
                        </div>
                        <p class="small mb-1">Válida: 10/08/2025 - 30/08/2025</p>
                        <p class="small mb-2">Categoría: Premium • Días: Fin de semana</p>
                        <div class="d-flex justify-content-between">
                            <span class="small text-muted">5 usos hoy</span>
                            <div>
                                <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Actividad -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6>Actividad de Promociones</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="localActivityChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        // Chart setup
        const ctx = document.getElementById("localActivityChart");
        if (ctx) {
            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0.05)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
                    datasets: [{
                        label: "Solicitudes de promociones",
                        data: [18, 22, 15, 20, 25, 30, 28],
                        backgroundColor: gradient,
                        borderColor: '#6366f1',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
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
                    }
                }
            });
        }

        // Add ripple effects to buttons
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('click', function (e) {
                const ripple = document.createElement('div');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255, 255, 255, 0.3)';
                ripple.style.animation = 'ripple-animation 0.6s ease-out';
                ripple.style.pointerEvents = 'none';

                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
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

        // Animate stats cards
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statValue = entry.target.querySelector('.h5');
                    if (statValue && !statValue.classList.contains('animated')) {
                        statValue.classList.add('animated');
                        animateNumber(statValue);
                    }
                }
            });
        }, { threshold: 0.3 });

        document.querySelectorAll('.card-dashboard').forEach(card => {
            statsObserver.observe(card);
        });

        function animateNumber(element) {
            const finalValue = parseInt(element.textContent);
            if (isNaN(finalValue)) return;

            const duration = 1500;
            const increment = finalValue / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += increment;
                if (current >= finalValue) {
                    current = finalValue;
                    clearInterval(timer);
                }

                if (element.textContent.includes('%')) {
                    element.textContent = Math.floor(current) + '%';
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 16);
        }
    });
</script>

<?php
require_once __DIR__ . '/../includes/footer-panel.php';
?>