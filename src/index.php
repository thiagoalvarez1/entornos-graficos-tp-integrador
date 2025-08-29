<?php
require_once 'includes/config.php';
$pageTitle = "Inicio - PromoShopping";
require_once 'includes/header.php';
?>

<!-- Hero Section Mejorado -->
<div class="hero-section position-relative overflow-hidden">
    <div class="hero-bg-gradient"></div>
    <div class="hero-particles"></div>
    <div class="container position-relative">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-6">
                <div class="hero-content">
                    <span class="hero-badge animate-fade-up">✨ Nuevas ofertas cada día</span>
                    <h1 class="hero-title animate-fade-up" style="animation-delay: 0.1s;">
                        Descubre las mejores
                        <span class="text-gradient">promociones</span>
                    </h1>
                    <p class="hero-subtitle animate-fade-up" style="animation-delay: 0.2s;">
                        Encuentra ofertas exclusivas en tus locales favoritos del shopping.
                        Más de 500 promociones activas esperándote.
                    </p>
                    <div class="hero-actions animate-fade-up" style="animation-delay: 0.3s;">
                        <a href="#promociones" class="btn btn-primary btn-hero me-3">
                            <i class="fas fa-rocket me-2"></i>Ver promociones
                        </a>
                        <a href="#como-funciona" class="btn btn-outline-light btn-hero">
                            <i class="fas fa-play-circle me-2"></i>Cómo funciona
                        </a>
                    </div>
                    <div class="hero-stats mt-4 animate-fade-up" style="animation-delay: 0.4s;">
                        <div class="row">
                            <div class="col-4">
                                <div class="stat-item">
                                    <h3 class="stat-number">500+</h3>
                                    <span class="stat-label">Promociones</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <h3 class="stat-number">150+</h3>
                                    <span class="stat-label">Locales</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <h3 class="stat-number">50K+</h3>
                                    <span class="stat-label">Usuarios</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual animate-fade-left">
                    <div class="floating-cards">
                        <div class="floating-card card-1">
                            <div class="card-icon">🛍️</div>
                            <div class="card-content">
                                <h4>50% OFF</h4>
                                <p>Fashion Store</p>
                            </div>
                        </div>
                        <div class="floating-card card-2">
                            <div class="card-icon">🎯</div>
                            <div class="card-content">
                                <h4>2x1</h4>
                                <p>Tech World</p>
                            </div>
                        </div>
                        <div class="floating-card card-3">
                            <div class="card-icon">⚡</div>
                            <div class="card-content">
                                <h4>30% OFF</h4>
                                <p>Food Court</p>
                            </div>
                        </div>
                    </div>
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
</div>

