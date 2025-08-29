<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

$auth = new Auth();
$auth->checkAccess(['administrador']);

$pageTitle = "Panel de Administrador";
require_once '../includes/header-panel.php';
?>

<!-- CSS específico para el panel -->
<style>
    body {
        overflow-x: hidden;
    }

    .sidebar {
        background-color: var(--primary);
        color: white;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        width: 250px;
        padding-top: 80px;
        /* Espacio para el navbar */
        transition: all 0.3s ease;
        z-index: 1000;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar.collapsed {
        left: -250px;
    }

    .main-content {
        margin-left: 250px;
        padding: 20px;
        transition: all 0.3s ease;
        min-height: 100vh;
        background-color: #f8f9fa;
    }

    .main-content.collapsed {
        margin-left: 0;
    }

    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.8);
        padding: 12px 20px;
        margin: 4px 10px;
        border-radius: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
        transform: translateX(5px);
    }

    .sidebar .nav-link i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }

    .card-dashboard {
        transition: transform 0.3s, box-shadow 0.3s;
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        position: relative;
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
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
    }

    .badge-aprobado {
        background-color: #2ecc71;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
    }

    .badge-rechazado {
        background-color: #e74c3c;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
    }

    .border-left-primary {
        border-left: 5px solid #4e73df;
    }

    .border-left-success {
        border-left: 5px solid #1cc88a;
    }

    .border-left-info {
        border-left: 5px solid #36b9cc;
    }

    .border-left-warning {
        border-left: 5px solid #f6c23e;
    }

    .chart-area {
        position: relative;
        height: 300px;
    }

    @media (max-width: 768px) {
        .sidebar {
            left: -250px;
        }

        .main-content {
            margin-left: 0;
        }

        .sidebar.show {
            left: 0;
        }
    }
</style>

<!-- Sidebar -->
<div class="sidebar">
    <div class="text-center mb-4 p-3">
        <h4 class="text-white">PromoShopping</h4>
        <p class="text-muted">Panel de Administración</p>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="#">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-store"></i> Gestión de Locales
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-tags"></i> Gestión de Promociones
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-user-check"></i> Validar Dueños
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-bullhorn"></i> Novedades
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
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
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Navbar superior -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 rounded shadow-sm">
        <div class="container-fluid">
            <button class="btn btn-sm btn-outline-secondary" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="d-flex align-items-center ms-auto">
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle text-dark text-decoration-none d-flex align-items-center"
                        id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name=Admin+User&background=random&size=32"
                            class="rounded-circle me-2" width="32" height="32">
                        <span>Admin User</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Perfil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Configuración</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="../logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50 me-1"></i> Generar Reporte
        </a>
    </div>

    <!-- Content Row - Estadísticas -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card-dashboard card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Locales Registrados
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">24</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-store stats-icon text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card-dashboard card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Promociones Activas
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">18</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags stats-icon text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card-dashboard card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                Solicitudes Pendientes
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">5</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list stats-icon text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card-dashboard card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Dueños por Validar
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">3</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check stats-icon text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row - Tablas -->
    <div class="row">
        <!-- Promociones Pendientes -->
        <div class="col-xl-6 col-lg-6">
            <div class="card-dashboard card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 fw-bold text-primary">Promociones Pendientes</h6>
                    <a class="btn btn-sm btn-primary" href="#">Ver todas</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                    <td>Fashion Store</td>
                                    <td>20% descuento en verano</td>
                                    <td><span class="badge-pendiente">Pendiente</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success me-1">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Shoes & More</td>
                                    <td>2x1 en calzado</td>
                                    <td><span class="badge-pendiente">Pendiente</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success me-1">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>TechWorld</td>
                                    <td>30% + 10% off acumulable</td>
                                    <td><span class="badge-pendiente">Pendiente</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success me-1">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger">
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

        <!-- Dueños por Validar -->
        <div class="col-xl-6 col-lg-6">
            <div class="card-dashboard card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 fw-bold text-primary">Dueños por Validar</h6>
                    <a class="btn btn-sm btn-primary" href="#">Ver todos</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                    <td>María González</td>
                                    <td>maria@fashionstore.com</td>
                                    <td>Fashion Store</td>
                                    <td>
                                        <button class="btn btn-sm btn-success me-1">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Carlos López</td>
                                    <td>carlos@shoesmore.com</td>
                                    <td>Shoes & More</td>
                                    <td>
                                        <button class="btn btn-sm btn-success me-1">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ana Martínez</td>
                                    <td>ana@techworld.com</td>
                                    <td>TechWorld</td>
                                    <td>
                                        <button class="btn btn-sm btn-success me-1">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger">
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
    </div>

    <!-- Gráfico de Actividad y Novedades -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card-dashboard card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 fw-bold text-primary">Resumen de Actividad</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Novedades Recientes -->
        <div class="col-xl-4 col-lg-5">
            <div class="card-dashboard card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 fw-bold text-primary">Novedades Recientes</h6>
                    <a class="btn btn-sm btn-primary" href="#">Nueva</a>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold">Nueva colección de verano</h6>
                        <p class="small text-muted">Publicado: 15/08/2025</p>
                        <p class="small">Descubre las nuevas tendencias de verano en todos nuestros locales.</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Horario extendido</h6>
                        <p class="small text-muted">Publicado: 10/08/2025</p>
                        <p class="small">A partir de esta semana, extendemos nuestro horario hasta las 22hs.</p>
                    </div>
                    <div>
                        <h6 class="fw-bold">Promociones de fin de mes</h6>
                        <p class="small text-muted">Publicado: 05/08/2025</p>
                        <p class="small">Aprovecha las increíbles ofertas de fin de mes en todos los locales.</p>
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
        // Script para el gráfico
        var ctx = document.getElementById("myAreaChart");
        if (ctx) {
            ctx = ctx.getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago"],
                    datasets: [{
                        label: "Promociones activas",
                        data: [12, 19, 15, 17, 14, 16, 18, 20],
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: 'rgba(78, 115, 223, 1)',
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        fill: true,
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });
        }

        // Toggle sidebar
        var sidebarToggle = document.getElementById('sidebarToggle');
        var sidebar = document.querySelector('.sidebar');
        var mainContent = document.querySelector('.main-content');

        if (sidebarToggle && sidebar && mainContent) {
            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('collapsed');

                // En móvil, usar clase 'show'
                if (window.innerWidth <= 768) {
                    sidebar.classList.toggle('show');
                }
            });
        }

        // Cerrar sidebar en móvil al hacer click fuera
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    });
</script>
<?php
require_once __DIR__ . '/../includes/footer-panel.php';
?>