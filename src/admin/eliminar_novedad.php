<?php
// admin/eliminar_novedad.php
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/database.php';

$auth = new Auth();
$auth->checkAccess(['administrador']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Método no permitido";
    header('Location: gestion_novedades.php');
    exit();
}

if (!isset($_POST['id']) || empty($_POST['id'])) {
    $_SESSION['error'] = "ID no proporcionado";
    header('Location: gestion_novedades.php');
    exit();
}

$id_novedad = $_POST['id'];
$database = new Database();
$conn = $database->getConnection();

try {
    // Verificar si la novedad existe
    $query = "SELECT * FROM novedades WHERE codNovedad = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id_novedad);
    $stmt->execute();
    $novedad = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$novedad) {
        $_SESSION['error'] = "La novedad no fue encontrada";
        header('Location: gestion_novedades.php');
        exit();
    }

    // Eliminar la novedad
    $query_delete = "DELETE FROM novedades WHERE codNovedad = :id";
    $stmt_delete = $conn->prepare($query_delete);
    $stmt_delete->bindParam(':id', $id_novedad);

    if ($stmt_delete->execute()) {
        $_SESSION['success'] = "Novedad eliminada exitosamente";
        header('Location: gestion_novedades.php');
    } else {
        $_SESSION['error'] = "Error al eliminar la novedad";
        header('Location: gestion_novedades.php');
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Error de base de datos: " . $e->getMessage();
    header('Location: gestion_novedades.php');
}
exit();
?>