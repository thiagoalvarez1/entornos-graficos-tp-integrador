<nav id="sidebarMenu" class="d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3 sidebar-sticky">
        <div class="user-info text-center mb-4">
            <img src="https://via.placeholder.com/150/3498db/ffffff?text=U" class="rounded-circle mb-2" alt="Avatar">
            <h5 class="mb-0"><?= htmlspecialchars($auth->getCurrentUser()['email']) ?></h5>
            <span class="badge bg-info mt-1"><?= htmlspecialchars($auth->getCurrentUser()['type']) ?></span>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="panel.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Inicio</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'buscar_promociones.php') ? 'active' : ''; ?>"
                    aria-current="page" href="buscar_promociones.php">
                    <i class="fas fa-tags"></i>
                    <span>Buscar Promociones</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'mis_promociones.php') ? 'active' : ''; ?>"
                    href="mis_promociones.php">
                    <i class="fas fa-receipt"></i>
                    <span>Mis Promociones</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-bullhorn"></i>
                    <span>Novedades</span>
                </a>
            </li>
            <li class="nav-item mt-4">
                <a class="nav-link" href="<?= SITE_URL ?>index.php">
                    <i class="fas fa-home"></i>
                    <span>Volver al sitio</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= SITE_URL ?>logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </div>
</nav>