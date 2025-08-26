<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Rosario - Promociones</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Shopping Rosario</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <?php if(isset($_SESSION['usuario'])): ?>
            <li class="nav-item"><a class="nav-link" href="#">Panel</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Salir</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="register.php">Registrarse</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Contenido -->
  <div class="container mt-4">
    <h1 class="text-center">Promociones Vigentes</h1>
    <div class="row mt-4">
      <!-- Ejemplo de tarjeta de promo -->
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">20% en efectivo</h5>
            <p class="card-text">Local: Tienda X</p>
            <p class="card-text"><small class="text-muted">Válido hasta: 30/09/2025</small></p>
            <a href="#" class="btn btn-primary">Usar promoción</a>
          </div>
        </div>
      </div>
      <!-- Después se cargan dinámicamente desde la BD -->
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
