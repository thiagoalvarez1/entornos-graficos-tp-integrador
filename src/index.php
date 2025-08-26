<?php
session_start();

// Datos de prueba (simulando BD) - ESTO DEBE ESTAR SIEMPRE
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

require_once 'includes/header.php';
?>

<div class="container mt-4">
    <div class="jumbotron bg-light p-5 rounded">
        <h1 class="display-4">¡Bienvenido a Shopping Promociones!</h1>
        <p class="lead">Descubre las mejores ofertas y descuentos en todos los locales de nuestro shopping</p>
        <hr class="my-4">
        <p>Regístrate para acceder a promociones exclusivas según tu categoría de cliente.</p>
    </div>

    <h1 class="text-center mb-4">Promociones Vigentes</h1>
    
    <div class="row">
        <?php foreach ($promociones as $promo): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary"><?= htmlspecialchars($promo['textoPromo']) ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted">Local: <?= htmlspecialchars($promo['nombreLocal']) ?></h6>
                    <p class="card-text">
                        <span class="badge bg-<?= 
                            ($promo['categoriaCliente'] == 'Premium') ? 'warning' : 
                            (($promo['categoriaCliente'] == 'Medium') ? 'info' : 'secondary') 
                        ?>">
                            <?= htmlspecialchars($promo['categoriaCliente']) ?>
                        </span>
                    </p>
                    <p class="card-text">
                        <small class="text-muted">Válido hasta: <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?></small>
                    </p>
                </div>
                <div class="card-footer">
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <a href="#" class="btn btn-success btn-sm">Usar promoción</a>
                    <?php else: ?>
                        <a href="register.php" class="btn btn-outline-primary btn-sm">Registrate para usar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<!-- Agregar después del jumbotron -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Buscar promociones..." id="buscadorPromociones">
            <button class="btn btn-outline-secondary" type="button">Buscar</button>
        </div>
    </div>
    <div class="col-md-6">
        <select class="form-select" id="filtroCategoria">
            <option value="">Todas las categorías</option>
            <option value="Inicial">Inicial</option>
            <option value="Medium">Medium</option>
            <option value="Premium">Premium</option>
        </select>
    </div>
</div>

<script>
// Búsqueda y filtrado simple
document.getElementById('buscadorPromociones').addEventListener('input', filtrarPromociones);
document.getElementById('filtroCategoria').addEventListener('change', filtrarPromociones);

function filtrarPromociones() {
    const texto = document.getElementById('buscadorPromociones').value.toLowerCase();
    const categoria = document.getElementById('filtroCategoria').value;
    const cards = document.querySelectorAll('.card');
    
    cards.forEach(card => {
        const textoCard = card.textContent.toLowerCase();
        const categoriaCard = card.querySelector('.badge').textContent;
        
        const coincideTexto = textoCard.includes(texto);
        const coincideCategoria = categoria === '' || categoriaCard === categoria;
        
        card.parentElement.style.display = (coincideTexto && coincideCategoria) ? 'block' : 'none';
    });
}
</script>

<?php require_once 'includes/footer.php'; ?>