<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
$pageTitle = "Inicio - Bandera";
require_once 'includes/header.php';

// Obtener promociones destacadas
$database = new Database();
$conn = $database->getConnection();

$query_promociones = "SELECT 
    p.codPromo,
    p.textoPromo,
    p.categoriaCliente,
    p.fechaDesdePromo,
    p.fechaHastaPromo,
    p.diasSemana,
    l.nombreLocal,
    l.ubicacionLocal,
    l.rubroLocal,
    u.nombreUsuario as nombreDueno
    FROM promociones p
    JOIN locales l ON p.codLocal = l.codLocal
    JOIN usuarios u ON l.codUsuario = u.codUsuario
    WHERE p.estadoPromo = 'aprobada' 
    AND p.fechaHastaPromo >= CURDATE()
    ORDER BY p.fechaCreacion DESC
    LIMIT 6";

$stmt_promociones = $conn->prepare($query_promociones);
$stmt_promociones->execute();
$promociones_destacadas = $stmt_promociones->fetchAll(PDO::FETCH_ASSOC);

// Obtener novedades activas
$query_novedades = "SELECT 
    textoNovedad, 
    tipoUsuario, 
    fechaHastaNovedad 
    FROM novedades 
    WHERE fechaHastaNovedad >= CURDATE() 
    AND fechaDesdeNovedad <= CURDATE()
    ORDER BY fechaCreacion DESC";

$stmt_novedades = $conn->prepare($query_novedades);
$stmt_novedades->execute();
$novedades_activas = $stmt_novedades->fetchAll(PDO::FETCH_ASSOC);

// Estadísticas
$total_promociones = count($promociones_destacadas) > 0 ? count($promociones_destacadas) * 50 : 300;
?>

<link rel="stylesheet" href="css/home-styles.css">

