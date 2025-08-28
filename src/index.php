<?php
// index.php - DEBE verse así SOLAMENTE

require_once 'includes/config.php';
$pageTitle = "Inicio - PromoShopping";
require_once 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PromoShopping - Ofertas exclusivas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #e74c3c;
            --accent: #3498db;
            --light: #ecf0f1;
            --dark: #2c3e50;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .navbar {
            background-color: var(--primary);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .hero-section {
            background: linear-gradient(rgba(44, 62, 80, 0.8), rgba(44, 62, 80, 0.8)), url('https://images.unsplash.com/photo-1563013546-72e6b2025c93?ixlib=rb-4.0.3') center/cover;
            color: white;
            padding: 100px 0;
            text-align: center;
        }

        .promo-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .promo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .category-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-inicial {
            background-color: #2ecc71;
            color: white;
        }

        .badge-medium {
            background-color: #f39c12;
            color: white;
        }

        .badge-premium {
            background-color: #e74c3c;
            color: white;
        }

        .btn-primary {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }

        .btn-primary:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }

        .footer {
            background-color: var(--dark);
            color: white;
            padding: 40px 0;
        }

        .section-title {
            position: relative;
            margin-bottom: 30px;
            padding-bottom: 15px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--secondary);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-store me-2"></i>PromoShopping
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#promociones">Promociones</a></li>
                    <li class="nav-item"><a class="nav-link" href="#locales">Locales</a></li>
                    <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>
                </ul>
                <div class="d-flex">
                    <a href="login.php" class="btn btn-outline-light me-2">Iniciar Sesión</a>
                    <a href="registro.php" class="btn btn-primary">Registrarse</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Descubre las mejores promociones</h1>
            <p class="lead mb-4">Encuentra ofertas exclusivas en tus locales favoritos del shopping</p>
            <a href="#promociones" class="btn btn-primary btn-lg">Ver promociones <i
                    class="fas fa-arrow-right ms-2"></i></a>
        </div>
    </section>

    <!-- Promociones Section -->
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

    <!-- Cómo funciona Section -->
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

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>PromoShopping</h5>
                    <p>El mejor lugar para encontrar las promociones exclusivas de tu shopping favorito.</p>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5>Enlaces</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-white">Inicio</a></li>
                        <li><a href="#promociones" class="text-white">Promociones</a></li>
                        <li><a href="#locales" class="text-white">Locales</a></li>
                        <li><a href="contacto.php" class="text-white">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5>Contacto</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Shopping del Sol, Rosario</li>
                        <li><i class="fas fa-phone me-2"></i> (0341) 123-4567</li>
                        <li><i class="fas fa-envelope me-2"></i> info@promoshopping.com</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Síguenos</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook-f fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-whatsapp fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4 bg-light">
            <div class="text-center">
                <p>&copy; 2025 PromoShopping. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
require_once 'includes/footer.php';
?>