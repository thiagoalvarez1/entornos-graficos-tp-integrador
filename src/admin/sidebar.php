<?php
// Asegúrate de que $userType se haya cargado en header-panel.php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="position-sticky pt-3 sidebar-sticky">
    <div class="user-info text-center mb-4">
        <img src="https://via.placeholder.com/150/ef4444/ffffff?text=A" class="rounded-circle mb-2" alt="Avatar">
        <h5 class="mb-0"><?= htmlspecialchars($userEmail) ?></h5>
        <span class="badge bg-danger mt-1"><?= htmlspecialchars($userType) ?></span>
    </div>
    <h6
        class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
        <span>Administración</span>
    </h6>
    <ul class="nav flex-column mb-2">
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'panel.php' ? 'active' : '') ?>" href="panel.php">
                <i class="fas fa-tachometer-alt"></i>
                <span>Panel</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'validar-duenos.php' ? 'active' : '') ?>" href="validar-duenos.php">
                <i class="fas fa-user-check"></i>
                <span>Validar Dueños</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'gestion-locales.php' ? 'active' : '') ?>"
                href="gestion-locales.php">
                <i class="fas fa-store"></i>
                <span>Gestión de Locales</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'gestion_promociones.php' ? 'active' : '') ?>"
                href="gestion_promociones.php">
                <i class="fas fa-tags"></i>
                <span>Gestión de Promos</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'gestion_novedades.php' ? 'active' : '') ?>"
                href="gestion_novedades.php">
                <i class="fas fa-bullhorn"></i>
                <span>Gestión de Novedades</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'reportes.php' ? 'active' : '') ?>" href="reportes.php">
                <i class="fas fa-chart-line"></i>
                <span>Reportes</span>
            </a>
        </li>
    </ul>

    <h6
        class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
        <span>Sistema</span>
    </h6>
    <ul class="nav flex-column mb-2">
        <li class="nav-item">
            <a class="nav-link" href="<?= SITE_URL ?>index.php">
                <i class="fas fa-home"></i>
                <span>Volver al Sitio</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-danger" href="<?= SITE_URL ?>logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
        </li>
    </ul>
</div>