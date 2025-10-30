<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$database = new Database();
$conn = $database->getConnection();

// Verificar que el usuario sea cliente
$auth->checkAccess([USER_CLIENT]);

// Obtener ID de la promoción desde la URL
$codPromo = $_GET['id'] ?? null;

if (!$codPromo) {
    header('Location: promociones_disponibles.php?error=no_promocion');
    exit();
}

// Obtener información de la promoción
$query = "SELECT p.*, l.nombreLocal, l.ubicacionLocal, l.rubroLocal
          FROM promociones p
          JOIN locales l ON p.codLocal = l.codLocal
          WHERE p.codPromo = :codPromo AND p.estadoPromo = 'aprobada'";

$stmt = $conn->prepare($query);
$stmt->bindParam(':codPromo', $codPromo, PDO::PARAM_INT);
$stmt->execute();
$promocion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$promocion) {
    header('Location: promociones_disponibles.php?error=promocion_no_valida');
    exit();
}

// Verificar si el cliente ya solicitó esta promoción
$currentUser = $auth->getCurrentUser();
$codCliente = $currentUser['id'];

$queryCheck = "SELECT * FROM uso_promociones 
               WHERE codCliente = :codCliente AND codPromo = :codPromo";
$stmtCheck = $conn->prepare($queryCheck);
$stmtCheck->bindParam(':codCliente', $codCliente, PDO::PARAM_INT);
$stmtCheck->bindParam(':codPromo', $codPromo, PDO::PARAM_INT);
$stmtCheck->execute();

if ($stmtCheck->rowCount() > 0) {
    header('Location: mis_promociones.php?error=ya_solicitada');
    exit();
}

