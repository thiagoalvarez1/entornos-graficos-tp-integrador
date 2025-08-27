<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Rosario - Sistema de Promociones</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #f59e0b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #06b6d4;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-3: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            overflow-x: hidden;
        }

        /* NAVBAR MEJORADA */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar-custom.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            margin: 0 10px;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background: var(--gradient);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 80%;
        }

        /* HERO SECTION ESPECTACULAR */
        .hero-section {
            min-height: 100vh;
            background: var(--gradient);
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="%23ffffff08" points="0,1000 1000,0 1000,1000"/></svg>');
            background-size: cover;
        }

        .hero-particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .particle:nth-child(1) { width: 80px; height: 80px; top: 20%; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 60px; height: 60px; top: 60%; left: 80%; animation-delay: 2s; }
        .particle:nth-child(3) { width: 40px; height: 40px; top: 80%; left: 20%; animation-delay: 4s; }
        .particle:nth-child(4) { width: 100px; height: 100px; top: 40%; left: 70%; animation-delay: 1s; }
        .particle:nth-child(5) { width: 50px; height: 50px; top: 10%; left: 60%; animation-delay: 3s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.5; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
        }

        .hero-content {
            position: relative;
            z-index: 10;
            text-align: center;
            color: white;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 900;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, #ffffff, #e0e7ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 3rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-hero {
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: 700;
            border: none;
            border-radius: 50px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            margin: 0 15px;
        }

        .btn-hero-primary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-hero-secondary {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.5);
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .btn-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-hero:hover::before {
            left: 100%;
        }

        /* SECCIÓN DE CARACTERÍSTICAS */
        .features-section {
            padding: 120px 0;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            position: relative;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            height: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--gradient);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 100px;
            height: 100px;
            background: var(--gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 2.5rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .feature-icon::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: var(--gradient-2);
            transform: scale(0);
            transition: transform 0.4s ease;
            border-radius: 50%;
        }

        .feature-card:hover .feature-icon::after {
            transform: scale(1);
        }

        .feature-icon i {
            position: relative;
            z-index: 2;
        }

        /* TARJETAS DE PROMOCIONES MEJORADAS */
        .promotion-card {
            border: none;
            border-radius: 25px;
            overflow: hidden;
            background: white;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            position: relative;
            height: 100%;
        }

        .promotion-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1));
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 1;
        }

        .promotion-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.2);
        }

        .promotion-card:hover::before {
            opacity: 1;
        }

        .promotion-card .card-img-top {
            height: 250px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .promotion-card:hover .card-img-top {
            transform: scale(1.1);
        }

        .promotion-card .card-body {
            padding: 30px;
            position: relative;
            z-index: 2;
        }

        .promotion-card .card-title {
            font-size: 1.5rem;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }

        .badge-custom {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            background: var(--gradient);
            color: white;
            border: none;
        }

        .badge-inicial { background: linear-gradient(135deg, #64748b, #475569); }
        .badge-medium { background: linear-gradient(135deg, #06b6d4, #0891b2); }
        .badge-premium { background: linear-gradient(135deg, #f59e0b, #d97706); }

        /* MODAL MEJORADO */
        .modal-content {
            border: none;
            border-radius: 25px;
            box-shadow: 0 50px 150px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .modal-header {
            background: var(--gradient);
            color: white;
            border: none;
            padding: 30px;
        }

        .modal-header h5 {
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0;
        }

        .modal-body {
            padding: 40px;
        }

        /* FILTROS Y BÚSQUEDA MEJORADOS */
        .search-container {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            margin-bottom: 60px;
        }

        .form-control-custom {
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 15px 20px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-control-custom:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
            background: white;
        }

        .form-select-custom {
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 15px 20px;
            font-size: 1.1rem;
            background: #f8fafc;
            transition: all 0.3s ease;
        }

        .form-select-custom:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
            background: white;
        }

        /* ESTADÍSTICAS */
        .stats-section {
            background: var(--dark-color);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .stats-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="%23ffffff05" points="0,0 1000,1000 0,1000"/></svg>');
        }

        .stat-item {
            text-align: center;
            padding: 30px;
            position: relative;
        }

        .stat-number {
            font-size: 4rem;
            font-weight: 900;
            background: var(--gradient-3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
            display: block;
        }

        .stat-label {
            font-size: 1.2rem;
            font-weight: 600;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* FOOTER MEJORADO */
        .footer-custom {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: white;
            padding: 60px 0 30px;
            position: relative;
        }

        .footer-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--gradient);
        }

        .footer-section h5 {
            font-weight: 700;
            margin-bottom: 25px;
            color: #e2e8f0;
        }

        .footer-section a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s ease;
            display: block;
            margin-bottom: 10px;
        }

        .footer-section a:hover {
            color: white;
            transform: translateX(5px);
        }

        .social-links a {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }

        /* ANIMACIONES ADICIONALES */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .fade-in-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .btn-hero {
                margin: 10px 0;
                width: 100%;
                max-width: 300px;
            }
            
            .feature-card {
                margin-bottom: 30px;
            }
            
            .stat-number {
                font-size: 3rem;
            }
        }

        /* LOADING ANIMATION */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <i class="fas fa-shopping-bag me-2"></i>
                SHOPPING ROSARIO
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#promociones">Promociones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#novedades">Novedades</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>Mi Cuenta
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="showLogin()">
                                <i class="fas fa-sign-in-alt me-2"></i>Ingresar
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="showRegister()">
                                <i class="fas fa-user-plus me-2"></i>Registrarse
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="showAdminLogin()">
                                <i class="fas fa-user-shield me-2"></i>Admin / Local
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="hero-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
        
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title animate__animated animate__fadeInDown">
                    DESCUBRÍ LAS MEJORES PROMOCIONES
                </h1>
                <p class="hero-subtitle animate__animated animate__fadeInUp">
                    Encontrá descuentos exclusivos en +50 locales de nuestro shopping. 
                    Registrate y accedé a ofertas personalizadas según tu categoría de cliente.
                </p>
                
                <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
                    <button class="btn btn-hero btn-hero-primary" onclick="showRegister()">
                        <i class="fas fa-user-plus me-2"></i>CREAR CUENTA GRATIS
                    </button>
                    <button class="btn btn-hero btn-hero-secondary" onclick="document.getElementById('promociones').scrollIntoView({behavior: 'smooth'})">
                        <i class="fas fa-tags me-2"></i>VER PROMOCIONES
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-4 fw-bold mb-4" data-aos="fade-up">
                        ¿Por qué elegir nuestro sistema?
                    </h2>
                    <p class="lead text-muted" data-aos="fade-up" data-aos-delay="200">
                        Acceso a promociones exclusivas con un sistema inteligente de categorización
                    </p>
                </div>
            </div>
            
            <div class="row g-5">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <h4 class="fw-bold mb-3">DESCUENTOS EXCLUSIVOS</h4>
                        <p class="text-muted">
                            Sistema inteligente de categorización: Inicial, Medium y Premium. 
                            Cuanto más uses las promociones, mejores beneficios obtenés.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <h4 class="fw-bold mb-3">+50 LOCALES PARTICIPANTES</h4>
                        <p class="text-muted">
                            Amplia variedad de rubros: indumentaria, tecnología, gastronomía, 
                            calzados, deportes y mucho más. Todo en un solo lugar.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h4 class="fw-bold mb-3">CÓDIGOS QR ÚNICOS</h4>
                        <p class="text-muted">
                            Cada promoción genera un código QR único para validar en el local. 
                            Seguro, rápido y sin complicaciones.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Novedades Section -->
    <section id="novedades" class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-4 fw-bold mb-4" data-aos="fade-up">
                        ÚLTIMAS NOVEDADES
                    </h2>
                    <p class="lead text-muted" data-aos="fade-up" data-aos-delay="200">
                        Mantente informado sobre las últimas promociones y novedades del shopping
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-lg">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge badge-custom">PARA TODOS</span>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>20/08/2025
                                </small>
                            </div>
                            <h5 class="card-title fw-bold">¡NUEVO HORARIO EXTENDIDO!</h5>
                            <p class="card-text">
                                Ampliamos nuestro horario de atención para tu comodidad. 
                                Ahora abrimos de 9:00 a 22:00 hs todos los días incluyendo feriados.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 border-0 shadow-lg">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge badge-premium">CLIENTES PREMIUM</span>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>25/08/2025
                                </small>
                            </div>
                            <h5 class="card-title fw-bold">WEEKEND PREMIUM ESPECIAL</h5>
                            <p class="card-text">
                                Clientes Premium: 50% OFF adicional en todos los locales participantes 
                                este fin de semana. ¡No te lo pierdas!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Promociones Section -->
    <section id="promociones" class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-4 fw-bold mb-4" data-aos="fade-up">
                        PROMOCIONES VIGENTES
                    </h2>
                    <p class="lead text-muted" data-aos="fade-up" data-aos-delay="200">
                        Encontrá las mejores ofertas disponibles en este momento
                    </p>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="search-container" data-aos="fade-up" data-aos-delay="300">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control form-control-custom border-start-0" 
                                   placeholder="Buscar promociones o locales..." id="buscadorPromociones">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <select class="form-select form-select-custom" id="filtroCategoria">
                            <option value="">TODAS LAS CATEGORÍAS</option>
                            <option value="Inicial">CLIENTES INICIAL</option>
                            <option value="Medium">CLIENTES MEDIUM</option>
                            <option value="Premium">CLIENTES PREMIUM</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Promotions Grid -->
            <div class="row g-4" id="gridPromociones">
                <!-- Promoción 1 -->
                <div class="col-xl-4 col-lg-6" data-category="Inicial" data-aos="fade-up" data-aos-delay="100">
                    <div class="card promotion-card">
                        <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80" 
                             class="card-img-top" alt="Tienda Fashion">
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge badge-inicial">
                                    <i class="fas fa-user me-1"></i>Inicial
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-store me-1"></i>Tienda Fashion
                                </span>
                            </div>

                            <h5 class="card-title">20% DE DESCUENTO EN EFECTIVO</h5>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Válida: 01/08/2025 - 30/09/2025
                                </small>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    Días: Lunes a Viernes
                                </small>
                            </div>

                            <div class="mb-3">
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>APROBADA
                                </span>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-0 pt-0">
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#promoModal1">
                                <i class="fas fa-qrcode me-2"></i>USAR PROMOCIÓN
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Promoción 2 -->
                <div class="col-xl-4 col-lg-6" data-category="Medium" data-aos="fade-up" data-aos-delay="200">
                    <div class="card promotion-card">
                        <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80" 
                             class="card-img-top" alt="Calzados Premium">
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge badge-medium">
                                    <i class="fas fa-star me-1"></i>Medium
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-store me-1"></i>Calzados Premium
                                </span>
                            </div>

                            <h5 class="card-title">2x1 EN PRODUCTOS SELECCIONADOS</h5>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Válida: 01/09/2025 - 15/10/2025
                                </small>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    Días: Sábado y Domingo
                                </small>
                            </div>

                            <div class="mb-3">
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>APROBADA
                                </span>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-0 pt-0">
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#promoModal2">
                                <i class="fas fa-qrcode me-2"></i>USAR PROMOCIÓN
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Promoción 3 -->
                <div class="col-xl-4 col-lg-6" data-category="Premium" data-aos="fade-up" data-aos-delay="300">
                    <div class="card promotion-card">
                        <img src="https://images.unsplash.com/photo-1498049794561-7780e7231661?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80" 
                             class="card-img-top" alt="TecnoShop">
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge badge-premium">
                                    <i class="fas fa-crown me-1"></i>Premium
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-store me-1"></i>TecnoShop
                                </span>
                            </div>

                            <h5 class="card-title">30% OFF + ENVÍO GRATIS</h5>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Válida: 15/08/2025 - 31/08/2025
                                </small>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    Días: Lunes, Miércoles y Viernes
                                </small>
                            </div>

                            <div class="mb-3">
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>APROBADA
                                </span>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-0 pt-0">
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#promoModal3">
                                <i class="fas fa-qrcode me-2"></i>USAR PROMOCIÓN
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Promoción 4 -->
                <div class="col-xl-4 col-lg-6" data-category="Medium" data-aos="fade-up" data-aos-delay="400">
                    <div class="card promotion-card">
                        <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80" 
                             class="card-img-top" alt="SportZone">
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge badge-medium">
                                    <i class="fas fa-star me-1"></i>Medium
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-store me-1"></i>SportZone
                                </span>
                            </div>

                            <h5 class="card-title">40% OFF EN ROPA DEPORTIVA</h5>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Válida: 20/08/2025 - 20/09/2025
                                </small>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    Días: Todos los días
                                </small>
                            </div>

                            <div class="mb-3">
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>APROBADA
                                </span>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-0 pt-0">
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#promoModal4">
                                <i class="fas fa-qrcode me-2"></i>USAR PROMOCIÓN
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Promoción 5 -->
                <div class="col-xl-4 col-lg-6" data-category="Inicial" data-aos="fade-up" data-aos-delay="500">
                    <div class="card promotion-card">
                        <img src="https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80" 
                             class="card-img-top" alt="Café Central">
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge badge-inicial">
                                    <i class="fas fa-user me-1"></i>Inicial
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-store me-1"></i>Café Central
                                </span>
                            </div>

                            <h5 class="card-title">2 CAFÉS POR EL PRECIO DE 1</h5>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Válida: 01/08/2025 - 31/12/2025
                                </small>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    Días: Lunes a Viernes 14:00-18:00
                                </small>
                            </div>

                            <div class="mb-3">
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>APROBADA
                                </span>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-0 pt-0">
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#promoModal5">
                                <i class="fas fa-qrcode me-2"></i>USAR PROMOCIÓN
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Promoción 6 -->
                <div class="col-xl-4 col-lg-6" data-category="Premium" data-aos="fade-up" data-aos-delay="600">
                    <div class="card promotion-card">
                        <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80" 
                             class="card-img-top" alt="Joyería Elegante">
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge badge-premium">
                                    <i class="fas fa-crown me-1"></i>Premium
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-store me-1"></i>Joyería Elegante
                                </span>
                            </div>

                            <h5 class="card-title">50% OFF EN JOYAS SELECCIONADAS</h5>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Válida: 26/08/2025 - 30/09/2025
                                </small>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    Días: Martes y Jueves
                                </small>
                            </div>

                            <div class="mb-3">
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>APROBADA
                                </span>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-0 pt-0">
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#promoModal6">
                                <i class="fas fa-qrcode me-2"></i>USAR PROMOCIÓN
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Results Message -->
            <div id="noResults" class="text-center py-5" style="display: none;">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No se encontraron promociones</h4>
                <p class="text-muted">Intenta con otros términos de búsqueda o selecciona una categoría diferente</p>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-item">
                        <span class="stat-number" data-count="500">0</span>
                        <p class="stat-label">Promociones Mensuales</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-item">
                        <span class="stat-number" data-count="5000">0</span>
                        <p class="stat-label">Clientes Registrados</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-item">
                        <span class="stat-number" data-count="95">0</span>
                        <p class="stat-label">% Satisfacción Cliente</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-item">
                        <span class="stat-number" data-count="50">0</span>
                        <p class="stat-label">Locales Participantes</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <h3 class="fw-bold mb-4">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Shopping Rosario
                    </h3>
                    <p class="text-muted mb-4">
                        El sistema de promociones más completo de la ciudad. 
                        Descuentos exclusivos, categorías de clientes y experiencias únicas.
                    </p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2">
                    <div class="footer-section">
                        <h5>Enlaces</h5>
                        <a href="#home">Inicio</a>
                        <a href="#promociones">Promociones</a>
                        <a href="#novedades">Novedades</a>
                        <a href="#" onclick="showLogin()">Mi Cuenta</a>
                    </div>
                </div>
                
                <div class="col-lg-3">
                    <div class="footer-section">
                        <h5>Información</h5>
                        <a href="#">Términos y Condiciones</a>
                        <a href="#">Política de Privacidad</a>
                        <a href="#">Preguntas Frecuentes</a>
                        <a href="#">Centro de Ayuda</a>
                    </div>
                </div>
                
                <div class="col-lg-3">
                    <div class="footer-section">
                        <h5>Contacto</h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Av. Pellegrini 3050, Rosario
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-phone me-2"></i>
                            (0341) 123-4567
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-envelope me-2"></i>
                            info@shoppingrosario.com
                        </p>
                    </div>
                </div>
            </div>
            
            <hr class="my-5" style="border-color: #374151;">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        © 2025 Shopping Rosario. Todos los derechos reservados.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">
                        Desarrollado con ❤️ para UTN-FRRo | Entornos Gráficos
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Modals -->
    
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="loginForm">
                        <div class="mb-4">
                            <label for="loginEmail" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control form-control-custom" id="loginEmail" required>
                        </div>
                        <div class="mb-4">
                            <label for="loginPassword" class="form-label fw-bold">Contraseña</label>
                            <input type="password" class="form-control form-control-custom" id="loginPassword" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>INGRESAR
                            </button>
                        </div>
                    </form>
                    <div class="text-center mt-4">
                        <p class="mb-0">¿No tienes cuenta? 
                            <a href="#" onclick="showRegister()" class="fw-bold">Regístrate aquí</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus me-2"></i>Crear Cuenta
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="registerForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="regNombre" class="form-label fw-bold">Nombre</label>
                                <input type="text" class="form-control form-control-custom" id="regNombre" required>
                            </div>
                            <div class="col-md-6">
                                <label for="regApellido" class="form-label fw-bold">Apellido</label>
                                <input type="text" class="form-control form-control-custom" id="regApellido" required>
                            </div>
                            <div class="col-12">
                                <label for="regEmail" class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control form-control-custom" id="regEmail" required>
                            </div>
                            <div class="col-md-6">
                                <label for="regPassword" class="form-label fw-bold">Contraseña</label>
                                <input type="password" class="form-control form-control-custom" id="regPassword" required>
                            </div>
                            <div class="col-md-6">
                                <label for="regConfirmPassword" class="form-label fw-bold">Confirmar Contraseña</label>
                                <input type="password" class="form-control form-control-custom" id="regConfirmPassword" required>
                            </div>
                            <div class="col-12">
                                <label for="regTelefono" class="form-label fw-bold">Teléfono</label>
                                <input type="tel" class="form-control form-control-custom" id="regTelefono" required>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="regTerms" required>
                                    <label class="form-check-label" for="regTerms">
                                        Acepto los <a href="#">términos y condiciones</a>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>CREAR CUENTA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin/Local Login Modal -->
    <div class="modal fade" id="adminLoginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-shield me-2"></i>Acceso Administrador/Local
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="userType" id="adminType" checked>
                            <label class="btn btn-outline-primary" for="adminType">Administrador</label>
                            
                            <input type="radio" class="btn-check" name="userType" id="localType">
                            <label class="btn btn-outline-primary" for="localType">Dueño de Local</label>
                        </div>
                    </div>
                    
                    <form id="adminLoginForm">
                        <div class="mb-4">
                            <label for="adminEmail" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control form-control-custom" id="adminEmail" required>
                        </div>
                        <div class="mb-4">
                            <label for="adminPassword" class="form-label fw-bold">Contraseña</label>
                            <input type="password" class="form-control form-control-custom" id="adminPassword" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>ACCEDER AL PANEL
                            </button>
                        </div>
                    </form>

                    <!-- Demo accounts info -->
                    <div class="alert alert-info mt-4">
                        <h6><i class="fas fa-info-circle me-2"></i>Cuentas de Demostración:</h6>
                        <small>
                            <strong>Admin:</strong> admin@shopping.com / admin123<br>
                            <strong>Local:</strong> local@tienda.com / local123
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Promotion Usage Modals -->
    <div class="modal fade" id="promoModal1" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Uso de Promoción</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80" 
                                 class="img-fluid rounded-3" alt="Tienda Fashion">
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">DETALLES DE LA PROMOCIÓN</h6>
                            <p class="fs-5 fw-bold">20% DE DESCUENTO EN EFECTIVO</p>
                            <div class="mb-2">
                                <i class="fas fa-store me-2 text-muted"></i>
                                <strong>Local:</strong> Tienda Fashion
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-calendar me-2 text-muted"></i>
                                <strong>Válida hasta:</strong> 30/09/2025
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-clock me-2 text-muted"></i>
                                <strong>Días:</strong> Lunes a Viernes
                            </div>
                            <div class="mb-0">
                                <i class="fas fa-user-tag me-2 text-muted"></i>
                                <strong>Categoría:</strong> Inicial
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="alert alert-info border-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Al confirmar, se generará un código QR único que deberás mostrar en el local para validar tu promoción.
                        <strong>Esta acción no se puede deshacer.</strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>CANCELAR
                    </button>
                    <button type="button" class="btn btn-primary" onclick="generateQR('promo1')">
                        <i class="fas fa-qrcode me-2"></i>GENERAR CÓDIGO QR
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-qrcode me-2"></i>Código QR Generado
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-4">
                        <div id="qrCode" class="d-flex justify-content-center">
                            <!-- QR Code will be generated here -->
                        </div>
                    </div>
                    <div class="alert alert-success border-0">
                        <h6><i class="fas fa-check-circle me-2"></i>¡Promoción Activada!</h6>
                        <p class="mb-0">Mostra este código QR en el local para validar tu descuento.</p>
                    </div>
                    <div class="bg-light p-3 rounded-3">
                        <small class="text-muted">
                            <strong>Código:</strong> <span id="promoCode">SHP-2025-001</span><br>
                            <strong>Válido hasta:</strong> <span id="promoExpiry">26/08/2025 23:59</span>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="downloadQR()">
                        <i class="fas fa-download me-2"></i>DESCARGAR QR
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>CERRAR
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message Modal -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-4"></i>
                    <h4 class="fw-bold mb-3">¡Registro Exitoso!</h4>
                    <p class="text-muted mb-4">
                        Tu cuenta ha sido creada exitosamente. 
                        Hemos enviado un email de confirmación a tu casilla.
                    </p>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="fas fa-check me-2"></i>ENTENDIDO
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>

    <script>
        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            // Ocultar loading overlay
            setTimeout(() => {
                document.getElementById('loadingOverlay').style.opacity = '0';
                setTimeout(() => {
                    document.getElementById('loadingOverlay').style.display = 'none';
                }, 500);
            }, 1500);

            // Inicializar AOS
            AOS.init({
                duration: 1000,
                easing: 'ease-in-out-cubic',
                once: true,
                offset: 50
            });

            // Navbar scroll effect
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar-custom');
                if (window.scrollY > 100) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Contador animado para estadísticas
            const counters = document.querySelectorAll('.stat-number');
            const observerOptions = {
                threshold: 0.7
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            counters.forEach(counter => {
                observer.observe(counter);
            });

            // Búsqueda y filtros
            const buscador = document.getElementById('buscadorPromociones');
            const filtro = document.getElementById('filtroCategoria');
            const grid = document.getElementById('gridPromociones');
            const noResults = document.getElementById('noResults');

            function filtrarPromociones() {
                const texto = buscador.value.toLowerCase().trim();
                const categoria = filtro.value;
                const cards = grid.querySelectorAll('.col-xl-4, .col-lg-6');
                let visibleCount = 0;

                cards.forEach(card => {
                    const textoCard = card.textContent.toLowerCase();
                    const categoriaCard = card.getAttribute('data-category');
                    
                    const coincideTexto = texto === '' || textoCard.includes(texto);
                    const coincideCategoria = categoria === '' || categoriaCard === categoria;
                    
                    if (coincideTexto && coincideCategoria) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Mostrar/ocultar mensaje de sin resultados
                if (visibleCount === 0) {
                    noResults.style.display = 'block';
                } else {
                    noResults.style.display = 'none';
                }
            }

            buscador.addEventListener('input', filtrarPromociones);
            filtro.addEventListener('change', filtrarPromociones);

            // Manejo de formularios
            setupFormHandlers();
        });

        // Funciones para mostrar modales
        function showLogin() {
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        }

        function showRegister() {
            const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
            registerModal.show();
        }

        function showAdminLogin() {
            const adminLoginModal = new bootstrap.Modal(document.getElementById('adminLoginModal'));
            adminLoginModal.show();
        }

        // Animación de contadores
        function animateCounter(counter) {
            const target = parseInt(counter.getAttribute('data-count'));
            const increment = target / 50;
            let current = 0;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    counter.textContent = target;
                    clearInterval(timer);
                } else {
                    counter.textContent = Math.ceil(current);
                }
            }, 40);
        }

        // Generación de código QR
        function generateQR(promoId) {
            // Generar código único
            const codigo = 'SHP-' + new Date().getFullYear() + '-' + 
                          Math.random().toString(36).substr(2, 6).toUpperCase();
            
            // Actualizar información del modal
            document.getElementById('promoCode').textContent = codigo;
            
            const expiry = new Date();
            expiry.setHours(23, 59, 59, 999);
            document.getElementById('promoExpiry').textContent = expiry.toLocaleString('es-AR');

            // Generar QR code
            const qr = new QRious({
                element: document.createElement('canvas'),
                value: JSON.stringify({
                    code: codigo,
                    promo: promoId,
                    timestamp: Date.now(),
                    shopping: 'Rosario'
                }),
                size: 200,
                foreground: '#2563eb',
                background: '#ffffff'
            });

            // Mostrar QR code
            const qrContainer = document.getElementById('qrCode');
            qrContainer.innerHTML = '';
            qrContainer.appendChild(qr.canvas);

            // Cerrar modal anterior y mostrar QR
            const currentModal = bootstrap.Modal.getInstance(document.querySelector('.modal.show'));
            if (currentModal) {
                currentModal.hide();
            }

            setTimeout(() => {
                const qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
                qrModal.show();
            }, 300);

            // Simular uso de promoción (actualizar estadísticas)
            updateUserStats();
        }

        // Descargar código QR
        function downloadQR() {
            const canvas = document.querySelector('#qrCode canvas');
            if (canvas) {
                const link = document.createElement('a');
                link.download = 'promocion-qr-' + document.getElementById('promoCode').textContent + '.png';
                link.href = canvas.toDataURL();
                link.click();
            }
        }

        // Configuración de manejadores de formularios
        function setupFormHandlers() {
            // Login form
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = document.getElementById('loginEmail').value;
                const password = document.getElementById('loginPassword').value;
                
                // Simulación de login
                if (validateLogin(email, password)) {
                    // Guardar sesión simulada
                    sessionStorage.setItem('user', JSON.stringify({
                        email: email,
                        nombre: 'Usuario',
                        categoria: 'Inicial',
                        promociones_usadas: 0
                    }));
                    
                    // Cerrar modal y actualizar UI
                    bootstrap.Modal.getInstance(document.getElementById('loginModal')).hide();
                    updateUserInterface();
                    
                    showNotification('¡Bienvenido!', 'Has iniciado sesión correctamente.', 'success');
                } else {
                    showNotification('Error', 'Credenciales inválidas.', 'error');
                }
            });

            // Register form
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = {
                    nombre: document.getElementById('regNombre').value,
                    apellido: document.getElementById('regApellido').value,
                    email: document.getElementById('regEmail').value,
                    password: document.getElementById('regPassword').value,
                    confirmPassword: document.getElementById('regConfirmPassword').value,
                    telefono: document.getElementById('regTelefono').value
                };
                
                if (validateRegisterForm(formData)) {
                    // Cerrar modal de registro
                    bootstrap.Modal.getInstance(document.getElementById('registerModal')).hide();
                    
                    // Mostrar modal de éxito
                    setTimeout(() => {
                        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                        successModal.show();
                    }, 300);
                }
            });

            // Admin login form
            document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = document.getElementById('adminEmail').value;
                const password = document.getElementById('adminPassword').value;
                const userType = document.querySelector('input[name="userType"]:checked').id;
                
                if (validateAdminLogin(email, password, userType)) {
                    bootstrap.Modal.getInstance(document.getElementById('adminLoginModal')).hide();
                    
                    // Redirigir al panel correspondiente
                    if (userType === 'adminType') {
                        showNotification('¡Bienvenido Administrador!', 'Redirigiendo al panel de control...', 'success');
                        setTimeout(() => {
                            // Aquí se redirigiría al panel de admin
                            window.location.href = 'admin-panel.php';
                        }, 2000);
                    } else {
                        showNotification('¡Bienvenido Dueño de Local!', 'Redirigiendo al panel de gestión...', 'success');
                        setTimeout(() => {
                            // Aquí se redirigiría al panel de local
                            window.location.href = 'local-panel.php';
                        }, 2000);
                    }
                } else {
                    showNotification('Error de Acceso', 'Credenciales inválidas para el tipo de usuario seleccionado.', 'error');
                }
            });
        }

        // Validaciones
        function validateLogin(email, password) {
            // Cuentas de prueba para clientes
            const testUsers = [
                { email: 'cliente@test.com', password: 'cliente123' },
                { email: 'user@shopping.com', password: 'user123' },
                { email: 'premium@test.com', password: 'premium123' }
            ];
            
            return testUsers.some(user => user.email === email && user.password === password);
        }

        function validateRegisterForm(data) {
            // Validar campos requeridos
            if (!data.nombre || !data.apellido || !data.email || !data.password || !data.telefono) {
                showNotification('Error', 'Todos los campos son obligatorios.', 'error');
                return false;
            }
            
            // Validar email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(data.email)) {
                showNotification('Error', 'El formato del email no es válido.', 'error');
                return false;
            }
            
            // Validar contraseñas
            if (data.password !== data.confirmPassword) {
                showNotification('Error', 'Las contraseñas no coinciden.', 'error');
                return false;
            }
            
            if (data.password.length < 6) {
                showNotification('Error', 'La contraseña debe tener al menos 6 caracteres.', 'error');
                return false;
            }
            
            // Validar teléfono
            const phoneRegex = /^[\d\s\-\+\(\)]{8,15}$/;
            if (!phoneRegex.test(data.telefono)) {
                showNotification('Error', 'El formato del teléfono no es válido.', 'error');
                return false;
            }
            
            return true;
        }

        function validateAdminLogin(email, password, userType) {
            const adminCredentials = {
                admin: { email: 'admin@shopping.com', password: 'admin123' },
                local: { email: 'local@tienda.com', password: 'local123' }
            };
            
            const type = userType === 'adminType' ? 'admin' : 'local';
            const credentials = adminCredentials[type];
            
            return credentials.email === email && credentials.password === password;
        }

        // Actualizar interfaz de usuario
        function updateUserInterface() {
            const user = JSON.parse(sessionStorage.getItem('user'));
            if (user) {
                // Actualizar navbar
                const navUser = document.getElementById('navbarDropdown');
                if (navUser) {
                    navUser.innerHTML = `<i class="fas fa-user-circle me-1"></i>${user.nombre}`;
                }
                
                // Actualizar botones del hero
                const heroButtons = document.querySelector('.hero-content .btn-hero');
                if (heroButtons && heroButtons.parentElement) {
                    heroButtons.parentElement.innerHTML = `
                        <button class="btn btn-hero btn-hero-primary" onclick="document.getElementById('promociones').scrollIntoView({behavior: 'smooth'})">
                            <i class="fas fa-tags me-2"></i>VER PROMOCIONES
                        </button>
                        <button class="btn btn-hero btn-hero-secondary" onclick="showUserProfile()">
                            <i class="fas fa-user me-2"></i>MI PERFIL
                        </button>
                    `;
                }
            }
        }

        // Actualizar estadísticas del usuario
        function updateUserStats() {
            const user = JSON.parse(sessionStorage.getItem('user'));
            if (user) {
                user.promociones_usadas++;
                
                // Actualizar categoría según promociones usadas
                if (user.promociones_usadas >= 10) {
                    user.categoria = 'Premium';
                } else if (user.promociones_usadas >= 5) {
                    user.categoria = 'Medium';
                }
                
                sessionStorage.setItem('user', JSON.stringify(user));
                
                // Mostrar notificación de categoría si cambió
                if (user.promociones_usadas === 5) {
                    showNotification('¡Felicitaciones!', 'Has alcanzado la categoría Medium. ¡Nuevas promociones disponibles!', 'success');
                } else if (user.promociones_usadas === 10) {
                    showNotification('¡Increíble!', 'Has alcanzado la categoría Premium. ¡Acceso a todas las promociones!', 'success');
                }
            }
        }

        // Sistema de notificaciones
        function showNotification(title, message, type = 'info') {
            // Crear elemento de notificación
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
            
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                    <div>
                        <strong>${title}</strong>
                        <div>${message}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            // Auto-remover después de 5 segundos
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

        // Mostrar perfil de usuario
        function showUserProfile() {
            const user = JSON.parse(sessionStorage.getItem('user'));
            if (user) {
                showNotification(
                    'Mi Perfil',
                    `Categoría: ${user.categoria} | Promociones usadas: ${user.promociones_usadas}`,
                    'info'
                );
            }
        }

        // Smooth scrolling para enlaces internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Efecto parallax sutil en el hero
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const heroSection = document.querySelector('.hero-section');
            if (heroSection) {
                heroSection.style.transform = `translateY(${scrolled * 0.3}px)`;
            }
        });

        // Precargar imágenes importantes
        function preloadImages() {
            const imageUrls = [
                'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da',
                'https://images.unsplash.com/photo-1549298916-b41d501d3772',
                'https://images.unsplash.com/photo-1498049794561-7780e7231661'
            ];
            
            imageUrls.forEach(url => {
                const img = new Image();
                img.src = url + '?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80';
            });
        }

        // Inicializar precarga de imágenes
        preloadImages();

        // Detectar si el usuario ya está logueado al cargar la página
        window.addEventListener('load', function() {
            const user = sessionStorage.getItem('user');
            if (user) {
                updateUserInterface();
            }
        });

        // Logout function
        function logout() {
            sessionStorage.removeItem('user');
            location.reload();
        }

        // Configurar tooltips de Bootstrap
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>