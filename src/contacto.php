<?php
require_once 'includes/config.php';
$pageTitle = "Contacto - Bandera Shopping";
require_once 'includes/header.php';
?>

<!-- Hero Contacto -->
<div class="hero-contacto py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="contacto-title">¿Tienes alguna pregunta?</h1>
                <p class="contacto-subtitle">Estamos aquí para ayudarte. Contacta con nosotros de cualquier forma que
                    prefieras.</p>
            </div>
        </div>
    </div>
</div>

<!-- Contacto Container -->
<section class="contacto-section py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Información de Contacto -->
            <div class="col-lg-4">
                <div class="contacto-info-card">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="info-title">Ubicación</h3>
                    <p class="info-text">
                        Bandera Shopping<br>
                        Av. Principal 1234<br>
                        Buenos Aires, Argentina
                    </p>
                </div>

                <div class="contacto-info-card">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3 class="info-title">Teléfono</h3>
                    <p class="info-text">
                        <a href="tel:+5491123456789">+54 (911) 2345-6789</a><br>
                        <a href="tel:+5493414123456">(0341) 412-3456</a>
                    </p>
                </div>

                <div class="contacto-info-card">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 class="info-title">Email</h3>
                    <p class="info-text">
                        <a href="mailto:info@banderashopping.com">info@banderashopping.com</a><br>
                        <a href="mailto:soporte@banderashopping.com">soporte@banderashopping.com</a>
                    </p>
                </div>

                <div class="contacto-info-card">
                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="info-title">Horario</h3>
                    <p class="info-text">
                        Lunes - Viernes: 10:00 - 20:00<br>
                        Sábado: 10:00 - 21:00<br>
                        Domingo: 10:00 - 20:00
                    </p>
                </div>
            </div>

            <!-- Formulario de Contacto -->
            <div class="col-lg-8">
                <div class="contacto-form-card">
                    <h2 class="form-title">Envíanos un mensaje</h2>
                    <p class="form-subtitle">Te responderemos en el menor tiempo posible</p>

                    <form class="contacto-form" id="contactoForm" method="POST" action="#">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required
                                    placeholder="Tu nombre completo">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    placeholder="tu@email.com">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="telefono" class="form-label">Teléfono (Opcional)</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono"
                                placeholder="+54 (911) 2345-6789">
                        </div>

                        <div class="form-group">
                            <label for="asunto" class="form-label">Asunto</label>
                            <select class="form-control" id="asunto" name="asunto" required>
                                <option value="">Selecciona un asunto</option>
                                <option value="consulta">Consulta General</option>
                                <option value="promocion">Promociones</option>
                                <option value="local">Información de Locales</option>
                                <option value="soporte">Soporte Técnico</option>
                                <option value="sugerencia">Sugerencia o Comentario</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="mensaje" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required
                                placeholder="Cuéntanos cómo podemos ayudarte..."></textarea>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="terminos" name="terminos" required>
                            <label class="form-check-label" for="terminos">
                                Acepto los <a href="#" class="link-terminos">términos y condiciones</a>
                            </label>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane me-2"></i>Enviar Mensaje
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mapa -->
<section class="mapa-section py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">Encuéntranos</h2>
        <div class="mapa-container">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3284.0167396244504!2d-58.38160252346943!3d-34.60368627282481!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95a3350b61fb651d%3A0x8c9e5a7e1b7b5f5d!2sBuenos%20Aires%2C%20Argentina!5e0!3m2!1ses!2sar!4v1234567890"
                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>

<!-- Redes Sociales -->
<section class="redes-section py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-12">
                <h2 class="section-title mb-4">Síguenos</h2>
                <p class="section-subtitle mb-4">Mantente conectado con nosotros en redes sociales</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-auto">
                <div class="redes-container">
                    <a href="#" class="red-link facebook" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="red-link instagram" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="red-link twitter" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="red-link whatsapp" title="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="#" class="red-link linkedin" title="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<link rel="stylesheet" href="css/contacto.css">


<?php
require_once 'includes/footer.php';
?>