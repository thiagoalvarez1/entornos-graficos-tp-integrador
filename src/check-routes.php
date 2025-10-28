<?php
require_once 'includes/config.php';

echo "<h2>Verificación de Rutas</h2>";
echo "<style>body {font-family: Arial; margin: 20px;} .ok {color: green;} .error {color: red;}</style>";

echo "<p>SITE_URL: <strong>" . SITE_URL . "</strong></p>";

// Verificar archivos esenciales
$archivos = [
    'includes/config.php',
    'includes/database.php',
    'includes/auth.php',
    'login.php',
    'registro.php',
    'admin/panel.php',
    'dueno/panel.php',
    'cliente/panel.php',
    'logout.php'
];

echo "<h3>Archivos esenciales:</h3>";
foreach ($archivos as $archivo) {
    $ruta_completa = $_SERVER['DOCUMENT_ROOT'] . '/shopping-promos/src/' . $archivo;
    echo $archivo . ": " . (file_exists($ruta_completa) ?
        "<span class='ok'>Existe</span>" :
        "<span class='error'>Faltante</span>") . "<br>";
}

// Verificar rutas de redirección
echo "<h3>Rutas de redirección:</h3>";
$rutas = [
    'Admin' => SITE_URL . 'admin/panel.php',
    'Dueño' => SITE_URL . 'dueno/panel.php',
    'Cliente' => SITE_URL . 'cliente/panel.php',
    'Login' => SITE_URL . 'login.php',
    'Registro' => SITE_URL . 'registro.php',
    'Logout' => SITE_URL . 'logout.php'
];

foreach ($rutas as $nombre => $ruta) {
    echo $nombre . ": <a href='" . $ruta . "' target='_blank'>" . $ruta . "</a><br>";
}

// Probar includes
echo "<h3>🧩 Includes:</h3>";
try {
    require_once 'includes/database.php';
    echo "includes/database.php: <span class='ok'>OK</span><br>";

    require_once 'includes/auth.php';
    echo "includes/auth.php: <span class='ok'>OK</span><br>";

    $database = new Database();
    $conn = $database->getConnection();
    echo "Conexión BD: " . ($conn ? "<span class='ok'>OK</span>" : "<span class='error'>Error</span>") . "<br>";

} catch (Exception $e) {
    echo "<span class='error'>Error en includes: " . $e->getMessage() . "</span><br>";
}

echo "<hr>";
echo "<h3>Pruebas rápidas:</h3>";
echo "<ul>";
echo "<li><a href='" . SITE_URL . "reset-password.php'>Resetear contraseñas</a></li>";
echo "<li><a href='" . SITE_URL . "debug-login.php'>Debug login</a></li>";
echo "<li><a href='" . SITE_URL . "login.php'>Página de login</a></li>";
echo "</ul>";
?>