<!-- Promociones Destacadas -->
<section id="promociones" class="py-5 promociones-section">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">🔥 Lo más popular</span>
            <h2 class="section-title">Promociones Destacadas</h2>
            <p class="section-subtitle">Las mejores ofertas seleccionadas especialmente para ti</p>
        </div>

        <div class="row g-4">
            <!-- Tarjeta Premium -->
            <div class="col-lg-4 col-md-6">
                <div class="promo-card-modern card-premium" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-glow"></div>
                    <div class="category-badge-modern badge-premium">
                        <i class="fas fa-crown"></i> Premium
                    </div>
                    <div class="card-image-container">
                        <img src="https://images.unsplash.com/photo-1556906781-2f0520405b71?ixlib=rb-4.0.3&w=500"
                            class="card-img-modern" alt="TechWorld">
                        <div class="card-overlay">
                            <div class="discount-bubble">
                                <span class="discount-text">30%+10%</span>
                                <span class="discount-label">OFF</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-content-modern">
                        <div class="store-info mb-3">
                            <div class="store-avatar">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <div class="store-details">
                                <h5 class="store-name">TechWorld</h5>
                                <span class="store-category">Tecnología</span>
                            </div>
                            <div class="store-rating">
                                <i class="fas fa-star"></i>
                                <span>4.9</span>
                            </div>
                        </div>
                        <h4 class="promo-title">30% + 10% off acumulable</h4>
                        <p class="promo-description">Descuento especial con tarjeta de crédito. Válido en toda la
                            tienda.</p>
                        <div class="promo-details">
                            <div class="detail-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Hasta 20/11/2025</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-clock"></i>
                                <span>Todos los días</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="btn-claim-modern">
                            <span>Obtener promoción</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Medium -->
            <div class="col-lg-4 col-md-6">
                <div class="promo-card-modern card-medium" data-aos="fade-up" data-aos-delay="200">
                    <div class="category-badge-modern badge-medium">
                        <i class="fas fa-gem"></i> Medium
                    </div>
                    <div class="card-image-container">
                        <img src="https://images.unsplash.com/photo-1605733513597-a8f8341084e6?ixlib=rb-4.0.3&w=500"
                            class="card-img-modern" alt="Shoes & More">
                        <div class="card-overlay">
                            <div class="discount-bubble discount-bubble-medium">
                                <span class="discount-text">2x1</span>
                                <span class="discount-label">OFERTA</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-content-modern">
                        <div class="store-info mb-3">
                            <div class="store-avatar">
                                <i class="fas fa-shoe-prints"></i>
                            </div>
                            <div class="store-details">
                                <h5 class="store-name">Shoes & More</h5>
                                <span class="store-category">Calzado</span>
                            </div>
                            <div class="store-rating">
                                <i class="fas fa-star"></i>
                                <span>4.7</span>
                            </div>
                        </div>
                        <h4 class="promo-title">2x1 en calzado seleccionado</h4>
                        <p class="promo-description">Segunda unidad sin costo en calzado seleccionado. No acumulable.
                        </p>
                        <div class="promo-details">
                            <div class="detail-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Hasta 15/01/2026</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-clock"></i>
                                <span>Solo Sábados</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="btn-claim-modern">
                            <span>Obtener promoción</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Inicial -->
            <div class="col-lg-4 col-md-6">
                <div class="promo-card-modern card-inicial" data-aos="fade-up" data-aos-delay="300">
                    <div class="category-badge-modern badge-inicial">
                        <i class="fas fa-star"></i> Inicial
                    </div>
                    <div class="card-image-container">
                        <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-4.0.3&w=500"
                            class="card-img-modern" alt="Fashion Store">
                        <div class="card-overlay">
                            <div class="discount-bubble discount-bubble-inicial">
                                <span class="discount-text">20%</span>
                                <span class="discount-label">OFF</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-content-modern">
                        <div class="store-info mb-3">
                            <div class="store-avatar">
                                <i class="fas fa-tshirt"></i>
                            </div>
                            <div class="store-details">
                                <h5 class="store-name">Fashion Store</h5>
                                <span class="store-category">Moda</span>
                            </div>
                            <div class="store-rating">
                                <i class="fas fa-star"></i>
                                <span>4.5</span>
                            </div>
                        </div>
                        <h4 class="promo-title">20% en colección verano</h4>
                        <p class="promo-description">Descuento en toda la colección de verano. Encuentra tu estilo
                            ideal.</p>
                        <div class="promo-details">
                            <div class="detail-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Hasta 30/12/2025</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-clock"></i>
                                <span>Lunes a Viernes</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="btn-claim-modern">
                            <span>Obtener promoción</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="#" class="btn btn-primary-modern btn-lg">
                <i class="fas fa-grid-3x3-gap me-2"></i>
                Ver todas las promociones
                <span class="btn-shine"></span>
            </a>
        </div>
    </div>
</section>

