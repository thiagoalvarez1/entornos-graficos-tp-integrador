<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$database = new Database();
$conn = $database->getConnection();

// Verificar que el usuario sea cliente
$auth->checkAccess([USER_CLIENT]);

$pageTitle = "Mis Promociones";
require_once '../includes/header-panel.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <?php include 'sidebar_cliente.php'; ?>
        </div>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <h1 class="h2">Mis Promociones</h1>
            <p>Aquí puedes ver el estado de las promociones que has solicitado.</p>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Fecha Solicitud</th>
                            <th>Local</th>
                            <th>Promoción</th>
                            <th>Estado</th>
                            <th>Código</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Obtener el ID del usuario actual
                        $currentUser = $auth->getCurrentUser();
                        $codCliente = $currentUser['id'];

                        // Consulta para obtener las promociones tomadas por el cliente
                        $query = "SELECT p.textoPromo, l.nombreLocal, u.fechaUsoPromo, u.estado, u.codUso
                                  FROM uso_promociones u
                                  JOIN promociones p ON u.codPromo = p.codPromo
                                  JOIN locales l ON p.codLocal = l.codLocal
                                  WHERE u.codCliente = :codCliente
                                  ORDER BY u.fechaUsoPromo DESC";

                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':codCliente', $codCliente, PDO::PARAM_INT);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            while ($uso = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $estadoClase = '';
                                switch ($uso['estado']) {
                                    case 'aceptada': // Cambiado de 'aceptado' a 'aceptada'
                                        $estadoClase = 'bg-success';
                                        break;
                                    case 'rechazada':
                                        $estadoClase = 'bg-danger';
                                        break;
                                    case 'enviada':
                                        $estadoClase = 'bg-warning';
                                        break;
                                }
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($uso['fechaUsoPromo']); ?></td>
                                    <td><?php echo htmlspecialchars($uso['nombreLocal']); ?></td>
                                    <td><?php echo htmlspecialchars($uso['textoPromo']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $estadoClase; ?>">
                                            <?php echo htmlspecialchars(ucfirst($uso['estado'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($uso['estado'] == 'aceptada'): ?>
                                            <span
                                                class="text-success fw-bold"><?php echo htmlspecialchars($uso['codUso']); ?></span>
                                        <?php else: ?>
                                            <span>No disponible</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>Aún no has solicitado ninguna promoción.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<?php require_once '../includes/footer-panel.php'; ?>