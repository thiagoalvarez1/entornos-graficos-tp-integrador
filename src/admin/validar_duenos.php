<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/header.php';
?>

<div class="container mt-4">
    <h2>Validar Dueños de Locales</h2>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Local solicitado</th>
                    <th>Fecha registro</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>3</td>
                    <td>Carlos López</td>
                    <td>carlos@mail.com</td>
                    <td>Tienda de Deportes</td>
                    <td>26/08/2025</td>
                    <td><span class="badge bg-warning">Pendiente</span></td>
                    <td>
                        <button class="btn btn-sm btn-success">Aprobar</button>
                        <button class="btn btn-sm btn-danger">Rechazar</button>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Ana Martínez</td>
                    <td>ana@mail.com</td>
                    <td>Óptica Vision</td>
                    <td>25/08/2025</td>
                    <td><span class="badge bg-warning">Pendiente</span></td>
                    <td>
                        <button class="btn btn-sm btn-success">Aprobar</button>
                        <button class="btn btn-sm btn-danger">Rechazar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>