<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Promociones - Shopping Center</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --border-radius: 12px;
            --box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Inter', sans-serif;
            color: var(--dark-text);
            line-height: 1.6;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), #34495e);
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: white !important;
        }
        
        .nav-link {
            font-weight: 500;
            color: rgba(255,255,255,0.9) !important;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }
        
        .nav-link:hover {
            color: white !important;
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: all 0.3s ease;
            background: white;
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 12px 24px;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success-color), #229954);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color), #e67e22);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--accent-color), #c0392b);
        }
        
        .hero-section {
            background: linear-gradient(rgba(44, 62, 80, 0.9), rgba(44, 62, 80, 0.8)), 
                       url('https://images.unsplash.com/photo-1556740711-a2c0d7c4c5e6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover;
            padding: 120px 0;
            color: white;
            text-align: center;
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
            box-shadow: var(--box-shadow);
        }
        
        .badge {
            border-radius: 20px;
            padding: 8px 16px;
            font-weight: 600;
        }
        
        .modal-content {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--box-shadow);
        }
        
        .table {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
        }
        
        .table th {
            background: linear-gradient(135deg, var(--primary-color), #34495e);
            color: white;
            font-weight: 600;
            border: none;
            padding: 15px;
        }
        
        .table td {
            padding: 15px;
            vertical-align: middle;
            border-color: #eee;
        }
        
        .alert {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--box-shadow);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                padding: 80px 0;
            }
            
            .display-4 {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-shopping-center me-2"></i>ShopCenter
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="promociones.php">
                            <i class="fas fa-tags me-1"></i>Promociones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="locales.php">
                            <i class="fas fa-store me-1"></i>Locales
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contacto.php">
                            <i class="fas fa-envelope me-1"></i>Contacto
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <!-- Notifications -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                <span class="badge bg-danger">3</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><h6 class="dropdown-header">Notificaciones</h6></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-check-circle text-success me-2"></i>Promoción aprobada</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-store text-primary me-2"></i>Nuevo local disponible</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-star text-warning me-2"></i>Subiste de categoría</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="#">Ver todas</a></li>
                            </ul>
                        </li>
                        
                        <!-- User Profile -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                <?= htmlspecialchars($_SESSION['usuario']['nombre'] ?? $_SESSION['usuario']['email']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><h6 class="dropdown-header">Mi Cuenta</h6></li>
                                <li><a class="dropdown-item" href="perfil.php"><i class="fas fa-user-edit me-2"></i>Mi Perfil</a></li>
                                <li><a class="dropdown-item" href="mis_promociones.php"><i class="fas fa-ticket-alt me-2"></i>Mis Promociones</a></li>
                                <li><hr class="dropdown-divider"></li>
                                
                                <?php if ($_SESSION['usuario']['rol'] === 'administrador'): ?>
                                    <li><a class="dropdown-item" href="admin/panel.php"><i class="fas fa-cog me-2"></i>Panel Administrador</a></li>
                                <?php elseif ($_SESSION['usuario']['rol'] === 'dueno'): ?>
                                    <li><a class="dropdown-item" href="dueño/panel.php"><i class="fas fa-store me-2"></i>Panel Dueño</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="cliente/panel.php"><i class="fas fa-user-circle me-2"></i>Panel Cliente</a></li>
                                <?php endif; ?>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                <i class="fas fa-sign-in-alt me-1"></i>Ingresar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">
                                <i class="fas fa-user-plus me-1"></i>Registrarse
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error:</strong> <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Éxito:</strong> <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>