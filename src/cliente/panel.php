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
        <h4>PromoShopping</h4>
        <p class="text-muted">Panel de Cliente</p>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="#">
                <i class="fas fa-tachometer-alt"></i> Inicio
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-tags"></i> Buscar Promociones
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-history"></i> Historial
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-bullhorn"></i> Novedades
            </a>
        </li>
        <li class="nav-item mt-4">
            <a class="nav-link" href="#">
                <i class="fas fa-user"></i> Mi Perfil
            </a>
        </li>
        <li class="nav-item">
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
                        <img src="https://ui-avatars.com/api/?name=Laura+Martinez&background=random"
                            class="rounded-circle me-2" width="32" height="32">
                        <span>Laura Martínez</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Perfil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Configuración</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i> Cerrar
                                Sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- User Header -->
    <div class="user-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2>¡Hola, Laura!</h2>
                <p class="mb-0">Descubre las mejores promociones para ti</p>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="category-badge badge-inicial">Categoría: Inicial</span>
                <p class="mb-0 mt-2">5 promociones utilizadas</p>
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Promociones
                                Usadas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">5</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ticket-alt stats-icon text-primary"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ahorro Total
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$1,250</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign stats-icon text-success"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Locales Visitados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-store stats-icon text-info"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Para Premium
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">5/10</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star stats-icon text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Promociones Recomendadas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-dashboard card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Promociones Recomendadas</h6>
                    <a class="btn btn-sm btn-primary" href="#">Ver todas</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="promo-card card h-100">
                                <span class="category-badge badge-inicial">Inicial</span>
                                <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-4.0.3"
                                    class="card-img-top" alt="Local 1" height="160" style="object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">20% de descuento</h5>
                                    <p class="card-text">En toda la colección de verano. Válido hasta el 30/12/2025.
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted"><i class="fas fa-store me-1"></i> Fashion
                                            Store</small>
                                        <small class="text-muted"><i class="fas fa-calendar me-1"></i> L-V</small>
                                    </div>
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="#" class="btn btn-primary w-100">Usar promoción</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="promo-card card h-100">
                                <span class="category-badge badge-inicial">Inicial</span>
                                <img src="https://images.unsplash.com/photo-1605733513597-a8f8341084e6?ixlib=rb-4.0.3"
                                    class="card-img-top" alt="Local 2" height="160" style="object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">15% en accesorios</h5>
                                    <p class="card-text">En toda la sección de accesorios. Válido hasta el
                                        25/08/2025.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted"><i class="fas fa-store me-1"></i> Fashion
                                            Store</small>
                                        <small class="text-muted"><i class="fas fa-calendar me-1"></i> Todos</small>
                                    </div>
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="#" class="btn btn-primary w-100">Usar promoción</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="promo-card card h-100">
                                <span class="category-badge badge-inicial">Inicial</span>
                                <img src="https://images.unsplash.com/photo-1593642632823-8f785ba67e45?ixlib=rb-4.0.3"
                                    class="card-img-top" alt="Local 3" height="160" style="object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">10% en segunda unidad</h5>
                                    <p class="card-text">En la segunda unidad de cualquier producto. Válido hasta el
                                        20/09/2025.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted"><i class="fas fa-store me-1"></i>
                                            TechWorld</small>
                                        <small class="text-muted"><i class="fas fa-calendar me-1"></i> Fin de
                                            semana</small>
                                    </div>
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="#" class="btn btn-primary w-100">Usar promoción</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Novedades -->
        <div class="col-xl-6 col-lg-6">
            <div class="card-dashboard card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Novedades</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3 p-3 border rounded">
                        <h6 class="font-weight-bold">Nueva colección de verano</h6>
                        <p class="small text-muted">Publicado: 15/08/2025 • Para: Todos</p>
                        <p class="small mb-0">Descubre las nuevas tendencias de verano en todos nuestros locales. No
                            te quedes sin tu outfit perfecto para esta temporada.</p>
                    </div>
                    <div class="mb-3 p-3 border rounded">
                        <h6 class="font-weight-bold">Horario extendido</h6>
                        <p class="small text-muted">Publicado: 10/08/2025 • Para: Todos</p>
                        <p class="small mb-0">A partir de esta semana, extendemos nuestro horario hasta las 22hs.
                            Vení a disfrutar de tus compras con más tiempo.</p>
                    </div>
                    <div class="p-3 border rounded">
                        <h6 class="font-weight-bold">Promociones de fin de mes</h6>
                        <p class="small text-muted">Publicado: 05/08/2025 • Para: Inicial</p>
                        <p class="small mb-0">Aprovecha las increíbles ofertas de fin de mes en todos los locales.
                            Descuentos especiales para clientes iniciales.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial Reciente -->
        <div class="col-xl-6 col-lg-6">
            <div class="card-dashboard card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Historial Reciente</h6>
                    <a class="btn btn-sm btn-primary" href="#">Ver todo</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Local</th>
                                    <th>Promoción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>15/08/2025</td>
                                    <td>Fashion Store</td>
                                    <td>20% descuento en verano</td>
                                    <td><span class="badge bg-success">Aceptada</span></td>
                                </tr>
                                <tr>
                                    <td>14/08/2025</td>
                                    <td>Shoes & More</td>
                                    <td>2x1 en calzado</td>
                                    <td><span class="badge bg-success">Aceptada</span></td>
                                </tr>
                                <tr>
                                    <td>13/08/2025</td>
                                    <td>TechWorld</td>
                                    <td>30% + 10% off acumulable</td>
                                    <td><span class="badge bg-warning">Pendiente</span></td>
                                </tr>
                                <tr>
                                    <td>12/08/2025</td>
                                    <td>Fashion Store</td>
                                    <td>15% en accesorios</td>
                                    <td><span class="badge bg-success">Aceptada</span></td>
                                </tr>
                                <tr>
                                    <td>10/08/2025</td>
                                    <td>TechWorld</td>
                                    <td>10% en segunda unidad</td>
                                    <td><span class="badge bg-danger">Rechazada</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle sidebar
    document.getElementById('sidebarToggle').addEventListener('click', function () {
        document.querySelector('.sidebar').classList.toggle('collapsed');
        document.querySelector('.main-content').classList.toggle('collapsed');
    });
</script>

<?php
require_once '../includes/footer.php';
?>