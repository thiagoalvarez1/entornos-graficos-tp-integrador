<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

$auth = new Auth();
// Verificar que el usuario sea administrador
$auth->checkAccess(['administrador']);

$pageTitle = "Panel de Administrador";
require_once '../includes/header.php';
?>
<!-- Sidebar -->
<div class="sidebar">
    <div class="text-center mb-4">
        <h4>Fashion Store</h4>
        <p class="text-muted">Panel de Dueño</p>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="#">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-tags"></i> Mis Promociones
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
            <a class="nav-link" href="#">
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
                        <img src="https://ui-avatars.com/api/?name=Maria+Gonzalez&background=random"
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
                <span class="badge bg-light text-dark p-2">Código: L-1025</span>
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
            <div class="card-dashboard card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Solicitudes Recientes</h6>
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
                                    <td>Laura Martínez</td>
                                    <td>20% descuento en verano</td>
                                    <td>15/08/2025 14:30</td>
                                    <td>
                                        <button class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Juan Pérez</td>
                                    <td>15% en accesorios</td>
                                    <td>15/08/2025 13:15</td>
                                    <td>
                                        <button class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Mónica Silva</td>
                                    <td>2x1 en jeans</td>
                                    <td>15/08/2025 12:45</td>
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
            <div class="card-dashboard card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Promociones Activas</h6>
                    <a class="btn btn-sm btn-primary" href="#">Nueva</a>
                </div>
                <div class="card-body">
                    <div class="mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-bold">20% descuento en verano</h6>
                            <span class="badge bg-success">Activa</span>
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
                    <div class="mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-bold">15% en accesorios</h6>
                            <span class="badge bg-success">Activa</span>
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
                    <div class="p-3 border rounded">
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-bold">2x1 en jeans</h6>
                            <span class="badge bg-success">Activa</span>
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
            <div class="card-dashboard card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Actividad de Promociones</h6>
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
    // Script para el gráfico de actividad del local
    var ctx = document.getElementById("localActivityChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
            datasets: [{
                label: "Solicitudes de promociones",
                data: [18, 22, 15, 20, 25, 30, 28],
                backgroundColor: 'rgba(52, 152, 219, 0.7)',
                borderColor: 'rgba(52, 152, 219, 1)',
                borderWidth: 1
            }]
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