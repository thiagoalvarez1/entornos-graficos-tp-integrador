<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess([USER_OWNER]);

$database = new Database();
$conn = $database->getConnection();
$user_id = $_SESSION['user_id'];

// Obtener el local del dueño
$query_local = "SELECT * FROM locales WHERE codUsuario = :user_id";
$stmt_local = $conn->prepare($query_local);
$stmt_local->bindParam(':user_id', $user_id);
$stmt_local->execute();
$local = $stmt_local->fetch(PDO::FETCH_ASSOC);

$success = '';
$error = '';

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $ubicacion = trim($_POST['ubicacion']);
    $rubro = trim($_POST['rubro']);


    // Validaciones
    if (empty($nombre) || empty($ubicacion) || empty($rubro)) {
        $error = "Todos los campos obligatorios deben ser completados";
    } elseif (strlen($nombre) < 3) {
        $error = "El nombre del local debe tener al menos 3 caracteres";
    } else {
        // Si ya existe, actualizar
        if ($local) {
            $query = "UPDATE locales SET 
                      nombreLocal = :nombre, 
                      ubicacionLocal = :ubicacion, 
                      rubroLocal = :rubro, 
                    
                      estado = 'pendiente'
                      WHERE codLocal = :local_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':ubicacion', $ubicacion);
            $stmt->bindParam(':rubro', $rubro);

            $stmt->bindParam(':local_id', $local['codLocal']);

            if ($stmt->execute()) {
                $success = "Local actualizado correctamente. Los cambios están pendientes de aprobación.";
                // Actualizar los datos locales
                $local['nombreLocal'] = $nombre;
                $local['ubicacionLocal'] = $ubicacion;
                $local['rubroLocal'] = $rubro;


                $local['estado'] = 'pendiente';
            } else {
                $error = "Error al actualizar el local";
            }
        } else {
            // Si no existe, crear uno nuevo
            $query = "INSERT INTO locales (nombreLocal, ubicacionLocal, rubroLocal, codUsuario, estado, fechaCreacion) 
                      VALUES (:nombre, :ubicacion, :rubro,  :user_id, 'pendiente', NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':ubicacion', $ubicacion);
            $stmt->bindParam(':rubro', $rubro);

            $stmt->bindParam(':user_id', $user_id);

            if ($stmt->execute()) {
                $success = "Local creado correctamente. Espera la aprobación del administrador.";
                // Recargar para mostrar los datos del nuevo local
                header('Location: mi_local.php');
                exit();
            } else {
                $error = "Error al crear el local";
            }
        }
    }
}

$pageTitle = "Mi Local";
require_once '../includes/header-panel.php';
?>

