<?php
require_once 'includes/auth.php';

$auth = new Auth();

// Redirigir al usuario según su tipo
$auth->redirectUser();
?>