// Procesar solicitud
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $queryInsert = "INSERT INTO uso_promociones (codCliente, codPromo, fechaUsoPromo, estado) 
                       VALUES (:codCliente, :codPromo, NOW(), 'enviada')";
        $stmtInsert = $conn->prepare($queryInsert);
        $stmtInsert->bindParam(':codCliente', $codCliente, PDO::PARAM_INT);
        $stmtInsert->bindParam(':codPromo', $codPromo, PDO::PARAM_INT);

        if ($stmtInsert->execute()) {
            header('Location: mis_promociones.php?success=solicitud_enviada');
            exit();
        } else {
            $error = "Error al enviar la solicitud";
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

$pageTitle = "Solicitar Promoción";
require_once '../includes/header-panel.php';
?>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<div class="container-fluid py-4">
    <!-- Header de la página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Solicitar Promoción</h1>
            <p class="text-muted mb-0">Solicita usar esta promoción en el local</p>
        </div>
        <a href="promociones_disponibles.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Volver a promociones disponibles
        </a>
    </div>

    <!-- Alertas -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2" aria-hidden="true"></i>
            <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar alerta"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <!-- Información de la promoción -->
            <section aria-labelledby="detalles-promocion-titulo">
                <div class="card card-panel mb-4">
                    <div class="card-header bg-primary text-white">
                        <h2 id="detalles-promocion-titulo" class="h5 card-title mb-0">
                            <i class="fas fa-tag me-2" aria-hidden="true"></i>Detalles de la Promoción
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="h4 text-primary"><?= htmlspecialchars($promocion['textoPromo']) ?></h3>
                                <div class="mb-3">
                                    <span class="badge bg-info" role="status"
                                        aria-label="Categoría de cliente: <?= htmlspecialchars($promocion['categoriaCliente']) ?>">
                                        <i class="fas fa-user me-1" aria-hidden="true"></i>
                                        <?= htmlspecialchars($promocion['categoriaCliente']) ?>
                                    </span>
                                    <span class="badge bg-secondary ms-2" role="status"
                                        aria-label="Días válidos: <?= htmlspecialchars($promocion['diasSemana']) ?>">
                                        <i class="fas fa-calendar me-1" aria-hidden="true"></i>
                                        <?= htmlspecialchars($promocion['diasSemana']) ?>
                                    </span>
                                </div>
                                <p class="text-muted mb-0">
                                    <strong>Vigencia:</strong>
                                    <?= date('d/m/Y', strtotime($promocion['fechaDesdePromo'])) ?> -
                                    <?= date('d/m/Y', strtotime($promocion['fechaHastaPromo'])) ?>
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="bg-light p-3 rounded">
                                    <small class="text-muted">Estado</small>
                                    <div class="badge-status badge-approved mt-1" role="status"
                                        aria-label="Promoción disponible">Disponible</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Información del local -->
            <section aria-labelledby="informacion-local-titulo">
                <div class="card card-panel">
                    <div class="card-header">
                        <h2 id="informacion-local-titulo" class="h5 card-title mb-0">
                            <i class="fas fa-store me-2" aria-hidden="true"></i>Información del Local
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="h6 text-primary"><?= htmlspecialchars($promocion['nombreLocal']) ?></h3>
                                <p class="mb-1">
                                    <i class="fas fa-map-marker-alt text-muted me-2" aria-hidden="true"></i>
                                    <?= htmlspecialchars($promocion['ubicacionLocal']) ?>
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-tag text-muted me-2" aria-hidden="true"></i>
                                    <?= htmlspecialchars($promocion['rubroLocal']) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <h4 class="h6 mb-2">Instrucciones:</h4>
                                    <ul class="small mb-0" aria-label="Instrucciones para usar la promoción">
                                        <li>Presenta el código QR en el local</li>
                                        <li>Válido solo en fechas indicadas</li>
                                        <li>Sujeto a disponibilidad del local</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="col-lg-4">
            <!-- Panel de solicitud -->
            <section aria-labelledby="panel-solicitud-titulo">
                <div class="card card-panel">
                    <div class="card-header bg-success text-white">
                        <h2 id="panel-solicitud-titulo" class="h5 card-title mb-0">
                            <i class="fas fa-paper-plane me-2" aria-hidden="true"></i>Solicitar Promoción
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="fas fa-gift fa-3x text-success mb-3" aria-hidden="true"></i>
                            <h3 class="h5">¿Usar esta promoción?</h3>
                            <p class="text-muted small">
                                Al solicitar, el dueño del local revisará tu solicitud y te notificará el resultado.
                            </p>
                        </div>

                        <form method="POST" id="solicitudForm">
                            <div class="mb-3">
                                <label for="cliente_email" class="form-label">Cliente</label>
                                <input type="text" class="form-control" id="cliente_email"
                                    value="<?= htmlspecialchars($currentUser['email']) ?>" readonly
                                    aria-describedby="cliente_help">
                                <small id="cliente_help" class="form-text text-muted">
                                    Tu dirección de email registrada
                                </small>
                            </div>

                            <div class="mb-3">
                                <label for="fecha_solicitud" class="form-label">Fecha de Solicitud</label>
                                <input type="text" class="form-control" id="fecha_solicitud"
                                    value="<?= date('d/m/Y H:i') ?>" readonly aria-describedby="fecha_help">
                                <small id="fecha_help" class="form-text text-muted">
                                    Fecha y hora actual de la solicitud
                                </small>
                            </div>

                            <div class="alert alert-info small" role="note" aria-labelledby="info-importante">
                                <h4 id="info-importante" class="h6 mb-2">
                                    <i class="fas fa-info-circle me-2" aria-hidden="true"></i>
                                    Importante
                                </h4>
                                <p class="mb-0">Esta promoción está sujeta a disponibilidad y aprobación del local.</p>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="button" id="btnSolicitar" class="btn btn-success btn-lg">
                                    <i class="fas fa-paper-plane me-2" aria-hidden="true"></i>Enviar Solicitud
                                </button>
                                <a href="promociones_disponibles.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2" aria-hidden="true"></i>Cancelar y volver
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnSolicitar = document.getElementById('btnSolicitar');
        const form = document.getElementById('solicitudForm');
        const promocionNombre = "<?= htmlspecialchars($promocion['textoPromo']) ?>";
        const localNombre = "<?= htmlspecialchars($promocion['nombreLocal']) ?>";

        if (btnSolicitar && form) {
            btnSolicitar.addEventListener('click', function () {
                Swal.fire({
                    title: '¿Solicitar promoción?',
                    html: `
                        <div class="text-start">
                            <p>¿Estás seguro de que quieres solicitar esta promoción?</p>
                            <div class="alert alert-warning small mt-3 mb-0">
                                <i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>
                                <strong>Detalles de la solicitud:</strong>
                                <ul class="mb-0 mt-2 ps-3">
                                    <li><strong>Promoción:</strong> ${promocionNombre}</li>
                                    <li><strong>Local:</strong> ${localNombre}</li>
                                    <li>El dueño revisará tu solicitud</li>
                                    <li>Recibirás una notificación con el resultado</li>
                                </ul>
                            </div>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-paper-plane me-2"></i>Sí, enviar solicitud',
                    cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
                    reverseButtons: true,
                    backdrop: true,
                    allowOutsideClick: false,
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });
</script>

<?php require_once '../includes/footer-panel.php'; ?>