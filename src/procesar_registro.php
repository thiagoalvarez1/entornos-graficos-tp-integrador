<?php
session_start();
// require_once 'includes/config.php'; // Descomentar cuando tengas BD

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $rol = trim($_POST['rol']);
    
    // Validación básica
    if (empty($nombre) || empty($email) || empty($password) || empty($rol)) {
        $_SESSION['error'] = "Todos los campos son obligatorios";
        header('Location: register.php');
        exit;
    }
    
    // SIMULACIÓN (luego con BD)
    $_SESSION['usuario'] = [
        'id' => rand(100, 1000),
        'nombre' => $nombre,
        'email' => $email,
        'rol' => $rol
    ];
    
    header('Location: index.php');
    exit;
}
?>