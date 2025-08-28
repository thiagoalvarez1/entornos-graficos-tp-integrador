<?php
require_once 'includes/database.php';

$database = new Database();
$conn = $database->getConnection();

if (!$conn) {
    die("❌ Error de conexión a la base de datos");
}

echo "<h2>🔄 Reseteo de Contraseñas</h2>";

// Resetear contraseña del admin
$nuevaPassword = password_hash('admin123', PASSWORD_DEFAULT);
$email = 'admin@promoshopping.com';

$query = "UPDATE usuarios SET claveUsuario = :password WHERE nombreUsuario = :email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':password', $nuevaPassword);
$stmt->bindParam(':email', $email);

if ($stmt->execute()) {
    echo "✅ Contraseña reseteada correctamente para: <strong>admin@promoshopping.com</strong><br>";
    echo "Nueva contraseña: <strong>admin123</strong><br><br>";
} else {
    echo "❌ Error al resetear contraseña para admin<br>";
}

// Resetear otros usuarios de prueba
$usuarios = [
    ['cliente@ejemplo.com', 'password'],
    ['dueno@ejemplo.com', 'password']
];

foreach ($usuarios as $usuario) {
    $hash = password_hash($usuario[1], PASSWORD_DEFAULT);
    $query = "UPDATE usuarios SET claveUsuario = :password WHERE nombreUsuario = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':password', $hash);
    $stmt->bindParam(':email', $usuario[0]);

    if ($stmt->execute()) {
        echo "✅ Contraseña reseteada para: <strong>{$usuario[0]}</strong><br>";
    } else {
        echo "❌ Error al resetear: {$usuario[0]}<br>";
    }
}

echo "<hr>";
echo "<h3>🔑 Credenciales de prueba:</h3>";
echo "<ul>";
echo "<li><strong>Admin:</strong> admin@promoshopping.com / admin123</li>";
echo "<li><strong>Cliente:</strong> cliente@ejemplo.com / password</li>";
echo "<li><strong>Dueño:</strong> dueno@ejemplo.com / password</li>";
echo "</ul>";

echo "<p><a href='login.php'>➡️ Ir al Login</a></p>";
?>