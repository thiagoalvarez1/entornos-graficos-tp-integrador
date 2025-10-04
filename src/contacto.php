<?php
// Incluir el archivo de encabezado
require_once 'includes/header.php';

// Variables para mensajes de estado
$success = '';
$error = '';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar y sanear los datos del formulario
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $email = htmlspecialchars(trim($_POST['email']));
    $asunto = htmlspecialchars(trim($_POST['asunto']));
    $mensaje = htmlspecialchars(trim($_POST['mensaje']));

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($email) || empty($asunto) || empty($mensaje)) {
        $error = "Por favor, completa todos los campos del formulario.";
    } else {
        // En un entorno real, aquí iría la lógica para enviar el correo
        // Por ejemplo, usando la función mail() o una librería como PHPMailer
        // mail("info@shoppingrosario.com", "Contacto: " . $asunto, $mensaje . " De: " . $nombre);

        // Mensaje de éxito simulado
        $success = "¡Gracias! Tu mensaje ha sido enviado con éxito.";

        // Puedes opcionalmente limpiar el formulario
        $nombre = '';
        $email = '';
        $asunto = '';
        $mensaje = '';
    }
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center">Contacto</h2>
                    <p class="text-center">¿Tenés alguna consulta? Escribinos</p>

                    <?php if ($success): ?>
                        <div class="alert alert-success" role="alert">
                            <?= $success ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                value="<?= htmlspecialchars($nombre ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= htmlspecialchars($email ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="asunto" class="form-label">Asunto</label>
                            <select class="form-select" id="asunto" name="asunto" required>
                                <option value="">Seleccionar asunto</option>
                                <option value="soporte" <?= ($asunto ?? '') == 'soporte' ? 'selected' : '' ?>>Soporte
                                    técnico</option>
                                <option value="sugerencia" <?= ($asunto ?? '') == 'sugerencia' ? 'selected' : '' ?>>
                                    Sugerencia</option>
                                <option value="queja" <?= ($asunto ?? '') == 'queja' ? 'selected' : '' ?>>Queja o reclamo
                                </option>
                                <option value="otros" <?= ($asunto ?? '') == 'otros' ? 'selected' : '' ?>>Otros</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="mensaje" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="mensaje" name="mensaje" rows="5"
                                required><?= htmlspecialchars($mensaje ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enviar mensaje</button>
                    </form>

                    <div class="mt-4">
                        <h5>Información de contacto</h5>
                        <p>📍 Shopping Rosario - San Martín 1234</p>
                        <p>📞 (341) 123-4567</p>
                        <p>✉️ info@shoppingrosario.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>