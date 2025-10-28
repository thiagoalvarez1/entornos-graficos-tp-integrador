<?php
// Herramienta para identificar archivos con duplicados
echo "<h2>Buscando archivos con código duplicado</h2>";

$filesToCheck = [
    'index.php',
    'login.php',
    'registro.php',
    'admin/panel.php',
    'dueno/panel.php',
    'cliente/panel.php'
];

foreach ($filesToCheck as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);

        $hasDoctype = strpos($content, '<!DOCTYPE') !== false;
        $hasHtml = strpos($content, '<html') !== false;
        $hasHead = strpos($content, '<head') !== false;
        $hasBody = strpos($content, '</body>') !== false;

        if ($hasDoctype || $hasHtml || $hasHead || $hasBody) {
            echo "<p style='color: red;'>$file - TIENE CÓDIGO DUPLICADO</p>";
        } else {
            echo "<p style='color: green;'>$file - OK</p>";
        }
    }
}
?>