<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

session_start();
$auth = new Auth();

echo "<h2>🔍 Debug de Sesión y Autenticación</h2>";
echo "<pre>";

// Verificar datos de sesión
echo "SESSION DATA:\n";
print_r($_SESSION);

// Verificar usuario en BD
if (isset($_SESSION['user_id'])) {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT codUsuario, nombreUsuario, tipoUsuario FROM usuarios WHERE codUsuario = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nUSER FROM DB:\n";
    print_r($user);
}

echo "</pre>";

// Probar redirección manualmente
if (isset($_SESSION['user_type'])) {
    echo "<h3>🔀 Redirección manual:</h3>";
    echo "Tipo de usuario: <strong>" . $_SESSION['user_type'] . "</strong><br>";
    echo "Debería redirigir a: <strong>" . SITE_URL . $_SESSION['user_type'] . "/panel.php</strong>";
}
?>