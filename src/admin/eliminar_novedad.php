<?php
// eliminar_novedad.php - VERSIÓN MEJORADA
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess(['administrador']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: gestion_novedades.php?error=metodo_no_permitido');
    exit();
}

if (!isset($_POST['id']) || empty($_POST['id'])) {
    header('Location: gestion_novedades.php?error=id_no_proporcionado');
    exit();
}

$id_novedad = $_POST['id'];
$database = new Database();
$conn = $database->getConnection();

// Verificar si la novedad existe
$query = "SELECT * FROM novedades WHERE codNovedad = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $id_novedad);
$stmt->execute();
$novedad = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$novedad) {
    header('Location: gestion_novedades.php?error=novedad_no_encontrada');
    exit();
}

// Eliminar la novedad
try {
    $query_delete = "DELETE FROM novedades WHERE codNovedad = :id";
    $stmt_delete = $conn->prepare($query_delete);
    $stmt_delete->bindParam(':id', $id_novedad);

    if ($stmt_delete->execute()) {
        header('Location: gestion_novedades.php?success=novedad_eliminada');
    } else {
        header('Location: gestion_novedades.php?error=eliminacion_fallida');
    }
} catch (PDOException $e) {
    header('Location: gestion_novedades.php?error=error_basedatos');
}
exit();
?>