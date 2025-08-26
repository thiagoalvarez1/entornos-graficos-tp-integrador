<?php
session_start();

// Datos de prueba simulados
$promociones = [  
    [
        'id' => 1,
        'textoPromo' => '20% DE DESCUENTO EN EFECTIVO', 
        'nombreLocal' => 'Tienda Fashion', 
        'fechaDesdePromo' => '2025-08-01',
        'fechaHastaPromo' => '2025-09-30', 
        'categoriaCliente' => 'Inicial',
        'imagen' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
        'estado' => 'aprobada',
        'dias' => ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes']
    ],
    [
        'id' => 2,
        'textoPromo' => '2x1 EN PRODUCTOS SELECCIONADOS', 
        'nombreLocal' => 'Calzados Premium', 
        'fechaDesdePromo' => '2025-09-01',
        'fechaHastaPromo' => '2025-10-15', 
        'categoriaCliente' => 'Medium',
        'imagen' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
        'estado' => 'aprobada',
        'dias' => ['Sábado', 'Domingo']
    ],
    [
        'id' => 3,
        'textoPromo' => '30% OFF EN SEGUNDA UNIDAD + ENVÍO GRATIS', 
        'nombreLocal' => 'TecnoShop', 
        'fechaDesdePromo' => '2025-08-15',
        'fechaHastaPromo' => '2025-08-31', 
        'categoriaCliente' => 'Premium',
        'imagen' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80',
        'estado' => 'aprobada',
        'dias' => ['Lunes', 'Miércoles', 'Viernes']
    ]
];

$novedades = [
    [
        'titulo' => '¡NUEVO HORARIO!',
        'texto' => 'Ampliamos nuestro horario de atención. Ahora abrimos de 9:00 a 22:00 hs todos los días.',
        'categoria' => 'todos',
        'fecha' => '2025-08-20'
    ],
    [
        'titulo' => 'PROMO ESPECIAL PREMIUM',
        'texto' => 'Clientes Premium: 40% off en todos los locales este fin de semana exclusivo.',
        'categoria' => 'Premium',
        'fecha' => '2025-08-25'
    ]
];

require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInDown">
                    DESCUBRÍ LAS MEJORES PROMOCIONES
                </h1>
                <p class="lead mb-5 fs-5 animate__animated animate__fadeInUp">
                    Encontrá descuentos exclusivos en +50 locales de nuestro shopping. 
                    Registrate y accedé a ofertas personalizadas según tu categoría.
                </p>
                
                <div class="d-flex justify-content-center gap-3 flex-wrap animate__animated animate__fadeInUp">
                    <?php if (!isset($_SESSION['usuario'])): ?>
                        <a href="register.php" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-user-plus me-2"></i>CREAR CUENTA
                        </a>
                        <a href="login.php" class="btn btn-outline-light btn-lg px-5 py-3">
                            <i class="fas fa-sign-in-alt me-2"></i>INGRESAR
                        </a>
                    <?php else: ?>
                        <a href="#promociones" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-tags me-2"></i>VER PROMOCIONES
                        </a>
                        <a href="mis_promociones.php" class="btn btn-outline-light btn-lg px-5 py-3">
                            <i class="fas fa-ticket-alt me-2"></i>MIS CUPONES
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="feature-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <h4 class="fw-bold mb-3">DESCUENTOS EXCLUSIVOS</h4>
                <p class="text-muted">
                    Promociones especiales adaptadas a tu categoría de cliente. 
                    Cuanto más uses, mejores beneficios obtenés.
                </p>
            </div>
            
            <div class="col-md-4 text-center">
                <div class="feature-icon">
                    <i class="fas fa-store"></i>
                </div>
                <h4 class="fw-bold mb-3">+50 LOCALES</h4>
                <p class="text-muted">
                    Variedad de rubros y opciones para todos los gustos. 
                    Desde indumentaria hasta tecnología y gastronomía.
                </p>
            </div>
            
            <div class="col-md-4 text-center">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h4 class="fw-bold mb-3">APP MÓVIL</h4>
                <p class="text-muted">
                    Accedé a las promociones desde tu celular. 
                    Recibí notificaciones de nuevas ofertas en tiempo real.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Novedades Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">ÚLTIMAS NOVEDADES</h2>
        
        <div class="row g-4">
            <?php foreach ($novedades as $novedad): ?>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-<?= 
                                ($novedad['categoria'] == 'Premium') ? 'warning' : 
                                (($novedad['categoria'] == 'Medium') ? 'info' : 'primary') 
                            ?>">
                                <?= $novedad['categoria'] === 'todos' ? 'PARA TODOS' : 'CLIENTES ' . strtoupper($novedad['categoria']) ?>
                            </span>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                <?= date('d/m/Y', strtotime($novedad['fecha'])) ?>
                            </small>
                        </div>
                        <h5 class="card-title fw-bold"><?= $novedad['titulo'] ?></h5>
                        <p class="card-text"><?= $novedad['texto'] ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Promociones Section -->
