<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess([USER_CLIENT]);

// Obtener datos del cliente
$database = new Database();
$conn = $database->getConnection();
$user_id = $_SESSION['user_id'];

// Estadísticas del cliente
$query_stats = "SELECT 
    COUNT(DISTINCT up.codPromo) as promociones_usadas,
    COUNT(DISTINCT p.codLocal) as locales_visitados,
    COUNT(CASE WHEN up.estado = 'aceptada' THEN 1 END) as descuentos_obtenidos,
    COUNT(CASE WHEN up.estado = 'enviada' THEN 1 END) as solicitudes_pendientes
    FROM uso_promociones up
    JOIN promociones p ON up.codPromo = p.codPromo
    WHERE up.codCliente = :user_id";

$stmt_stats = $conn->prepare($query_stats);
$stmt_stats->bindParam(':user_id', $user_id);
$stmt_stats->execute();
$stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);

// Promociones recientes utilizadas
$query_recientes = "SELECT 
    p.textoPromo,
    p.categoriaCliente,
    l.nombreLocal,
    up.fechaUsoPromo,
    up.estado
    FROM uso_promociones up
    JOIN promociones p ON up.codPromo = p.codPromo
    JOIN locales l ON p.codLocal = l.codLocal
    WHERE up.codCliente = :user_id
    ORDER BY up.fechaUsoPromo DESC
    LIMIT 5";

$stmt_recientes = $conn->prepare($query_recientes);
$stmt_recientes->bindParam(':user_id', $user_id);
$stmt_recientes->execute();
$promociones_recientes = $stmt_recientes->fetchAll(PDO::FETCH_ASSOC);

// Promociones disponibles (nuevas)
$query_disponibles = "SELECT 
    p.codPromo,
    p.textoPromo,
    p.categoriaCliente,
    l.nombreLocal,
    p.fechaCreacion
    FROM promociones p
    JOIN locales l ON p.codLocal = l.codLocal
    WHERE p.estadoPromo = 'aprobada' 
    AND p.categoriaCliente = (
        SELECT CASE 
            WHEN COUNT(*) >= 10 THEN 'Premium'
            WHEN COUNT(*) >= 5 THEN 'Medium'
            ELSE 'Inicial'
        END
        FROM uso_promociones up2
        WHERE up2.codCliente = :user_id AND up2.estado = 'aceptada'
    )
    AND p.codPromo NOT IN (
        SELECT up3.codPromo 
        FROM uso_promociones up3 
        WHERE up3.codCliente = :user_id
    )
    ORDER BY p.fechaCreacion DESC
    LIMIT 6";

$stmt_disponibles = $conn->prepare($query_disponibles);
$stmt_disponibles->bindParam(':user_id', $user_id);
$stmt_disponibles->execute();
$promociones_disponibles = $stmt_disponibles->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Panel Cliente";
require_once '../includes/header-panel.php';
?>

