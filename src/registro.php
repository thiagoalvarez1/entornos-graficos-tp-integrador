<?php
require_once 'includes/auth.php';

$auth = new Auth();

// Si ya está logueado, redirigir al panel correspondiente
if ($auth->isLoggedIn()) {
    $auth->redirectUser();
}

$error = '';
$success = '';

// Procesar formulario de registro
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $user_type = trim($_POST['user_type']);

    // Validaciones
    if ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden";
    } else {
        // Establecer categoría por defecto para clientes
        $category = ($user_type == USER_CLIENT) ? CATEGORY_INITIAL : '';

        $result = $auth->register($email, $password, $user_type, '', $category);

        if ($result === true) {
            $success = "Usuario registrado correctamente. Ahora puedes iniciar sesión.";
        } else {
            $error = $result;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - PromoShopping</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
        }

        .register-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
        }

        .register-header {
            background: linear-gradient(rgba(52, 152, 219, 0.9), rgba(52, 152, 219, 0.9)), url('https://images.unsplash.com/photo-1563013546-72e6b2025c93?ixlib=rb-4.0.3') center/cover;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .register-form {
            padding: 30px;
        }

        .btn-primary {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        .form-control:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="register-container">
                    <div class="register-header">
                        <h3><i class="fas fa-store me-2"></i>PromoShopping</h3>
                        <p class="mb-0">Crear una nueva cuenta</p>
                    </div>

                    <div class="register-form">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $success; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="user_type" class="form-label">Tipo de usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <select class="form-select" id="user_type" name="user_type" required>
                                        <option value="">Seleccionar tipo</option>
                                        <option value="<?php echo USER_CLIENT; ?>">Cliente</option>
                                        <option value="<?php echo USER_OWNER; ?>">Dueño de local</option>
                                    </select>
                                </div>
                                <div class="form-text">Los dueños de locales deben ser validados por un administrador
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmar contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirm_password"
                                        name="confirm_password" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                        </form>

                        <hr>

                        <div class="text-center">
                            <p>¿Ya tienes una cuenta? <a href="<?php echo SITE_URL; ?>login.php">Inicia sesión aquí</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>