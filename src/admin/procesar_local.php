<?php
// admin/procesar_local.php

session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess([USER_ADMIN]); // Asegura que solo el admin puede procesar

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

                // 1. Verificar que el dueño no tenga ya un local
                $check_query = "SELECT codLocal FROM locales WHERE codUsuario = :dueno_id";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bindParam(':dueno_id', $dueno_id);
                $check_stmt->execute();

                if ($check_stmt->rowCount() > 0) {
                    throw new Exception("El dueño seleccionado ya tiene un local asignado. Por favor, seleccione otro.");
                }

                // 2. Insertar nuevo local
                $query = "INSERT INTO locales (nombreLocal, ubicacionLocal, rubroLocal, codUsuario, estado, fechaCreacion) 
                          VALUES (:nombre, :ubicacion, :rubro, :dueno_id, :estado, NOW())";

                $stmt = $conn->prepare($query);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':ubicacion', $ubicacion);
                $stmt->bindParam(':rubro', $rubro);
                $stmt->bindParam(':dueno_id', $dueno_id);
                $stmt->bindParam(':estado', $estado);

                if ($stmt->execute()) {
                    $_SESSION['success'] = "Local <strong>{$nombre}</strong> creado exitosamente.";
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

                // 1. Verificar que el dueño no esté asignado a OTRO local
                $check_query = "SELECT codLocal FROM locales WHERE codUsuario = :dueno_id AND codLocal != :codLocal";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bindParam(':dueno_id', $dueno_id);
                $check_stmt->bindParam(':codLocal', $codLocal);
                $check_stmt->execute();

                if ($check_stmt->rowCount() > 0) {
                    throw new Exception("El dueño seleccionado ya está asignado a otro local. Debe desasignarlo primero.");
                }

                // 2. Actualizar el local
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
                    $_SESSION['success'] = "Local **#{$codLocal} ({$nombre})** actualizado exitosamente.";
                } else {
                    throw new Exception("Error al ejecutar la actualización del local.");
                }
                break;

            case 'eliminar':
                $codLocal = intval($_POST['codLocal']);

                // **ATENCIÓN**: Considera el manejo de Claves Foráneas. 
                // Si tienes promociones o usos de promociones vinculados, la eliminación fallará.
                // Podrías: 
                // 1. Eliminar promociones relacionadas: 
                // $conn->exec("DELETE FROM promociones WHERE codLocal = $codLocal");
                // 2. O solo cambiar el estado a 'inactivo' en lugar de eliminar.

                // Aquí usamos una eliminación simple (asumiendo CASCADE DELETE en promociones o sin datos vinculados)
                $query = "DELETE FROM locales WHERE codLocal = :codLocal";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':codLocal', $codLocal);

                if ($stmt->execute()) {
                    $_SESSION['success'] = "Local #{$codLocal} eliminado exitosamente.";
                } else {
                    throw new Exception("Error al eliminar el local. Asegúrese de que no tenga promociones activas.");
                }
                break;

            default:
                throw new Exception("Acción no válida.");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Operación fallida: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "Acceso no autorizado al procesamiento de locales.";
}

// Redirigir siempre a la página de gestión
header('Location: gestion-locales.php');
exit;