<!-- HERO SECTION -->
<section class="hero-section">
    <div class="hero-bg"></div>
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <span class="hero-badge animate-fade-up">
                        ✨ Nuevas ofertas cada día
                    </span>
                    <h1 class="hero-title animate-fade-up" style="animation-delay: 0.1s;">
                        Descubre las mejores
                        <span class="text-gradient">promociones</span>
                    </h1>
                    <p class="hero-subtitle animate-fade-up" style="animation-delay: 0.2s;">
                        Encuentra ofertas exclusivas en tus locales favoritos del shopping.
                        Más de <?= $total_promociones ?>+ promociones activas esperándote.
                    </p>
                    <div class="hero-actions animate-fade-up" style="animation-delay: 0.3s;">
                        <a href="#promociones" class="btn-hero btn-primary">
                            <i class="fas fa-rocket"></i>Ver promociones
                        </a>
                        <a href="#como-funciona" class="btn-hero btn-outline">
                            <i class="fas fa-play-circle"></i>Cómo funciona
                        </a>
                    </div>
                    <div class="hero-stats animate-fade-up" style="animation-delay: 0.4s;">
                        <div class="stat-item">
                            <div class="stat-number"><?= $total_promociones ?>+</div>
                            <div class="stat-label">Promociones</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">50+</div>
                            <div class="stat-label">Locales</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">50K+</div>
                            <div class="stat-label">Usuarios</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="floating-cards">
                    <?php if (!empty($promociones_destacadas)): ?>
                        <?php foreach (array_slice($promociones_destacadas, 0, 3) as $index => $promo): ?>
                            <div class="floating-card">
                                <div class="card-icon">
                                    <?php
                                    $iconos = ['🛍️', '🎯', '⚡'];
                                    echo $iconos[$index] ?? '🎁';
                                    ?>
                                </div>
                                <div class="card-content">
                                    <h4><?= htmlspecialchars($promo['categoriaCliente']) ?></h4>
                                    <p><?= htmlspecialchars($promo['nombreLocal']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="floating-card">
                            <div class="card-icon">🛍️</div>
                            <div class="card-content">
                                <h4>Premium</h4>
                                <p>Fashion Store</p>
                            </div>
                        </div>
                        <div class="floating-card">
                            <div class="card-icon">🎯</div>
                            <div class="card-content">
                                <h4>Medium</h4>
                                <p>Tech World</p>
                            </div>
                        </div>
                        <div class="floating-card">
                            <div class="card-icon">⚡</div>
                            <div class="card-content">
                                <h4>Inicial</h4>
                                <p>Food Court</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-wave">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V120Z"
                fill="white" />
        </svg>
    </div>
</section>

<!-- NOVEDADES SECTION -->
<?php if (!empty($novedades_activas)): ?>
    <section class="novedades-section">
        <div class="container">
            <div class="novedades-container">
                <div class="novedades-header">
                    <div class="novedades-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h3 class="novedades-title">Novedades Importantes</h3>
                </div>
                <div class="novedades-slider">
                    <?php foreach ($novedades_activas as $novedad):
                        $tipo_usuario = $novedad['tipoUsuario'];
                        $badge_class = match ($tipo_usuario) {
                            'todos' => 'badge-primary',
                            'cliente' => 'badge-success',
                            'dueño de local' => 'badge-warning',
                            'administrador' => 'badge-danger',
                            default => 'badge-primary'
                        };
                        ?>
                        <div class="novedad-item">
                            <div class="novedad-text">
                                <?= htmlspecialchars($novedad['textoNovedad']) ?>
                            </div>
                            <div class="novedad-meta">
                                <span class="novedad-badge <?= $badge_class ?>">
                                    <?= $tipo_usuario == 'todos' ? 'Para todos' : 'Para ' . $tipo_usuario ?>
                                </span>
                                <span class="novedad-fecha">
                                    <i class="fas fa-calendar-alt"></i>
                                    Hasta <?= date('d/m/Y', strtotime($novedad['fechaHastaNovedad'])) ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- PROMOCIONES DESTACADAS -->
<section id="promociones" class="promociones-section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">🔥 Lo más popular</span>
            <h2 class="section-title">Promociones Destacadas</h2>
            <p class="section-subtitle">Las mejores ofertas seleccionadas especialmente para ti</p>
        </div>

        <div class="row g-4">
            <?php if (!empty($promociones_destacadas)): ?>
                <?php foreach ($promociones_destacadas as $index => $promo):
                    $categoria = $promo['categoriaCliente'];
                    $card_class = match ($categoria) {
                        'Premium' => 'card-premium',
                        'Medium' => 'card-medium',
                        default => 'card-inicial'
                    };
                    $badge_class = match ($categoria) {
                        'Premium' => 'badge-premium',
                        'Medium' => 'badge-medium',
                        default => 'badge-inicial'
                    };
                    $discount_class = match ($categoria) {
                        'Premium' => '',
                        'Medium' => 'discount-badge-medium',
                        default => 'discount-badge-inicial'
                    };

                    // Icono según rubro
                    $icono = 'fas fa-store';
                    $rubro = strtolower($promo['rubroLocal']);
                    if (str_contains($rubro, 'tech') || str_contains($rubro, 'tecnolog')) {
                        $icono = 'fas fa-laptop';
                    } elseif (str_contains($rubro, 'calzado') || str_contains($rubro, 'shoe')) {
                        $icono = 'fas fa-shoe-prints';
                    } elseif (str_contains($rubro, 'moda') || str_contains($rubro, 'ropa') || str_contains($rubro, 'fashion')) {
                        $icono = 'fas fa-tshirt';
                    } elseif (str_contains($rubro, 'comida') || str_contains($rubro, 'food') || str_contains($rubro, 'restaurant')) {
                        $icono = 'fas fa-utensils';
                    }
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="promo-card <?= $card_class ?>" data-aos="fade-up"
                            data-aos-delay="<?= ($index + 1) * 100 ?>">
                            <span class="category-badge <?= $badge_class ?>">
                                <i
                                    class="fas <?= $categoria == 'Premium' ? 'fa-crown' : ($categoria == 'Medium' ? 'fa-gem' : 'fa-star') ?>"></i>
                                <?= $categoria ?>
                            </span>
                            <div class="card-image">
                                <img src="https://via.placeholder.com/400x200/6366f1/ffffff?text=<?= urlencode($promo['nombreLocal']) ?>"
                                    alt="<?= htmlspecialchars($promo['nombreLocal']) ?>">
                                <div class="discount-badge <?= $discount_class ?>">
                                    <span class="discount-text"><?= $categoria ?></span>
                                    <span class="discount-label">OFERTA</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="store-info">
                                    <div class="store-avatar">
                                        <i class="<?= $icono ?>"></i>
                                    </div>
                                    <div class="store-details">
                                        <h5 class="store-name"><?= htmlspecialchars($promo['nombreLocal']) ?></h5>
                                        <span class="store-category"><?= htmlspecialchars($promo['rubroLocal']) ?></span>
                                    </div>
                                    <div class="store-rating">
                                        <i class="fas fa-star"></i>
                                        <span>4.<?= rand(5, 9) ?></span>
                                    </div>
                                </div>
                                <h4 class="promo-title"><?= htmlspecialchars($promo['textoPromo']) ?></h4>
                                <p class="promo-description">
                                    Promoción exclusiva disponible por tiempo limitado. No acumulable con otras ofertas.
                                </p>
                                <div class="promo-details">
                                    <div class="detail-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>Hasta <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-clock"></i>
                                        <span><?= htmlspecialchars($promo['diasSemana']) ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?= htmlspecialchars($promo['ubicacionLocal']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'cliente'): ?>
                                    <a href="cliente/solicitar_promocion.php?id=<?= $promo['codPromo'] ?>" class="btn-claim">
                                        <span>Obtener promoción</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="login.php" class="btn-claim">
                                        <span>Iniciar sesión</span>
                                        <i class="fas fa-sign-in-alt"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Tarjeta ejemplo si no hay promociones -->
                <div class="col-lg-4 col-md-6">
                    <div class="promo-card card-premium">
                        <span class="category-badge badge-premium">
                            <i class="fas fa-crown"></i> Premium
                        </span>
                        <div class="card-image">
                            <img src="https://via.placeholder.com/400x200/6366f1/ffffff?text=Ejemplo" alt="Ejemplo">
                            <div class="discount-badge">
                                <span class="discount-text">30% OFF</span>
                                <span class="discount-label">OFERTA</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="store-info">
                                <div class="store-avatar">
                                    <i class="fas fa-store"></i>
                                </div>
                                <div class="store-details">
                                    <h5 class="store-name">Nombre del Local</h5>
                                    <span class="store-category">Categoría</span>
                                </div>
                                <div class="store-rating">
                                    <i class="fas fa-star"></i>
                                    <span>4.8</span>
                                </div>
                            </div>
                            <h4 class="promo-title">Título de la promoción</h4>
                            <p class="promo-description">Descripción de la promoción disponible.</p>
                            <div class="promo-details">
                                <div class="detail-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Hasta 31/12/2025</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="login.php" class="btn-claim">
                                <span>Iniciar sesión</span>
                                <i class="fas fa-sign-in-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-5">
            <a href="promociones.php" class="btn-view-all">
                <i class="fas fa-grid-3x3-gap"></i>
                Ver todas las promociones
            </a>
        </div>
    </div>
</section>

<!-- CÓMO FUNCIONA -->
<section id="como-funciona" class="how-it-works">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">📱 Súper fácil</span>
            <h2 class="section-title">¿Cómo funciona?</h2>
            <p class="section-subtitle">En solo 3 pasos simples comenzarás a ahorrar</p>
        </div>

        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="steps-container">
                    <div class="step" data-aos="fade-right" data-aos-delay="100">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <div class="step-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h4>Regístrate gratis</h4>
                            <p>Crea tu cuenta en menos de 2 minutos y accede a promociones exclusivas.</p>
                        </div>
                    </div>

                    <div class="step" data-aos="fade-right" data-aos-delay="200">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <div class="step-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h4>Encuentra tu promoción</h4>
                            <p>Busca entre cientos de ofertas de tus locales favoritos del shopping.</p>
                        </div>
                    </div>

                    <div class="step" data-aos="fade-right" data-aos-delay="300">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <div class="step-icon">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <h4>Disfruta tu beneficio</h4>
                            <p>Presenta tu código QR en el local y obtén tu descuento al instante.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left">
                <div class="phone-mockup">
                    <div class="phone-screen">
                        <div class="app-interface">
                            <div class="app-header">
                                <div class="app-title">Bandera</div>
                                <div class="notification-dot"></div>
                            </div>
                            <div class="search-bar">
                                <i class="fas fa-search"></i>
                                <span>Buscar promociones...</span>
                            </div>
                            <div class="mini-cards">
                                <div class="mini-card active">
                                    <span class="mini-discount">30% OFF</span>
                                    <span class="mini-store">TechWorld</span>
                                </div>
                                <div class="mini-card">
                                    <span class="mini-discount">2x1</span>
                                    <span class="mini-store">Fashion Store</span>
                                </div>
                                <div class="mini-card">
                                    <span class="mini-discount">20% OFF</span>
                                    <span class="mini-store">Food Court</span>
                                </div>
                            </div>
                            <div class="qr-preview">
                                <div class="qr-box"></div>
                                <p>Tu código QR</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA FINAL -->
<section class="cta-section">
    <div class="container">
        <div class="cta-container" data-aos="zoom-in">
            <div class="cta-emoji">🎉</div>
            <h2>¡No te pierdas ninguna promoción!</h2>
            <p>Únete a más de 50,000 usuarios que ya están ahorrando con Bandera</p>
            <div class="cta-actions">
                <a href="registro.php" class="btn-white">
                    <i class="fas fa-user-plus"></i>Registrarme gratis
                </a>
                <a href="#" class="btn-outline-white">
                    <i class="fas fa-mobile-alt"></i>Descargar app
                </a>
            </div>
        </div>
    </div>
</section>

<script>
    // Auto-scroll novedades
    document.addEventListener('DOMContentLoaded', function () {
        const slider = document.querySelector('.novedades-slider');
        if (slider) {
            let scrollPos = 0;
            const scrollSpeed = 0.3;

            function autoScroll() {
                if (scrollPos >= slider.scrollHeight - slider.clientHeight) {
                    scrollPos = 0;
                } else {
                    scrollPos += scrollSpeed;
                }
                slider.scrollTop = scrollPos;
                requestAnimationFrame(autoScroll);
            }

            setTimeout(autoScroll, 3000);
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>