<!-- Cómo funciona -->
<section id="como-funciona" class="py-5 how-it-works-section">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">📱 Súper fácil</span>
            <h2 class="section-title">¿Cómo funciona?</h2>
            <p class="section-subtitle">En solo 3 pasos simples comenzarás a ahorrar</p>
        </div>

        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="steps-container">
                    <div class="step-item" data-aos="fade-right" data-aos-delay="100">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <div class="step-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h4>Regístrate gratis</h4>
                            <p>Crea tu cuenta en menos de 2 minutos y accede a promociones exclusivas.</p>
                        </div>
                    </div>

                    <div class="step-item" data-aos="fade-right" data-aos-delay="200">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <div class="step-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h4>Encuentra tu promoción</h4>
                            <p>Busca entre cientos de ofertas de tus locales favoritos del shopping.</p>
                        </div>
                    </div>

                    <div class="step-item" data-aos="fade-right" data-aos-delay="300">
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

            <div class="col-lg-6">
                <div class="mockup-container" data-aos="fade-left">
                    <div class="phone-mockup">
                        <div class="phone-screen">
                            <div class="app-interface">
                                <div class="app-header">
                                    <div class="app-title">PromoShopping</div>
                                    <div class="notification-dot"></div>
                                </div>
                                <div class="search-bar">
                                    <i class="fas fa-search"></i>
                                    <span>Buscar promociones...</span>
                                </div>
                                <div class="promo-mini-cards">
                                    <div class="mini-card active">
                                        <div class="mini-discount">30% OFF</div>
                                        <div class="mini-store">TechWorld</div>
                                    </div>
                                    <div class="mini-card">
                                        <div class="mini-discount">2x1</div>
                                        <div class="mini-store">Shoes & More</div>
                                    </div>
                                    <div class="mini-card">
                                        <div class="mini-discount">20% OFF</div>
                                        <div class="mini-store">Fashion Store</div>
                                    </div>
                                </div>
                                <div class="qr-code-preview">
                                    <div class="qr-placeholder"></div>
                                    <p>Tu código QR</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Final -->
