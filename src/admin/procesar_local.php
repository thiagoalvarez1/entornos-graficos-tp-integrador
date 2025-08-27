<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header('Location: ../login.php');
    exit;
}

// Simulación de procesamiento (luego con BD real)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'crear':
            // Procesar creación
            $_SESSION['success'] = "Local creado exitosamente";
            break;
            
        case 'editar':
            // Procesar edición
            $_SESSION['success'] = "Local actualizado exitosamente";
            break;
            
        case 'eliminar':
            // Procesar eliminación
            $_SESSION['success'] = "Local eliminado exitosamente";
            break;
    }
}

header('Location: gestion_locales.php');
exit;