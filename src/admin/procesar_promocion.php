<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

// Simulación de procesamiento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'crear';
    
    switch ($action) {
        case 'crear':
            $_SESSION['success'] = "Promoción creada exitosamente";
            break;
            
        case 'editar':
            $_SESSION['success'] = "Promoción actualizada exitosamente";
            break;
            
        case 'eliminar':
            $_SESSION['success'] = "Promoción eliminada exitosamente";
            break;
            
        case 'aprobar':
            $_SESSION['success'] = "Promoción aprobada exitosamente";
            break;
            
        case 'rechazar':
            $_SESSION['success'] = "Promoción rechazada exitosamente";
            break;
    }
}

header('Location: gestion_promociones.php');
exit;