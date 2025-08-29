<?php
require_once 'includes/config.php';
$pageTitle = "Inicio - PromoShopping";
require_once 'includes/header.php';
?>

<!-- SOLO el contenido ÚNICO de la página de inicio -->
<div class="hero-section">
    <div class="container">
        <h1>Descubre las mejores promociones</h1>
        <p>Encuentra ofertas exclusivas en tus locales favoritos del shopping</p>
        <a href="#promociones" class="btn btn-primary btn-lg">Ver promociones →</a>
    </div>
</div>

<section id="promociones" class="py-5">
    <div class="container">
        <h2 class="section-title">Promociones Destacadas</h2>
        <div class="row g-4">
            <!-- Ejemplo de tarjeta de promoción -->
            <div class="col-md-4">
                <div class="promo-card card h-100">
                    <span class="category-badge badge-inicial">Inicial</span>
                    <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-4.0.3"
                        class="card-img-top" alt="Local 1" height="200" style="object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">20% de descuento</h5>
                        <p class="card-text">En toda la colección de verano. Válido hasta el 30/12/2025.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="fas fa-store me-1"></i> Fashion Store</small>
                            <small class="text-muted"><i class="fas fa-calendar me-1"></i> Lunes a Viernes</small>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="#" class="btn btn-primary w-100">Ver detalle</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="promo-card card h-100">
                    <span class="category-badge badge-medium">Medium</span>
                    <img src="https://images.unsplash.com/photo-1605733513597-a8f8341084e6?ixlib=rb-4.0.3"
                        class="card-img-top" alt="Local 2" height="200" style="object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">2x1 en calzado</h5>
                        <p class="card-text">En la segunda unidad de calzado. Válido hasta el 15/01/2026.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="fas fa-store me-1"></i> Shoes & More</small>
                            <small class="text-muted"><i class="fas fa-calendar me-1"></i> Solo Sábados</small>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="#" class="btn btn-primary w-100">Ver detalle</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="promo-card card h-100">
                    <span class="category-badge badge-premium">Premium</span>
                    <img src="https://images.unsplash.com/photo-1556906781-2f0520405b71?ixlib=rb-4.0.3"
                        class="card-img-top" alt="Local 3" height="200" style="object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">30% + 10% off</h5>
                        <p class="card-text">Descuento acumulable con tarjeta. Válido hasta el 20/11/2025.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="fas fa-store me-1"></i> TechWorld</small>
                            <small class="text-muted"><i class="fas fa-calendar me-1"></i> Todos los días</small>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="#" class="btn btn-primary w-100">Ver detalle</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="#" class="btn btn-outline-primary">Ver todas las promociones</a>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center">¿Cómo funciona?</h2>
        <div class="row g-4 mt-3">
            <div class="col-md-4 text-center">
                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 80px; height: 80px;">
                    <i class="fas fa-user-plus fa-2x text-white"></i>
                </div>
                <h4>1. Regístrate</h4>
                <p>Crea tu cuenta y comienza a disfrutar de las promociones exclusivas.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 80px; height: 80px;">
                    <i class="fas fa-search fa-2x text-white"></i>
                </div>
                <h4>2. Encuentra promociones</h4>
                <p>Busca entre cientos de ofertas disponibles en todos los locales.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 80px; height: 80px;">
                    <i class="fas fa-ticket-alt fa-2x text-white"></i>
                </div>
                <h4>3. Disfruta tus beneficios</h4>
                <p>Muestra tu código en el local seleccionado y obtén tu descuento.</p>
            </div>
        </div>
    </div>
</section>

<?php
require_once 'includes/footer.php';
?>