<style>
    :root {
        --primary-purple: #8B5CF6;
        --secondary-purple: #A855F7;
        --accent-green: #10B981;
        --accent-blue: #3B82F6;
        --accent-orange: #F59E0B;
        --accent-red: #EF4444;
        --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --card-bg: rgba(255, 255, 255, 0.95);
        --text-primary: #1F2937;
        --text-secondary: #6B7280;
        --border-color: rgba(255, 255, 255, 0.2);
        --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.1);
        --shadow-medium: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    body {
        background: var(--bg-gradient);
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .main-content-panel {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .page-header {
        background: var(--card-bg);
        border-radius: 24px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--border-color);
        backdrop-filter: blur(20px);
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .header-text h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .header-text p {
        color: var(--text-secondary);
        margin: 0.25rem 0 0 0;
        font-size: 0.875rem;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        color: var(--accent-green);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        color: var(--accent-red);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .alert-info {
        background: rgba(59, 130, 246, 0.1);
        color: var(--accent-blue);
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .alert-warning {
        background: rgba(245, 158, 11, 0.1);
        color: var(--accent-orange);
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .content-section {
        background: var(--card-bg);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--border-color);
        backdrop-filter: blur(20px);
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(139, 92, 246, 0.1);
    }

    .section-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pendiente {
        background: rgba(245, 158, 11, 0.1);
        color: var(--accent-orange);
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .status-activo {
        background: rgba(16, 185, 129, 0.1);
        color: var(--accent-green);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .status-rechazado {
        background: rgba(239, 68, 68, 0.1);
        color: var(--accent-red);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .info-card {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 1rem;
        background: rgba(139, 92, 246, 0.03);
        border-radius: 12px;
        border: 1px solid rgba(139, 92, 246, 0.1);
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .info-label i {
        color: var(--primary-purple);
        width: 16px;
    }

    .info-value {
        color: var(--text-secondary);
        text-align: right;
        max-width: 60%;
        font-size: 0.9rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    }

    .welcome-message {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-secondary);
    }

    .welcome-message i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: var(--primary-purple);
        opacity: 0.7;
    }

    .welcome-message h3 {
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .form-instructions {
        background: rgba(139, 92, 246, 0.05);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
    }

    .form-instructions h6 {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .form-instructions ul {
        color: var(--text-secondary);
        padding-left: 1.5rem;
        margin: 0;
        font-size: 0.875rem;
    }

    .form-instructions li {
        margin-bottom: 0.5rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-group label i {
        color: var(--primary-purple);
        width: 16px;
    }

    .required-indicator {
        color: var(--accent-red);
    }

    .form-control {
        padding: 0.75rem 1rem;
        border: 2px solid rgba(139, 92, 246, 0.1);
        border-radius: 12px;
        background: white;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-purple);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .is-valid {
        border-color: var(--accent-green) !important;
    }

    .is-invalid {
        border-color: var(--accent-red) !important;
    }

    .char-counter {
        font-size: 0.75rem;
        color: var(--text-secondary);
        text-align: right;
        margin-top: 0.25rem;
    }

    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .modal-content {
        background: var(--card-bg);
        border-radius: 20px;
        padding: 2rem;
        max-width: 600px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: var(--shadow-medium);
        border: 1px solid var(--border-color);
        backdrop-filter: blur(20px);
    }

    @media (max-width: 768px) {
        .main-content-panel {
            padding: 1rem;
        }

        .page-header {
            padding: 1.5rem;
        }

        .content-section {
            padding: 1.5rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .info-item {
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-value {
            max-width: 100%;
            text-align: left;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
</style>

<div class="main-content-panel">
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-store"></i>
            </div>
            <div class="header-text">
                <h1>Mi Local</h1>
                <p>Gestiona la información y configuración de tu local comercial</p>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= $success ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?= $error ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] == 'no_local'): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Para poder crear promociones, primero debes registrar la información de tu local.
        </div>
    <?php endif; ?>

    <!-- Contenido principal -->
    <div class="content-section">
        <?php if ($local): ?>
            <!-- Información del local existente -->
            <div class="section-header">
                <div class="section-info">
                    <div class="section-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3 class="section-title">Información del Local</h3>
                </div>
                <span class="status-badge status-<?= $local['estado'] ?>">
                    <?= ucfirst($local['estado']) ?>
                </span>
            </div>

            <?php if ($local['estado'] == 'pendiente'): ?>
                <div class="alert alert-info">
                    <i class="fas fa-clock"></i>
                    Tu local está pendiente de aprobación. No podrás crear promociones hasta que sea aprobado por el
                    administrador.
                </div>
            <?php elseif ($local['estado'] == 'rechazado'): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Tu local fue rechazado. Contacta al administrador para más información o actualiza los datos.
                </div>
            <?php endif; ?>

            <div class="info-card">
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-store"></i> Nombre del Local
                    </span>
                    <span class="info-value"><?= htmlspecialchars($local['nombreLocal']) ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-map-marker-alt"></i> Ubicación
                    </span>
                    <span class="info-value"><?= htmlspecialchars($local['ubicacionLocal']) ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-tags"></i> Rubro
                    </span>
                    <span class="info-value"><?= htmlspecialchars($local['rubroLocal']) ?></span>
                </div>


            </div>
            <!--
            <div
                style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 2px solid rgba(139, 92, 246, 0.1);">
                <p style="color: var(--text-secondary); margin-bottom: 1rem;">
                    <i class="fas fa-info-circle"></i>
                    Para modificar los datos de tu local, contacta al administrador del sistema.
                </p>
                <button type="button" class="btn-primary" onclick="toggleEditMode()">
                    <i class="fas fa-edit"></i> Solicitar Modificación
                </button>
            </div>
            -->

        <?php else: ?>
                <!-- Formulario para crear local -->
            <div class="section-header">
                <div class="section-info">
                    <div class="section-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <h3 class="section-title">Registrar Nuevo Local</h3>
                </div>
            </div>

            <div class="welcome-message">
                <i class="fas fa-store-alt"></i>
                <h3>¡Bienvenido!</h3>
                <p>Para comenzar a ofrecer promociones, primero necesitamos que registres la información de tu local.</p>
            </div>

            <div class="form-instructions">
                <h6><i class="fas fa-lightbulb"></i> Instrucciones</h6>
                <ul>
                    <li>Completa todos los campos obligatorios (*)</li>
                    <li>Asegúrate de que la información sea correcta</li>
                    <li>Tu local será revisado por un administrador antes de ser aprobado</li>
                    <li>Una vez aprobado, podrás crear promociones</li>
                </ul>
            </div>

            <form method="POST" id="localForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nombre">
                            <i class="fas fa-store"></i> Nombre del Local
                            <span class="required-indicator">*</span>
                        </label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required
                            placeholder="Ej: Fashion Store" minlength="3">
                    </div>

                    <div class="form-group">
                        <label for="rubro">
                            <i class="fas fa-tags"></i> Rubro
                            <span class="required-indicator">*</span>
                        </label>
                        <select name="rubro" id="rubro" class="form-control" required>
                            <option value="">Selecciona un rubro</option>
                            <option value="Indumentaria">Indumentaria</option>
                            <option value="Calzado">Calzado</option>
                            <option value="Electrónica">Electrónica</option>
                            <option value="Comida">Comida</option>
                            <option value="Perfumería">Perfumería</option>
                            <option value="Óptica">Óptica</option>
                            <option value="Hogar">Hogar</option>
                            <option value="Deportes">Deportes</option>
                            <option value="Libros">Libros</option>
                            <option value="Juguetes">Juguetes</option>
                            <option value="Otros">Otros</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ubicacion">
                            <i class="fas fa-map-marker-alt"></i> Ubicación en el Shopping
                            <span class="required-indicator">*</span>
                        </label>
                        <input type="text" name="ubicacion" id="ubicacion" class="form-control" required
                            placeholder="Ej: Planta Baja, Local 15">
                    </div>


                </div>

                <div style="text-align: center; margin-top: 2rem;">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-plus-circle"></i> Registrar Local
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <!-- Modal para solicitar modificación -->
    <div id="editRequestModal" class="modal-overlay">
        <div class="modal-content">
            <div class="section-header" style="margin-bottom: 1rem; padding-bottom: 1rem;">
                <div class="section-info">
                    <div class="section-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <h3 class="section-title">Solicitar Modificación</h3>
                </div>
            </div>

            <form method="POST" id="editRequestForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit_nombre">
                            <i class="fas fa-store"></i> Nombre del Local
                            <span class="required-indicator">*</span>
                        </label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required
                            value="<?= htmlspecialchars($local['nombreLocal'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="edit_rubro">
                            <i class="fas fa-tags"></i> Rubro
                            <span class="required-indicator">*</span>
                        </label>
                        <select name="rubro" id="edit_rubro" class="form-control" required>
                            <option value="">Selecciona un rubro</option>
                            <option value="Indumentaria" <?= ($local['rubroLocal'] ?? '') == 'Indumentaria' ? 'selected' : '' ?>>Indumentaria</option>
                            <option value="Calzado" <?= ($local['rubroLocal'] ?? '') == 'Calzado' ? 'selected' : '' ?>>
                                Calzado</option>
                            <option value="Electrónica" <?= ($local['rubroLocal'] ?? '') == 'Electrónica' ? 'selected' : '' ?>>Electrónica</option>
                            <option value="Comida" <?= ($local['rubroLocal'] ?? '') == 'Comida' ? 'selected' : '' ?>>Comida
                            </option>
                            <option value="Perfumería" <?= ($local['rubroLocal'] ?? '') == 'Perfumería' ? 'selected' : '' ?>>Perfumería</option>
                            <option value="Óptica" <?= ($local['rubroLocal'] ?? '') == 'Óptica' ? 'selected' : '' ?>>Óptica
                            </option>
                            <option value="Hogar" <?= ($local['rubroLocal'] ?? '') == 'Hogar' ? 'selected' : '' ?>>Hogar
                            </option>
                            <option value="Deportes" <?= ($local['rubroLocal'] ?? '') == 'Deportes' ? 'selected' : '' ?>>
                                Deportes</option>
                            <option value="Libros" <?= ($local['rubroLocal'] ?? '') == 'Libros' ? 'selected' : '' ?>>Libros
                            </option>
                            <option value="Juguetes" <?= ($local['rubroLocal'] ?? '') == 'Juguetes' ? 'selected' : '' ?>>
                                Juguetes</option>
                            <option value="Otros" <?= ($local['rubroLocal'] ?? '') == 'Otros' ? 'selected' : '' ?>>Otros
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_ubicacion">
                            <i class="fas fa-map-marker-alt"></i> Ubicación en el Shopping
                            <span class="required-indicator">*</span>
                        </label>
                        <input type="text" name="ubicacion" id="edit_ubicacion" class="form-control" required
                            value="<?= htmlspecialchars($local['ubicacionLocal'] ?? '') ?>">
                    </div>

                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                    <button type="button" onclick="toggleEditMode()" class="btn-primary"
                        style="background: rgba(107, 114, 128, 0.1); color: var(--text-secondary);">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Validación en tiempo real para formularios
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, select, textarea');

            inputs.forEach(input => {
                input.addEventListener('blur', function () {
                    validateField(this);
                });

                input.addEventListener('input', function () {
                    if (this.classList.contains('is-invalid')) {
                        validateField(this);
                    }
                });
            });

            form.addEventListener('submit', function (e) {
                let isValid = true;

                inputs.forEach(input => {
                    if (!validateField(input)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    return false;
                }

                // Mostrar estado de carga
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    const originalHTML = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

                    // Restaurar en caso de error del servidor
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalHTML;
                    }, 10000);
                }
            });
        });

        // Función de validación




        // Auto-focus en el primer campo
        const firstInput = document.querySelector('#nombre, #edit_nombre');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }

        // Formatear teléfono mientras se escribe
        const phoneInputs = document.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function () {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 0) {
                    if (value.startsWith('54')) {
                        value = '+' + value;
                    } else if (!value.startsWith('+')) {
                        value = '+54 ' + value;
                    }
                }
                this.value = value;
            });
        });

        // Contador de caracteres para descripción
        const descripcionInputs = document.querySelectorAll('textarea[name="descripcion"]');
        descripcionInputs.forEach(textarea => {
            const maxLength = 500;

            function updateCounter() {
                const currentLength = textarea.value.length;
                let counter = textarea.parentNode.querySelector('.char-counter');

                if (!counter) {
                    counter = document.createElement('div');
                    counter.className = 'char-counter';
                    counter.style.cssText = `
                    font-size: 0.75rem;
                    color: var(--text-secondary);
                    text-align: right;
                    margin-top: 0.25rem;
                `;
                    textarea.parentNode.appendChild(counter);
                }

                counter.textContent = `${currentLength}/${maxLength} caracteres`;

                if (currentLength > maxLength * 0.9) {
                    counter.style.color = 'var(--accent-orange)';
                } else if (currentLength > maxLength) {
                    counter.style.color = 'var(--accent-red)';
                } else {
                    counter.style.color = 'var(--text-secondary)';
                }
            }

            textarea.addEventListener('input', updateCounter);
            textarea.setAttribute('maxlength', maxLength);
            updateCounter(); // Inicializar
        });
    });

    // Función global para toggle del modal de edición
    function toggleEditMode() {
        const modal = document.getElementById('editRequestModal');
        if (modal.style.display === 'none' || modal.style.display === '') {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';

            // Focus en el primer campo
            setTimeout(() => {
                const firstInput = modal.querySelector('#edit_nombre');
                if (firstInput) firstInput.focus();
            }, 100);
        } else {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // Cerrar modal al hacer click fuera
    document.addEventListener('click', function (e) {
        const modal = document.getElementById('editRequestModal');
        if (e.target === modal) {
            toggleEditMode();
        }
    });

    // Cerrar modal con Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('editRequestModal');
            if (modal.style.display === 'flex') {
                toggleEditMode();
            }
        }
    });
</script>

<?php require_once '../includes/footer-panel.php'; ?>