<section class="cta-section py-5">
    <div class="container">
        <div class="cta-container" data-aos="zoom-in">
            <div class="cta-content text-center">
                <div class="cta-emoji">🎉</div>
                <h2>¡No te pierdas ninguna promoción!</h2>
                <p>Únete a más de 50,000 usuarios que ya están ahorrando con PromoShopping</p>
                <div class="cta-actions">
                    <a href="#" class="btn btn-white btn-lg me-3">
                        <i class="fas fa-user-plus me-2"></i>Registrarme gratis
                    </a>
                    <a href="#" class="btn btn-outline-white btn-lg">
                        <i class="fas fa-mobile-alt me-2"></i>Descargar app
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Variables CSS */
    :root {
        --primary-color: #6366f1;
        --primary-dark: #4f46e5;
        --secondary-color: #ec4899;
        --accent-color: #f59e0b;
        --success-color: #10b981;
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --bg-light: #f8fafc;
        --white: #ffffff;
        --border-radius: 16px;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    }

    /* Animaciones */
    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeLeft {
        from {
            opacity: 0;
            transform: translateX(30px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.8;
        }
    }

    @keyframes shine {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(100%);
        }
    }

    .animate-fade-up {
        animation: fadeUp 0.6s ease-out forwards;
    }

    .animate-fade-left {
        animation: fadeLeft 0.8s ease-out forwards;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 50%, var(--secondary-color) 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .hero-bg-gradient {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    }

    .hero-particles {
        position: absolute;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="1" fill="%23ffffff" opacity="0.3"><animate attributeName="opacity" values="0.3;1;0.3" dur="2s" repeatCount="indefinite"/></circle><circle cx="80" cy="40" r="1.5" fill="%23ffffff" opacity="0.2"><animate attributeName="opacity" values="0.2;0.8;0.2" dur="3s" repeatCount="indefinite"/></circle><circle cx="40" cy="70" r="1" fill="%23ffffff" opacity="0.4"><animate attributeName="opacity" values="0.4;1;0.4" dur="2.5s" repeatCount="indefinite"/></circle></svg>') repeat;
        animation: float 6s ease-in-out infinite;
    }

    .min-vh-75 {
        min-height: 75vh;
    }

    .hero-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 1.5rem;
    }

    .text-gradient {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        opacity: 0.9;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .btn-hero {
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-hero:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .hero-stats .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
        color: var(--accent-color);
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    /* Floating Cards */
    .floating-cards {
        position: relative;
        height: 400px;
    }

    .floating-card {
        position: absolute;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--shadow-xl);
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: float 3s ease-in-out infinite;
        color: var(--text-primary);
    }

    .floating-card.card-1 {
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }

    .floating-card.card-2 {
        top: 50%;
        right: 10%;
        animation-delay: -1s;
    }

    .floating-card.card-3 {
        bottom: 20%;
        left: 20%;
        animation-delay: -2s;
    }

    .card-icon {
        font-size: 2rem;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .floating-card h4 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .floating-card p {
        color: var(--text-secondary);
        margin: 0;
        font-size: 0.9rem;
    }

    /* Wave */
    .hero-wave {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        overflow: hidden;
        line-height: 0;
    }

    .hero-wave svg {
        position: relative;
        display: block;
        width: calc(100% + 1.3px);
        height: 60px;
    }

    /* Section Headers */
    .section-header {
        max-width: 600px;
        margin: 0 auto;
    }

    .section-badge {
        display: inline-block;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .section-subtitle {
        font-size: 1.1rem;
        color: var(--text-secondary);
    }

    /* Modern Promo Cards */
    .promo-card-modern {
        position: relative;
        background: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e5e7eb;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .promo-card-modern:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
    }

    .card-premium {
        border: 2px solid var(--primary-color);
    }

    .card-premium .card-glow {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .card-premium:hover .card-glow {
        opacity: 0.05;
    }

    .category-badge-modern {
        position: absolute;
        top: 1rem;
        left: 1rem;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 10;
        color: white;
    }

    .badge-premium {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    }

    .badge-medium {
        background: linear-gradient(135deg, var(--secondary-color), #be185d);
    }

    .badge-inicial {
        background: linear-gradient(135deg, var(--accent-color), #d97706);
    }

    .card-image-container {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .card-img-modern {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .promo-card-modern:hover .card-img-modern {
        transform: scale(1.05);
    }

    .card-overlay {
        position: absolute;
        top: 0;
        right: 0;
        padding: 1rem;
    }

    .discount-bubble {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 12px 16px;
        border-radius: 12px;
        text-align: center;
        min-width: 80px;
        box-shadow: var(--shadow-lg);
    }

    .discount-bubble-medium {
        background: linear-gradient(135deg, var(--secondary-color), #be185d);
    }

    .discount-bubble-inicial {
        background: linear-gradient(135deg, var(--accent-color), #d97706);
    }

    .discount-text {
        display: block;
        font-size: 1.25rem;
        font-weight: 800;
        line-height: 1;
    }

    .discount-label {
        font-size: 0.7rem;
        font-weight: 600;
        opacity: 0.9;
    }

    .card-content-modern {
        padding: 1.5rem;
        flex: 1;
    }

    .store-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .store-avatar {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .store-details {
        flex: 1;
    }

    .store-name {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .store-category {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    .store-rating {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--accent-color);
    }

    .promo-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .promo-description {
        color: var(--text-secondary);
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .promo-details {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .detail-item i {
        color: var(--primary-color);
        width: 16px;
    }

    .card-action {
        padding: 1rem 1.5rem;
        border-top: 1px solid #f3f4f6;
    }

    .btn-claim-modern {
        width: 100%;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-claim-modern:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-color));
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-claim-modern i {
        transition: transform 0.3s ease;
    }

    .btn-claim-modern:hover i {
        transform: translateX(4px);
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-primary-modern:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-color));
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-shine {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-primary-modern:hover .btn-shine {
        left: 100%;
    }

    /* How it works section */
    .how-it-works-section {
        background: var(--bg-light);
    }

    .steps-container {
        position: relative;
    }

    .step-item {
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
        margin-bottom: 3rem;
        position: relative;
    }

    .step-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 24px;
        top: 60px;
        width: 2px;
        height: 60px;
        background: linear-gradient(180deg, var(--primary-color), transparent);
    }

    .step-number {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.2rem;
        flex-shrink: 0;
        box-shadow: var(--shadow-lg);
    }

    .step-content {
        flex: 1;
    }

    .step-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, var(--accent-color), #d97706);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: white;
        margin-bottom: 1rem;
        box-shadow: var(--shadow-md);
    }

    .step-content h4 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .step-content p {
        color: var(--text-secondary);
        font-size: 1.1rem;
        line-height: 1.6;
        margin: 0;
    }

    /* Phone Mockup */
    .mockup-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .phone-mockup {
        width: 280px;
        height: 560px;
        background: linear-gradient(135deg, #1f2937, #111827);
        border-radius: 32px;
        padding: 24px 16px;
        box-shadow: var(--shadow-xl);
        position: relative;
    }

    .phone-mockup::before {
        content: '';
        position: absolute;
        top: 12px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: #374151;
        border-radius: 2px;
    }

    .phone-screen {
        width: 100%;
        height: 100%;
        background: white;
        border-radius: 24px;
        overflow: hidden;
        position: relative;
    }

    .app-interface {
        padding: 1.5rem 1rem;
        height: 100%;
    }

    .app-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .app-title {
        font-size: 1.2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .notification-dot {
        width: 8px;
        height: 8px;
        background: var(--secondary-color);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    .search-bar {
        background: #f3f4f6;
        padding: 12px 16px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 1.5rem;
        color: var(--text-secondary);
    }

    .promo-mini-cards {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 2rem;
    }

    .mini-card {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }

    .mini-card.active {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
    }

    .mini-discount {
        font-weight: 700;
        font-size: 0.9rem;
    }

    .mini-store {
        font-size: 0.8rem;
        opacity: 0.8;
    }

    .qr-code-preview {
        text-align: center;
        margin-top: auto;
    }

    .qr-placeholder {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 12px;
        margin: 0 auto 1rem;
        position: relative;
        overflow: hidden;
    }

    .qr-placeholder::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background: repeating-linear-gradient(0deg,
                white 0px,
                white 4px,
                transparent 4px,
                transparent 8px),
            repeating-linear-gradient(90deg,
                white 0px,
                white 4px,
                transparent 4px,
                transparent 8px);
    }

    .qr-code-preview p {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 50%, var(--secondary-color) 100%);
        color: white;
    }

    .cta-container {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: var(--border-radius);
        padding: 3rem 2rem;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .cta-emoji {
        font-size: 4rem;
        margin-bottom: 1rem;
        display: block;
    }

    .cta-container h2 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
    }

    .cta-container p {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 2rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .btn-white {
        background: white;
        color: var(--primary-color);
        border: 2px solid white;
        padding: 15px 30px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .btn-white:hover {
        background: transparent;
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-outline-white {
        background: transparent;
        color: white;
        border: 2px solid white;
        padding: 15px 30px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .btn-outline-white:hover {
        background: white;
        color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .floating-cards {
            display: none;
        }

        .step-item {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }

        .step-item::after {
            display: none;
        }

        .phone-mockup {
            width: 240px;
            height: 480px;
        }

        .cta-container h2 {
            font-size: 2rem;
        }

        .cta-actions {
            flex-direction: column;
            gap: 1rem;
        }

        .btn-white,
        .btn-outline-white {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .hero-title {
            font-size: 2rem;
        }

        .hero-actions {
            flex-direction: column;
            gap: 1rem;
        }

        .btn-hero {
            width: 100%;
            justify-content: center;
        }

        .promo-card-modern {
            margin-bottom: 1rem;
        }
    }
</style>

<?php
require_once 'includes/footer.php';
?>