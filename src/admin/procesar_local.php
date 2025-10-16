<?php
// admin/procesar_local.php

session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess([USER_ADMIN]);

$database = new Database();
$conn = $database->getConnection();

$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($action)) {
    try {
        switch ($action) {
            case 'crear':
                $nombre = trim($_POST['nombre']);
                $ubicacion = trim($_POST['ubicacion']);
                $rubro = trim($_POST['rubro']);
                $dueno_id = intval($_POST['dueno_id']);
                $estado = $_POST['estado'];

                // Verificar que el dueño no tenga ya un local
                $check_query = "SELECT codLocal FROM locales WHERE codUsuario = :dueno_id";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bindParam(':dueno_id', $dueno_id);
                $check_stmt->execute();

                if ($check_stmt->rowCount() > 0) {
                    throw new Exception("El dueño seleccionado ya tiene un local asignado.");
                }

                // Insertar nuevo local
                $query = "INSERT INTO locales (nombreLocal, ubicacionLocal, rubroLocal, codUsuario, estado, fechaCreacion) 
                          VALUES (:nombre, :ubicacion, :rubro, :dueno_id, :estado, NOW())";

                $stmt = $conn->prepare($query);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':ubicacion', $ubicacion);
                $stmt->bindParam(':rubro', $rubro);
                $stmt->bindParam(':dueno_id', $dueno_id);
                $stmt->bindParam(':estado', $estado);

                if ($stmt->execute()) {
                    $_SESSION['swal'] = [
                        'icon' => 'success',
                        'title' => '¡Éxito!',
                        'text' => "Local {$nombre} creado exitosamente.",
                        'action' => 'crear'
                    ];
                } else {
                    throw new Exception("Error al ejecutar la creación del local.");
                }
                break;

            case 'editar':
                $codLocal = intval($_POST['codLocal']);
                $nombre = trim($_POST['nombre']);
                $ubicacion = trim($_POST['ubicacion']);
                $rubro = trim($_POST['rubro']);
                $dueno_id = intval($_POST['dueno_id']);
                $estado = $_POST['estado'];

                // Verificar que el dueño no esté asignado a OTRO local
                $check_query = "SELECT codLocal FROM locales WHERE codUsuario = :dueno_id AND codLocal != :codLocal";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bindParam(':dueno_id', $dueno_id);
                $check_stmt->bindParam(':codLocal', $codLocal);
                $check_stmt->execute();

                if ($check_stmt->rowCount() > 0) {
                    throw new Exception("El dueño seleccionado ya está asignado a otro local.");
                }

                // Actualizar el local
                $query = "UPDATE locales 
                          SET nombreLocal = :nombre, ubicacionLocal = :ubicacion, 
                              rubroLocal = :rubro, codUsuario = :dueno_id, estado = :estado 
                          WHERE codLocal = :codLocal";

                $stmt = $conn->prepare($query);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':ubicacion', $ubicacion);
                $stmt->bindParam(':rubro', $rubro);
                $stmt->bindParam(':dueno_id', $dueno_id);
                $stmt->bindParam(':estado', $estado);
                $stmt->bindParam(':codLocal', $codLocal);

                if ($stmt->execute()) {
                    $_SESSION['swal'] = [
                        'icon' => 'success',
                        'title' => '¡Actualizado!',
                        'text' => "Local #{$codLocal} ({$nombre}) actualizado exitosamente.",
                        'action' => 'editar'
                    ];
                } else {
                    throw new Exception("Error al ejecutar la actualización del local.");
                }
                break;

            case 'eliminar':
                $codLocal = intval($_POST['codLocal']);
                $nombre = trim($_POST['nombre']); // Asegúrate de enviar el nombre desde el formulario

                // Verificar si tiene promociones activas
                $check_promos = "SELECT COUNT(*) as total FROM promociones WHERE codLocal = :codLocal AND estadoPromo = 'aprobada'";
                $stmt_check = $conn->prepare($check_promos);
                $stmt_check->bindParam(':codLocal', $codLocal);
                $stmt_check->execute();
                $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

                if ($result['total'] > 0) {
                    throw new Exception("No se puede eliminar el local porque tiene promociones activas asignadas.");
                }

                // Eliminar el local
                $query = "DELETE FROM locales WHERE codLocal = :codLocal";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':codLocal', $codLocal);

                if ($stmt->execute()) {
                    $_SESSION['swal'] = [
                        'icon' => 'success',
                        'title' => '¡Eliminado!',
                        'text' => "Local #{$codLocal} ({$nombre}) eliminado exitosamente.",
                        'action' => 'eliminar'
                    ];
                } else {
                    throw new Exception("Error al eliminar el local.");
                }
                break;

            default:
                throw new Exception("Acción no válida.");
        }
    } catch (Exception $e) {
        $_SESSION['swal'] = [
            'icon' => 'error',
            'title' => 'Error',
            'text' => $e->getMessage(),
            'action' => 'error'
        ];
    }
} else {
    $_SESSION['swal'] = [
        'icon' => 'error',
        'title' => 'Error',
        'text' => "Acceso no autorizado al procesamiento de locales.",
        'action' => 'error'
    ];
}

// Redirigir siempre a la página de gestión
header('Location: gestion-locales.php');
exit;
?>