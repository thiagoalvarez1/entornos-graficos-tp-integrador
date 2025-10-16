<?php
// includes/config.php

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'shopping_promos');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuración de la aplicación
define('SITE_URL', 'http://localhost/shopping-promos/src/');

// Tipos de usuario
define('USER_ADMIN', 'administrador');
define('USER_OWNER', 'dueño de local');
define('USER_CLIENT', 'cliente');

// Configuración para verificación de email
define('EMAIL_VERIFICATION_REQUIRED', true);
define('VERIFICATION_TOKEN_EXPIRY', 24 * 60 * 60); // 24 horas
define('SITE_NAME', 'Bandera Shopping');

// Estados de usuario
define('USER_STATUS_PENDING', 'pendiente');
define('USER_STATUS_ACTIVE', 'activo');
define('USER_STATUS_INACTIVE', 'inactivo');
define('USER_STATUS_UNVERIFIED', 'no_verificado');

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'tu-email@gmail.com'); // CAMBIA esto
define('SMTP_PASSWORD', 'tu-password-de-aplicacion'); // Password de aplicación
define('SMTP_FROM_EMAIL', 'tu-email@gmail.com'); // CAMBIA esto
define('SMTP_FROM_NAME', 'Bandera Shopping');



// Iniciar sesión de manera SEGURA (SOLO aquí)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>