<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Shopping Rosario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow">
          <div class="card-body">
            <h3 class="card-title text-center mb-4">Iniciar Sesión</h3>
            <form method="POST" action="">
              <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="usuario@mail.com" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="********" required>
              </div>
              <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            </form>
            <p class="mt-3 text-center">¿No tenés cuenta? <a href="register.php">Registrate</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
