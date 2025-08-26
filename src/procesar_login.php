<?php
session_start();
// require_once 'includes/config.php'; // Descomentar cuando tengas BD

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Validación básica
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Todos los campos son obligatorios";
        header('Location: login.php');
        exit;
    }
    
    // SIMULACIÓN (luego con BD)
    if ($email === 'admin@shopping.com' && $password === 'admin123') {
        $_SESSION['usuario'] = [
            'id' => 1,
            'email' => $email,
            'nombre' => 'Administrador',
            'rol' => 'administrador'
        ];
    } else {
        // Simulación de usuario normal
        $_SESSION['usuario'] = [
            'id' => 2,
            'email' => $email,
            'nombre' => 'Usuario Demo',
            'rol' => 'cliente'
        ];
    }
    
    header('Location: index.php');
    exit;
}
?>