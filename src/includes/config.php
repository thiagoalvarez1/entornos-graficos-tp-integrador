<?php
// includes/config.php (VERSIÓN SEGURA para GitHub)

// Configuración de la base de datos (cambiar en cada instalación)
define('DB_HOST', 'localhost');
define('DB_NAME', 'shopping_promos');
define('DB_USER', 'TU_USUARIO');      // ← Cambiar por cada instalación
define('DB_PASS', 'TU_PASSWORD');     // ← Cambiar por cada instalación

// Configuración de la aplicación
define('SITE_URL', 'http://localhost/shopping-promos/src/');

// Tipos de usuario
define('USER_ADMIN', 'administrador');
define('USER_OWNER', 'dueño de local');
define('USER_CLIENT', 'cliente');

// Categorías de cliente
define('CATEGORY_INITIAL', 'Inicial');
define('CATEGORY_MEDIUM', 'Medium');
define('CATEGORY_PREMIUM', 'Premium');

// Iniciar sesión
session_start();
?>