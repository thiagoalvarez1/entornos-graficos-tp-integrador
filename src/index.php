<?php
// DATOS DE PRUEBA (simulando la base de datos)
$promociones = [
    [
        'textoPromo' => '20% de descuento en efectivo',
        'nombreLocal' => 'Tienda X',
        'fechaHastaPromo' => '2025-09-30',
        'categoriaCliente' => 'Inicial'
    ],
    [
        'textoPromo' => '2x1 en productos seleccionados',
        'nombreLocal' => 'Tienda Y', 
        'fechaHastaPromo' => '2025-10-15',
        'categoriaCliente' => 'Medium'
    ],
    [
        'textoPromo' => '30% off en segunda unidad',
        'nombreLocal' => 'Tienda Z',
        'fechaHastaPromo' => '2025-08-31',
        'categoriaCliente' => 'Premium'
    ]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Promociones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Shopping Promociones</a>
            <div class="navbar-nav">
                <a class="nav-link" href="login.php">Login</a>
                <a class="nav-link" href="register.php">Registrarse</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="text-center">Promociones Vigentes</h1>
        
        <div class="row mt-4">
            <?php foreach ($promociones as $promo): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $promo['textoPromo'] ?></h5>
                        <p class="card-text">Local: <?= $promo['nombreLocal'] ?></p>
                        <p class="card-text">Categoría: <?= $promo['categoriaCliente'] ?></p>
                        <p class="card-text">
                            <small class="text-muted">Válido hasta: <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?></small>
                        </p>
                        <a href="#" class="btn btn-primary">Usar promoción</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>