<style>
    :root {
        --primary-purple: #8B5CF6;
        --secondary-purple: #A855F7;
        --accent-green: #10B981;
        --accent-blue: #3B82F6;
        --accent-orange: #F59E0B;
        --accent-red: #EF4444;
        --accent-pink: #EC4899;
        --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --card-bg: rgba(255, 255, 255, 0.95);
        --text-primary: #1F2937;
        --text-secondary: #6B7280;
        --border-color: rgba(255, 255, 255, 0.2);
        --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.1);
        --shadow-medium: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    body {
        background: var(--bg-gradient);
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .dashboard-layout {
        display: flex;
        min-height: 100vh;
    }

    /* Sidebar moderno */
    .sidebar-modern {
        width: 280px;
        background: var(--card-bg);
        backdrop-filter: blur(20px);
        border-right: 1px solid var(--border-color);
        position: fixed;
        height: 100vh;
        overflow-y: auto;
        z-index: 1000;
    }

    .user-profile {
        padding: 2rem;
        text-align: center;
        border-bottom: 1px solid rgba(139, 92, 246, 0.1);
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: bold;
        margin: 0 auto 1rem;
        box-shadow: var(--shadow-soft);
    }

    .user-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .user-badge {
        background: rgba(139, 92, 246, 0.1);
        color: var(--primary-purple);
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .nav-modern {
        padding: 1rem 0;
    }

    .nav-item {
        margin-bottom: 0.25rem;
    }

    .nav-link-modern {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.875rem 1.5rem;
        color: var(--text-secondary);
        text-decoration: none;
        transition: all 0.2s ease;
        border-radius: 0;
        position: relative;
    }

    .nav-link-modern:hover {
        background: rgba(139, 92, 246, 0.05);
        color: var(--primary-purple);
        transform: translateX(4px);
    }

    .nav-link-modern.active {
        background: rgba(139, 92, 246, 0.1);
        color: var(--primary-purple);
        font-weight: 600;
    }

    .nav-link-modern.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--primary-purple);
    }

    .nav-icon {
        width: 20px;
        text-align: center;
    }

    .nav-separator {
        height: 1px;
        background: rgba(139, 92, 246, 0.1);
        margin: 1rem 1.5rem;
    }

    /* Contenido principal */
    .main-content {
        flex: 1;
        margin-left: 280px;
        padding: 2rem;
    }

    .welcome-header {
        background: var(--card-bg);
        border-radius: 24px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--border-color);
        backdrop-filter: blur(20px);
    }

    .welcome-content {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .welcome-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--accent-blue), var(--primary-purple));
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }

    .welcome-text h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .welcome-text p {
        color: var(--text-secondary);
        margin: 0;
        font-size: 1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--card-bg);
        border-radius: 20px;
        padding: 1.75rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--border-color);
        backdrop-filter: blur(20px);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-medium);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        border-radius: 20px 20px 0 0;
    }

    .stat-card:nth-child(1)::before {
        background: var(--accent-green);
    }

    .stat-card:nth-child(2)::before {
        background: var(--accent-blue);
    }

    .stat-card:nth-child(3)::before {
        background: var(--accent-orange);
    }

    .stat-card:nth-child(4)::before {
        background: var(--accent-red);
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .stat-label {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-secondary);
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-change {
        font-size: 0.8rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        color: var(--text-secondary);
    }

    .content-section {
        background: var(--card-bg);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--border-color);
        backdrop-filter: blur(20px);
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: between;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(139, 92, 246, 0.1);
    }

    .section-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .view-all-btn {
        background: rgba(139, 92, 246, 0.1);
        color: var(--primary-purple);
        border: 1px solid rgba(139, 92, 246, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .view-all-btn:hover {
        background: var(--primary-purple);
        color: white;
        transform: translateY(-1px);
    }

    .promocion-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(139, 92, 246, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .promocion-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
    }

    .promocion-card.inicial::before {
        background: var(--accent-green);
    }

    .promocion-card.medium::before {
        background: var(--accent-orange);
    }

    .promocion-card.premium::before {
        background: var(--primary-purple);
    }

    .promocion-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .promocion-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .promocion-info h6 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 0.25rem 0;
    }

    .promocion-info p {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .category-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .category-inicial {
        background: rgba(16, 185, 129, 0.1);
        color: var(--accent-green);
    }

    .category-medium {
        background: rgba(245, 158, 11, 0.1);
        color: var(--accent-orange);
    }

    .category-premium {
        background: rgba(139, 92, 246, 0.1);
        color: var(--primary-purple);
    }

    .promocion-text {
        background: rgba(139, 92, 246, 0.03);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
        font-size: 0.9rem;
        line-height: 1.4;
        color: var(--text-primary);
    }

    .promocion-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .btn-action {
        padding: 0.625rem 1.25rem;
        border: none;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
        color: white;
    }

    .estado-badge {
        padding: 0.25rem 0.6rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .estado-aceptada {
        background: rgba(16, 185, 129, 0.1);
        color: var(--accent-green);
    }

    .estado-enviada {
        background: rgba(245, 158, 11, 0.1);
        color: var(--accent-orange);
    }

    .estado-rechazada {
        background: rgba(239, 68, 68, 0.1);
        color: var(--accent-red);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state h4 {
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    @media (max-width: 768px) {
        .sidebar-modern {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .sidebar-modern.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
            padding: 1rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .welcome-content {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<div class="dashboard-layout">
    <!-- Sidebar -->
    <nav class="sidebar-modern">
        <div class="user-profile">
            <div class="user-avatar">
                <?= strtoupper(substr($auth->getCurrentUser()['email'], 0, 1)) ?>
            </div>
            <div class="user-name"><?= htmlspecialchars($auth->getCurrentUser()['email']) ?></div>
            <span class="user-badge"><?= htmlspecialchars($auth->getCurrentUser()['type']) ?></span>
        </div>

        <nav class="nav-modern">
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li class="nav-item">
                    <a class="nav-link-modern active" href="panel.php">
                        <i class="fas fa-home nav-icon"></i>
                        <span>Inicio</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link-modern" href="mis_promociones.php">
                        <i class="fas fa-tags nav-icon"></i>
                        <span>Mis Promociones</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link-modern" href="#">
                        <i class="fas fa-history nav-icon"></i>
                        <span>Historial</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link-modern" href="#">
                        <i class="fas fa-bullhorn nav-icon"></i>
                        <span>Novedades</span>
                    </a>
                </li>

                <div class="nav-separator"></div>

                <li class="nav-item">
                    <a class="nav-link-modern" href="<?= SITE_URL ?>index.php">
                        <i class="fas fa-globe nav-icon"></i>
                        <span>Volver al sitio</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link-modern" href="<?= SITE_URL ?>logout.php">
                        <i class="fas fa-sign-out-alt nav-icon"></i>
                        <span>Cerrar Sesión</span>
                    </a>
                </li>
            </ul>
        </nav>
    </nav>

    <!-- Contenido principal -->
    <main class="main-content">
        <!-- Header de bienvenida -->
        <div class="welcome-header">
            <div class="welcome-content">
                <div class="welcome-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="welcome-text">
                    <h1>¡Hola, <?= htmlspecialchars(explode('@', $auth->getCurrentUser()['email'])[0]) ?>!</h1>
                    <p>Descubre las mejores promociones y descuentos en tus tiendas favoritas</p>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-label">Descuentos Obtenidos</div>
                    <div class="stat-icon" style="background: var(--accent-green);">
                        <i class="fas fa-percentage"></i>
                    </div>
                </div>
                <div class="stat-number"><?= $stats['descuentos_obtenidos'] ?? 0 ?></div>
                <div class="stat-change">
                    <i class="fas fa-check-circle"></i>
                    Aceptados
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-label">Locales Visitados</div>
                    <div class="stat-icon" style="background: var(--accent-blue);">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
                <div class="stat-number"><?= $stats['locales_visitados'] ?? 0 ?></div>
                <div class="stat-change">
                    <i class="fas fa-map-marker-alt"></i>
                    Diferentes
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-label">Promociones Usadas</div>
                    <div class="stat-icon" style="background: var(--accent-orange);">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
                <div class="stat-number"><?= $stats['promociones_usadas'] ?? 0 ?></div>
                <div class="stat-change">
                    <i class="fas fa-chart-line"></i>
                    Total
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-label">Solicitudes Pendientes</div>
                    <div class="stat-icon" style="background: var(--accent-red);">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stat-number"><?= $stats['solicitudes_pendientes'] ?? 0 ?></div>
                <div class="stat-change">
                    <i class="fas fa-hourglass-half"></i>
                    En espera
                </div>
            </div>
        </div>

        <!-- Promociones disponibles -->
        <div class="content-section">
            <div class="section-header">
                <div class="section-info">
                    <div class="section-icon">
                        <i class="fas fa-gift"></i>
                    </div>
                    <h3 class="section-title">Promociones Disponibles</h3>
                </div>
                <a href="promociones_disponibles.php" class="view-all-btn">Ver todas</a>
            </div>

            <?php if (empty($promociones_disponibles)): ?>
                <div class="empty-state">
                    <i class="fas fa-gift"></i>
                    <h4>No hay promociones disponibles</h4>
                    <p>¡Pronto habrá nuevas ofertas para ti!</p>
                </div>
            <?php else: ?>
                <?php foreach ($promociones_disponibles as $promocion): ?>
                    <div class="promocion-card <?= strtolower($promocion['categoriaCliente']) ?>">
                        <div class="promocion-header">
                            <div class="promocion-info">
                                <h6><?= htmlspecialchars($promocion['nombreLocal']) ?></h6>
                                <p>
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= htmlspecialchars($promocion['direccion']) ?>
                                </p>
                            </div>
                            <span class="category-badge category-<?= strtolower($promocion['categoriaCliente']) ?>">
                                <?= $promocion['categoriaCliente'] ?>
                            </span>
                        </div>

                        <div class="promocion-text">
                            <?= htmlspecialchars($promocion['textoPromo']) ?>
                        </div>

                        <div class="promocion-actions">
                            <a href="solicitar_promocion.php?id=<?= $promocion['codPromo'] ?>" class="btn-action btn-primary">
                                <i class="fas fa-paper-plane"></i>
                                Solicitar
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Actividad reciente -->
        <div class="content-section">
            <div class="section-header">
                <div class="section-info">
                    <div class="section-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3 class="section-title">Actividad Reciente</h3>
                </div>
                <a href="historial.php" class="view-all-btn">Ver historial</a>
            </div>

            <?php if (empty($promociones_recientes)): ?>
                <div class="empty-state">
                    <i class="fas fa-history"></i>
                    <h4>Sin actividad reciente</h4>
                    <p>Comienza a usar promociones para ver tu historial aquí</p>
                </div>
            <?php else: ?>
                <?php foreach ($promociones_recientes as $reciente): ?>
                    <div class="promocion-card <?= strtolower($reciente['categoriaCliente']) ?>">
                        <div class="promocion-header">
                            <div class="promocion-info">
                                <h6><?= htmlspecialchars($reciente['nombreLocal']) ?></h6>
                                <p>
                                    <i class="fas fa-calendar"></i>
                                    <?= date('d/m/Y H:i', strtotime($reciente['fechaUsoPromo'])) ?>
                                </p>
                            </div>
                            <span class="estado-badge estado-<?= $reciente['estado'] ?>">
                                <?= ucfirst($reciente['estado']) ?>
                            </span>
                        </div>

                        <div class="promocion-text">
                            <?= htmlspecialchars($reciente['textoPromo']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Animación de números en estadísticas
        const statNumbers = document.querySelectorAll('.stat-number');
        statNumbers.forEach(stat => {
            const finalValue = parseInt(stat.textContent);
            if (!isNaN(finalValue) && finalValue > 0) {
                let currentValue = 0;
                const increment = Math.ceil(finalValue / 30);
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        stat.textContent = finalValue;
                        clearInterval(timer);
                    } else {
                        stat.textContent = currentValue;
                    }
                }, 50);
            }
        });

        // Animación de entrada para las tarjetas
        const cards = document.querySelectorAll('.promocion-card, .stat-card');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                }
            });
        });

        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease';
            observer.observe(card);
        });

        // Sidebar toggle para móvil
        const toggleBtn = document.createElement('button');
        toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
        toggleBtn.className = 'mobile-toggle';
        toggleBtn.style.cssText = `
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 1100;
        background: var(--primary-purple);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.75rem;
        display: none;
        cursor: pointer;
        box-shadow: var(--shadow-soft);
    `;

        document.body.appendChild(toggleBtn);

        // Mostrar botón toggle en móvil
        function checkMobile() {
            if (window.innerWidth <= 768) {
                toggleBtn.style.display = 'block';
            } else {
                toggleBtn.style.display = 'none';
                document.querySelector('.sidebar-modern').classList.remove('show');
            }
        }

        window.addEventListener('resize', checkMobile);
        checkMobile();

        // Toggle sidebar
        toggleBtn.addEventListener('click', () => {
            document.querySelector('.sidebar-modern').classList.toggle('show');
        });

        // Cerrar sidebar al hacer click fuera (móvil)
        document.addEventListener('click', (e) => {
            const sidebar = document.querySelector('.sidebar-modern');
            if (window.innerWidth <= 768 &&
                !sidebar.contains(e.target) &&
                !toggleBtn.contains(e.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        // Efectos hover mejorados
        const hoverCards = document.querySelectorAll('.stat-card, .promocion-card');
        hoverCards.forEach(card => {
            card.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-4px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Auto-refresh para solicitudes pendientes
        const pendingCount = parseInt(document.querySelector('.stat-card:nth-child(4) .stat-number').textContent);
        if (pendingCount > 0) {
            setInterval(() => {
                // Actualizar contador de solicitudes pendientes silenciosamente
                fetch(window.location.href)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const newDoc = parser.parseFromString(html, 'text/html');
                        const newPendingElement = newDoc.querySelector('.stat-card:nth-child(4) .stat-number');
                        const currentPendingElement = document.querySelector('.stat-card:nth-child(4) .stat-number');

                        if (newPendingElement && currentPendingElement) {
                            const newValue = newPendingElement.textContent;
                            const currentValue = currentPendingElement.textContent;

                            if (newValue !== currentValue) {
                                currentPendingElement.textContent = newValue;
                                // Agregar efecto visual de actualización
                                currentPendingElement.style.color = 'var(--accent-green)';
                                setTimeout(() => {
                                    currentPendingElement.style.color = 'var(--text-primary)';
                                }, 2000);
                            }
                        }
                    })
                    .catch(() => {
                        // Silenciar errores de red
                    });
            }, 30000); // Cada 30 segundos
        }

        // Notificaciones toast personalizadas
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast-notification toast-${type}`;
            toast.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
            toast.style.cssText = `
            position: fixed;
            top: 2rem;
            right: 2rem;
            background: var(--card-bg);
            padding: 1rem 1.5rem;
            border-radius: 16px;
            box-shadow: var(--shadow-medium);
            border: 1px solid var(--border-color);
            backdrop-filter: blur(20px);
            z-index: 2000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 350px;
        `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);

            setTimeout(() => {
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }

        // Detectar nuevas promociones disponibles
        const currentPromotionsCount = document.querySelectorAll('.content-section:first-of-type .promocion-card').length;

        setInterval(() => {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const newDoc = parser.parseFromString(html, 'text/html');
                    const newPromotionsCount = newDoc.querySelectorAll('.content-section:first-of-type .promocion-card').length;

                    if (newPromotionsCount > currentPromotionsCount) {
                        showToast('¡Hay nuevas promociones disponibles!', 'success');
                    }
                })
                .catch(() => {
                    // Silenciar errores
                });
        }, 60000); // Cada minuto

        // Lazy loading de imágenes si las hubiera
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    });
</script>

<?php require_once '../includes/footer-panel.php'; ?>