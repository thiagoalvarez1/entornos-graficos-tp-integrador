<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="position-sticky pt-3 sidebar-sticky">
    <div class="user-info text-center mb-4">
        <img src="https://via.placeholder.com/150/f59e0b/ffffff?text=D" class="rounded-circle mb-2" alt="Avatar">
        <h5 class="mb-0"><?= htmlspecialchars($userEmail) ?></h5>
        <span class="badge bg-warning text-dark mt-1"><?= htmlspecialchars($userType) ?></span>
    </div>
    <h6
        class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
        <span>Gestión del Local</span>
    </h6>
    <ul class="nav flex-column mb-2">
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'panel.php' ? 'active' : '') ?>" href="panel.php">
                <i class="fas fa-tachometer-alt"></i>
                <span>Panel</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'mi_local.php' ? 'active' : '') ?>" href="mi_local.php">
                <i class="fas fa-store"></i>
                <span>Mi Local</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'mis_promociones.php' ? 'active' : '') ?>"
                href="mis_promociones.php">
                <i class="fas fa-tags"></i>
                <span>Promociones</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'mis_solicitudes.php' ? 'active' : '') ?>"
                href="mis_solicitudes.php">
                <i class="fas fa-receipt"></i>
                <span>Solicitudes Cliente</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'reportes_promociones.php' ? 'active' : '') ?>"
                href="reportes_promociones.php">
                <i class="fas fa-chart-bar"></i>
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