<section id="promociones" class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="fw-bold mb-3">PROMOCIONES VIGENTES</h2>
                <p class="text-muted">Encontrá las mejores ofertas disponibles en este momento</p>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" 
                           placeholder="Buscar promociones o locales..." id="buscadorPromociones">
                </div>
            </div>
            <div class="col-lg-4">
                <select class="form-select form-select-lg" id="filtroCategoria">
                    <option value="">TODAS LAS CATEGORÍAS</option>
                    <option value="Inicial">CLIENTES INICIAL</option>
                    <option value="Medium">CLIENTES MEDIUM</option>
                    <option value="Premium">CLIENTES PREMIUM</option>
                </select>
            </div>
        </div>

        <!-- Promotions Grid -->
        <div class="row g-4" id="gridPromociones">
            <?php foreach ($promociones as $index => $promo): ?>
            <div class="col-xl-4 col-lg-6 col-md-6" data-category="<?= $promo['categoriaCliente'] ?>">
                <div class="card h-100 promotion-card">
                    <img src="<?= $promo['imagen'] ?>" class="card-img-top" alt="<?= $promo['nombreLocal'] ?>" 
                         style="height: 220px; object-fit: cover;">
                    
                    <div class="card-body">
                        <!-- Badges -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-<?= 
                                ($promo['categoriaCliente'] == 'Premium') ? 'warning' : 
                                (($promo['categoriaCliente'] == 'Medium') ? 'info' : 'secondary') 
                            ?>">
                                <i class="fas fa-<?= 
                                    ($promo['categoriaCliente'] == 'Premium') ? 'crown' : 
                                    (($promo['categoriaCliente'] == 'Medium') ? 'star' : 'user') 
                                ?> me-1"></i>
                                <?= $promo['categoriaCliente'] ?>
                            </span>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-store me-1"></i>
                                <?= $promo['nombreLocal'] ?>
                            </span>
                        </div>

                        <!-- Promotion Title -->
                        <h5 class="card-title fw-bold text-primary"><?= $promo['textoPromo'] ?></h5>

                        <!-- Dates -->
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Válida: <?= date('d/m/Y', strtotime($promo['fechaDesdePromo'])) ?> - <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?>
                            </small>
                        </div>

                        <!-- Days -->
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Días: <?= implode(', ', $promo['dias']) ?>
                            </small>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <span class="badge bg-<?= $promo['estado'] === 'aprobada' ? 'success' : 'warning' ?>">
                                <i class="fas fa-<?= $promo['estado'] === 'aprobada' ? 'check' : 'clock' ?> me-1"></i>
                                <?= strtoupper($promo['estado']) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <button class="btn btn-primary w-100" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#usoPromoModal<?= $index ?>">
                                <i class="fas fa-ticket-alt me-2"></i>
                                USAR PROMOCIÓN
                            </button>
                        <?php else: ?>
                            <a href="register.php" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-plus me-2"></i>
                                REGISTRATE PARA USAR
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- No Results Message (Hidden by default) -->
        <div id="noResults" class="text-center py-5" style="display: none;">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No se encontraron promociones</h4>
            <p class="text-muted">Intenta con otros términos de búsqueda</p>
        </div>
    </div>
</section>

<!-- Modals for using promotions -->
<?php foreach ($promociones as $index => $promo): ?>
<div class="modal fade" id="usoPromoModal<?= $index ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">CONFIRMAR USO DE PROMOCIÓN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img src="<?= $promo['imagen'] ?>" class="img-fluid rounded" alt="<?= $promo['nombreLocal'] ?>">
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">DETALLES DE LA PROMOCIÓN</h6>
                        <p><strong><?= $promo['textoPromo'] ?></strong></p>
                        <p class="mb-1"><i class="fas fa-store me-2"></i><strong>Local:</strong> <?= $promo['nombreLocal'] ?></p>
                        <p class="mb-1"><i class="fas fa-calendar me-2"></i><strong>Válida hasta:</strong> <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?></p>
                        <p class="mb-1"><i class="fas fa-clock me-2"></i><strong>Días:</strong> <?= implode(', ', $promo['dias']) ?></p>
                        <p class="mb-0"><i class="fas fa-user-tag me-2"></i><strong>Categoría:</strong> <?= $promo['categoriaCliente'] ?></p>
                    </div>
                </div>
                
                <hr>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Al confirmar, se generará un código QR que deberás mostrar en el local para validar tu promoción.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>CANCELAR
                </button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-qrcode me-2"></i>GENERAR CÓDIGO QR
                </button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Statistics Section -->
<section class="py-5 bg-dark text-white">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold">500+</h2>
                    <p class="mb-0">PROMOCIONES MENSUALES</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold">5K+</h2>
                    <p class="mb-0">CLIENTES REGISTRADOS</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold">95%</h2>
                    <p class="mb-0">SATISFACCIÓN DEL CLIENTE</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold">24/7</h2>
                    <p class="mb-0">SOPORTE ACTIVO</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Enhanced search and filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const buscador = document.getElementById('buscadorPromociones');
    const filtro = document.getElementById('filtroCategoria');
    const grid = document.getElementById('gridPromociones');
    const noResults = document.getElementById('noResults');
    const cards = grid.querySelectorAll('.col-xl-4');

    function filtrarPromociones() {
        const texto = buscador.value.toLowerCase();
        const categoria = filtro.value;
        let visibleCount = 0;

        cards.forEach(card => {
            const textoCard = card.textContent.toLowerCase();
            const localCard = card.querySelector('.badge.text-dark').textContent;
            const categoriaCard = card.getAttribute('data-category');
            
            const coincideTexto = textoCard.includes(texto) || localCard.toLowerCase().includes(texto);
            const coincideCategoria = categoria === '' || categoriaCard === categoria;
            
            if (coincideTexto && coincideCategoria) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show/hide no results message
        if (visibleCount === 0) {
            noResults.style.display = 'block';
            grid.style.display = 'none';
        } else {
            noResults.style.display = 'none';
            grid.style.display = 'grid';
        }
    }

    buscador.addEventListener('input', filtrarPromociones);
    filtro.addEventListener('change', filtrarPromociones);
    
    // Initial filter
    filtrarPromociones();
});
</script>

<?php require_once 'includes/footer.php'; ?>