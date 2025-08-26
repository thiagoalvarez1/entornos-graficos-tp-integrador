<?php
session_start();

// Datos de prueba
$promociones = [  
    [
        'textoPromo' => '20% de descuento en efectivo', 
        'nombreLocal' => 'Tienda X', 
        'fechaHastaPromo' => '2025-09-30', 
        'categoriaCliente' => 'Inicial',
        'imagen' => 'https://via.placeholder.com/300x200/3498db/ffffff?text=20%25+OFF'
    ],
    [
        'textoPromo' => '2x1 en productos seleccionados', 
        'nombreLocal' => 'Tienda Y', 
        'fechaHastaPromo' => '2025-10-15', 
        'categoriaCliente' => 'Medium',
        'imagen' => 'https://via.placeholder.com/300x200/e74c3c/ffffff?text=2x1'
    ],
    [
        'textoPromo' => '30% off en segunda unidad', 
        'nombreLocal' => 'Tienda Z', 
        'fechaHastaPromo' => '2025-08-31', 
        'categoriaCliente' => 'Premium',
        'imagen' => 'https://via.placeholder.com/300x200/2ecc71/ffffff?text=30%25+OFF'
    ]
];

require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://via.placeholder.com/1920x600/3498db/ffffff?text=Shopping+Promociones') center/cover; padding: 100px 0; color: white;">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-4">Descubre las Mejores Promociones</h1>
        <p class="lead mb-4">Encuentra descuentos exclusivos en todos los locales de nuestro shopping</p>
        
        <?php if (!isset($_SESSION['usuario'])): ?>
            <div class="d-flex justify-content-center gap-3">
                <a href="register.php" class="btn btn-primary btn-lg px-4">
                    <i class="fas fa-user-plus me-2"></i>Registrarse
                </a>
                <a href="login.php" class="btn btn-outline-light btn-lg px-4">
                    <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                </a>
            </div>
        <?php else: ?>
            <a href="#promociones" class="btn btn-primary btn-lg px-4">
                <i class="fas fa-tags me-2"></i>Ver Promociones
            </a>
        <?php endif; ?>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <h4>Descuentos Exclusivos</h4>
                <p class="text-muted">Promociones especiales según tu categoría de cliente</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-store"></i>
                </div>
                <h4>+50 Locales</h4>
                <p class="text-muted">Variedad de rubros y opciones para todos los gustos</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h4>App Móvil</h4>
                <p class="text-muted">Accede a las promociones desde tu celular</p>
            </div>
        </div>
    </div>
</section>

<!-- Promociones Section -->
<section id="promociones" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Promociones Vigentes</h2>
        
        <!-- Buscador y Filtros -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Buscar promociones..." id="buscadorPromociones">
                </div>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="filtroCategoria">
                    <option value="">Todas las categorías</option>
                    <option value="Inicial">Inicial</option>
                    <option value="Medium">Medium</option>
                    <option value="Premium">Premium</option>
                </select>
            </div>
        </div>

        <!-- Grid de Promociones -->
        <div class="row" id="gridPromociones">
            <?php foreach ($promociones as $index => $promo): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <img src="<?= $promo['imagen'] ?>" class="card-img-top" alt="Promoción" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-<?= 
                                ($promo['categoriaCliente'] == 'Premium') ? 'warning' : 
                                (($promo['categoriaCliente'] == 'Medium') ? 'info' : 'secondary') 
                            ?>">
                                <?= $promo['categoriaCliente'] ?>
                            </span>
                            <small class="text-muted"><?= $promo['nombreLocal'] ?></small>
                        </div>
                        <h5 class="card-title"><?= $promo['textoPromo'] ?></h5>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Válido hasta: <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?>
                            </small>
                        </p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#usoPromoModal<?= $index ?>">
                                <i class="fas fa-ticket-alt me-2"></i>Usar Promoción
                            </button>
                        <?php else: ?>
                            <a href="register.php" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-plus me-2"></i>Registrate para usar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Modales para usar promociones -->
<?php foreach ($promociones as $index => $promo): ?>
<div class="modal fade" id="usoPromoModal<?= $index ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Usar Promoción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Deseas usar la promoción: <strong><?= $promo['textoPromo'] ?></strong>?</p>
                <p>Local: <?= $promo['nombreLocal'] ?></p>
                <p>Esta acción generará un código QR que deberás mostrar en el local.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Generar Código QR</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script>
// Búsqueda y filtrado
document.addEventListener('DOMContentLoaded', function() {
    const buscador = document.getElementById('buscadorPromociones');
    const filtro = document.getElementById('filtroCategoria');
    const grid = document.getElementById('gridPromociones');
    const cards = grid.querySelectorAll('.col-lg-4');

    function filtrarPromociones() {
        const texto = buscador.value.toLowerCase();
        const categoria = filtro.value;
        
        cards.forEach(card => {
            const textoCard = card.textContent.toLowerCase();
            const categoriaCard = card.querySelector('.badge').textContent;
            const localCard = card.querySelector('.text-muted').textContent;
            
            const coincideTexto = textoCard.includes(texto) || localCard.toLowerCase().includes(texto);
            const coincideCategoria = categoria === '' || categoriaCard === categoria;
            
            card.style.display = (coincideTexto && coincideCategoria) ? 'block' : 'none';
        });
    }

    buscador.addEventListener('input', filtrarPromociones);
    filtro.addEventListener('change', filtrarPromociones);
});
</script>

<?php require_once 'includes/footer.php'; ?>