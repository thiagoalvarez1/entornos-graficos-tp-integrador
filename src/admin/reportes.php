<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess(['administrador']);

$database = new Database();
$conn = $database->getConnection();

// Obtener estadísticas
$query = "SELECT 
    (SELECT COUNT(*) FROM usuarios WHERE tipoUsuario = 'cliente') as total_clientes,
    (SELECT COUNT(*) FROM usuarios WHERE tipoUsuario = 'dueño de local') as total_duenos,
    (SELECT COUNT(*) FROM locales) as total_locales,
    (SELECT COUNT(*) FROM promociones WHERE estadoPromo = 'aprobada' AND fechaHastaPromo >= CURDATE()) as promociones_activas,
    (SELECT COUNT(*) FROM uso_promociones WHERE estado = 'aceptada' AND DATE(fechaUsoPromo) = CURDATE()) as usos_hoy";

$stats = $conn->query($query)->fetch(PDO::FETCH_ASSOC);

// Promociones más usadas
$query = "SELECT p.textoPromo, l.nombreLocal, COUNT(up.codUso) as total_usos
          FROM uso_promociones up
          JOIN promociones p ON up.codPromo = p.codPromo
          JOIN locales l ON p.codLocal = l.codLocal
          WHERE up.estado = 'aceptada'
          GROUP BY p.codPromo
          ORDER BY total_usos DESC
          LIMIT 5";
$top_promociones = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Reportes y Estadísticas";
require_once '../includes/header-panel.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Reportes y Estadísticas</h1>

    <!-- Estadísticas generales -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Clientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['total_clientes'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Dueños de Locales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['total_duenos'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-store fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Promociones Activas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['promociones_activas'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Usos Hoy</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['usos_hoy'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top promociones -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 5 Promociones Más Usadas</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($top_promociones)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Promoción</th>
                                    <th>Local</th>
                                    <th>Usos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($top_promociones as $promo): ?>
                                <tr>
                                    <td><?= htmlspecialchars($promo['textoPromo']) ?></td>
                                    <td><?= htmlspecialchars($promo['nombreLocal']) ?></td>
                                    <td><span class="badge bg-primary"><?= $promo['total_usos'] ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted">No hay datos de usos aún.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Exportar Reportes</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="exportar_reportes.php?tipo=promociones" class="list-group-item list-group-item-action">
                            <i class="fas fa-download me-2"></i>Exportar Promociones
                        </a>
                        <a href="exportar_reportes.php?tipo=usuarios" class="list-group-item list-group-item-action">
                            <i class="fas fa-download me-2"></i>Exportar Usuarios
                        </a>
                        <a href="exportar_reportes.php?tipo=locales" class="list-group-item list-group-item-action">
                            <i class="fas fa-download me-2"></i>Exportar Locales
                        </a>
                        <a href="exportar_reportes.php?tipo=usos" class="list-group-item list-group-item-action">
                            <i class="fas fa-download me-2"></i>Exportar Usos de Promociones
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer-panel.php'; ?>