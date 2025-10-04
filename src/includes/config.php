<?php
// includes/config.php

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'shopping_promos');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuración de la aplicación
define('SITE_URL', 'http://localhost/shopping-promos/src/');

// Tipos de usuario (EXACTAMENTE como en tu BD)
// En includes/config.php
define('USER_ADMIN', 'administrador');
define('USER_OWNER', 'dueño de local'); // Cambia a "dueño de local"
define('USER_CLIENT', 'cliente');
// Iniciar sesión de manera SEGURA (SOLO aquí)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>