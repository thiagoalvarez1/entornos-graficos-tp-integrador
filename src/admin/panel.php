<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

$auth = new Auth();
// Verificar que el usuario sea administrador
$auth->checkAccess(['administrador']);

$pageTitle = "Panel de Administrador";
require_once '../includes/header.php';
?>

<!-- SOLO el contenido ÚNICO del panel de administrador -->
<div class="sidebar">
    <div class="text-center mb-4">
        <h4>PromoShopping</h4>
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
                        <img src="https://ui-avatars.com/api/?name=Admin+User&background=random"
                            class="rounded-circle me-2" width="32" height="32">
                        <span>Admin User</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Perfil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Configuración</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i>
                                Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Generar Reporte
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Estadísticas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card-dashboard card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Locales Registrados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">24</div>
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
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Promociones Activas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
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
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Solicitudes Pendientes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">5</div>
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
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Dueños por Validar
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check stats-icon text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Promociones Pendientes -->
        <div class="col-xl-6 col-lg-6">
            <div class="card-dashboard card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Promociones Pendientes</h6>
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
                                    <td><span class="badge badge-pendiente">Pendiente</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Shoes & More</td>
                                    <td>2x1 en calzado</td>
                                    <td><span class="badge badge-pendiente">Pendiente</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>TechWorld</td>
                                    <td>30% + 10% off acumulable</td>
                                    <td><span class="badge badge-pendiente">Pendiente</span></td>
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

        <!-- Dueños por Validar -->
        <div class="col-xl-6 col-lg-6">
            <div class="card-dashboard card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Dueños por Validar</h6>
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
                                        <button class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Carlos López</td>
                                    <td>carlos@shoesmore.com</td>
                                    <td>Shoes & More</td>
                                    <td>
                                        <button class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ana Martínez</td>
                                    <td>ana@techworld.com</td>
                                    <td>TechWorld</td>
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
    </div>

    <!-- Gráfico de Actividad -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card-dashboard card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Resumen de Actividad</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Novedades Recientes -->
        <div class="col-xl-4 col-lg-5">
            <div class="card-dashboard card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Novedades Recientes</h6>
                    <a class="btn btn-sm btn-primary" href="#">Nueva</a>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="font-weight-bold">Nueva colección de verano</h6>
                        <p class="small text-muted">Publicado: 15/08/2025</p>
                        <p class="small">Descubre las nuevas tendencias de verano en todos nuestros locales.</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="font-weight-bold">Horario extendido</h6>
                        <p class="small text-muted">Publicado: 10/08/2025</p>
                        <p class="small">A partir de esta semana, extendemos nuestro horario hasta las 22hs.</p>
                    </div>
                    <div>
                        <h6 class="font-weight-bold">Promociones de fin de mes</h6>
                        <p class="small text-muted">Publicado: 05/08/2025</p>
                        <p class="small">Aprovecha las increíbles ofertas de fin de mes en todos los locales.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Script para el gráfico
    var ctx = document.getElementById("myAreaChart").getContext('2d');
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
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Toggle sidebar
    document.getElementById('sidebarToggle').addEventListener('click', function () {
        document.querySelector('.sidebar').classList.toggle('collapsed');
        document.querySelector('.main-content').classList.toggle('collapsed');
    });
</script>

<?php
require_once '../includes/footer.php';
?>