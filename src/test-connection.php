<?php
require_once 'includes/database.php';

$database = new Database();
$conn = $database->getConnection();

if ($conn) {
    echo "✅ Conexión exitosa a la base de datos!<br>";
    
    // Verificar si las tablas existen
    $query = "SHOW TABLES";
    $stmt = $conn->query($query);
    $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "📊 Tablas encontradas: " . implode(", ", $tablas);
    
    // Verificar usuarios existentes
    echo "<br><br>👥 Usuarios en la base de datos:";
    $query = "SELECT codUsuario, nombreUsuario, tipoUsuario FROM usuarios";
    $stmt = $conn->query($query);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<ul>";
    foreach ($usuarios as $usuario) {
        echo "<li>ID: {$usuario['codUsuario']} - Email: {$usuario['nombreUsuario']} - Tipo: {$usuario['tipoUsuario']}</li>";
    }
    echo "</ul>";
    
} else {
    echo "❌ Error de conexión. Revisa la configuración en includes/config.php";
}
?>