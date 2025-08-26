<?php
require_once 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center">Contacto</h2>
                    <p class="text-center">¿Tenés alguna consulta? Escribinos</p>
                    
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Asunto</label>
                            <select class="form-select" required>
                                <option value="">Seleccionar asunto</option>
                                <option value="soporte">Soporte técnico</option>
                                <option value="sugerencia">Sugerencia</option>
                                <option value="queja">Queja o reclamo</option>
                                <option value="otros">Otros</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mensaje</label>
                            <textarea class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enviar mensaje</button>
                    </form>
                    
                    <div class="mt-4">
                        <h5>Información de contacto</h5>
                        <p>📍 Shopping Rosario - San Martín 1234</p>
                        <p>📞 (341) 123-4567</p>
                        <p>✉️ info@shoppingrosario.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>