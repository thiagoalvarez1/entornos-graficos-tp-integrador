<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/auth.php';

// Iniciar sesión para la prueba
session_start();

echo "<h2>🔍 Diagnóstico del Sistema de Login</h2>";
echo "<style>
    body {font-family: Arial; margin: 20px; background: #f5f5f5;}
    .ok {color: green; font-weight: bold;}
    .error {color: red; font-weight: bold;}
    table {border-collapse: collapse; margin: 10px 0;}
    th, td {border: 1px solid #ccc; padding: 8px; text-align: left;}
    th {background: #eee;}
</style>";

// 1. Verificar conexión a BD
$database = new Database();
$conn = $database->getConnection();
echo "<p>1. Conexión BD: <span class='" . ($conn ? "ok'>✅ OK" : "error'>❌ FALLÓ") . "</span></p>";

// 2. Verificar configuración
echo "<p>2. Configuración actual:</p>";
echo "<ul>";
echo "<li>SITE_URL: <strong>" . SITE_URL . "</strong></li>";
echo "<li>USER_ADMIN: '" . USER_ADMIN . "'</li>";
echo "<li>USER_OWNER: '" . USER_OWNER . "'</li>";
echo "<li>USER_CLIENT: '" . USER_CLIENT . "'</li>";
echo "</ul>";

// 3. Verificar usuarios existentes
echo "<p>3. Usuarios en la base de datos:</p>";
try {
    $query = "SELECT codUsuario, nombreUsuario, claveUsuario, tipoUsuario FROM usuarios";
    $stmt = $conn->query($query);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($usuarios) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Email</th><th>Tipo</th><th>Contraseña (hash)</th></tr>";
        foreach ($usuarios as $usuario) {
            echo "<tr>";
            echo "<td>{$usuario['codUsuario']}</td>";
            echo "<td>{$usuario['nombreUsuario']}</td>";
            echo "<td>{$usuario['tipoUsuario']}</td>";
            echo "<td style='font-size: 10px;'>" . substr($usuario['claveUsuario'], 0, 30) . "...</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='error'>❌ No hay usuarios en la base de datos</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Error al leer usuarios: " . $e->getMessage() . "</p>";
}

// 4. Probar login con admin
echo "<p>4. Probando login con <strong>admin@promoshopping.com / admin123</strong>:</p>";
$auth = new Auth();
$result = $auth->login('admin@promoshopping.com', 'admin123');

if ($result === true) {
    echo "<p class='ok'>✅ Login exitoso</p>";
    echo "<p>Tipo de usuario en sesión: <strong>{$_SESSION['user_type']}</strong></p>";

    // Test redirección
    echo "<p>Redirección debería ser: ";
    switch ($_SESSION['user_type']) {
        case USER_ADMIN:
            echo "<strong>" . SITE_URL . "admin/panel.php</strong>";
            break;
        case USER_OWNER:
            echo "<strong>" . SITE_URL . "dueno/panel.php</strong>";
            break;
        case USER_CLIENT:
            echo "<strong>" . SITE_URL . "cliente/panel.php</strong>";
            break;
        default:
            echo "desconocido ({$_SESSION['user_type']})";
            break;
    }
    echo "</p>";

} else {
    echo "<p class='error'>❌ Error: " . $result . "</p>";
}

// 5. Verificar valores exactos en BD vs constantes
echo "<p>5. Verificación de valores:</p>";
try {
    $query = "SELECT DISTINCT tipoUsuario FROM usuarios";
    $stmt = $conn->query($query);
    $tiposBD = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "Tipos en BD: <strong>" . implode("</strong>, <strong>", $tiposBD) . "</strong><br>";
    echo "Coincide con USER_ADMIN ('" . USER_ADMIN . "'): " . (in_array(USER_ADMIN, $tiposBD) ? "✅" : "❌") . "<br>";
    echo "Coincide con USER_OWNER ('" . USER_OWNER . "'): " . (in_array(USER_OWNER, $tiposBD) ? "✅" : "❌") . "<br>";
    echo "Coincide con USER_CLIENT ('" . USER_CLIENT . "'): " . (in_array(USER_CLIENT, $tiposBD) ? "✅" : "❌") . "<br>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

echo "<hr>";
echo "<h3>🔗 Enlaces de prueba:</h3>";
echo "<ul>";
echo "<li><a href='" . SITE_URL . "reset-password.php'>Resetear contraseñas</a></li>";
echo "<li><a href='" . SITE_URL . "login.php'>Página de login</a></li>";
echo "<li><a href='" . SITE_URL . "registro.php'>Página de registro</a></li>";
echo "</ul>";
?>