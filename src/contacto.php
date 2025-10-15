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

<style>
    :root {
        --primary-color: #6366f1;
        --primary-dark: #4f46e5;
        --secondary-color: #ec4899;
        --accent-color: #f59e0b;
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --bg-light: #f8fafc;
        --white: #ffffff;
        --border-radius: 12px;
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    /* Hero Contacto */
    .hero-contacto {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 50%, var(--secondary-color) 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .hero-contacto::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    }

    .contacto-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .contacto-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    /* Contacto Section */
    .contacto-section {
        background: var(--bg-light);
    }

    .contacto-info-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        text-align: center;
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
        border-left: 4px solid var(--primary-color);
    }

    .contacto-info-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .info-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        margin: 0 auto 1rem;
    }

    .info-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .info-text {
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.6;
        margin: 0;
    }

    .info-text a {
        color: var(--primary-color);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .info-text a:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    /* Formulario */
    .contacto-form-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-md);
    }

    .form-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .form-subtitle {
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-group {
        margin-bottom: 1.2rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.95rem;
        color: var(--text-primary);
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .form-control::placeholder {
        color: var(--text-secondary);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 120px;
    }

    .form-check {
        display: flex;
        align-items: center;
    }

    .form-check-input {
        width: 20px;
        height: 20px;
        margin-right: 0.75rem;
        cursor: pointer;
        accent-color: var(--primary-color);
    }

    .form-check-label {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin: 0;
        cursor: pointer;
    }

    .link-terminos {
        color: var(--primary-color);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .link-terminos:hover {
        text-decoration: underline;
        color: var(--primary-dark);
    }

    .btn-submit {
        width: 100%;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-color));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    /* Mapa */
    .mapa-section {
        background: white;
    }

    .section-title {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--text-primary);
    }

    .section-subtitle {
        font-size: 0.95rem;
        color: var(--text-secondary);
    }

    .mapa-container {
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    /* Redes Sociales */
    .redes-section {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .redes-section .section-title {
        color: white;
    }

    .redes-section .section-subtitle {
        color: rgba(255, 255, 255, 0.9);
    }

    .redes-container {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .red-link {
        width: 55px;
        height: 55px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        font-size: 1.5rem;
        transition: all 0.3s ease;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .red-link:hover {
        background: white;
        color: var(--primary-color);
        transform: translateY(-4px);
        border-color: white;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .hero-contacto {
            padding: 3rem 0;
        }

        .contacto-title {
            font-size: 2rem;
        }

        .contacto-form-card {
            padding: 1.5rem;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .mapa-container iframe {
            height: 350px;
        }
    }

    @media (max-width: 768px) {
        .contacto-title {
            font-size: 1.6rem;
        }

        .contacto-subtitle {
            font-size: 0.95rem;
        }

        .contacto-section {
            padding: 2rem 0;
        }

        .contacto-info-card {
            padding: 1.2rem;
            margin-bottom: 1rem;
        }

        .contacto-form-card {
            padding: 1.2rem;
        }

        .form-title {
            font-size: 1.4rem;
        }

        .section-title {
            font-size: 1.8rem;
        }

        .mapa-container iframe {
            height: 300px;
        }

        .redes-container {
            gap: 1rem;
        }

        .red-link {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }
    }

    @media (max-width: 576px) {
        .contacto-title {
            font-size: 1.4rem;
            margin-bottom: 0.8rem;
        }

        .contacto-subtitle {
            font-size: 0.9rem;
        }

        .form-label {
            font-size: 0.85rem;
        }

        .form-control {
            padding: 0.6rem;
            font-size: 0.9rem;
        }

        .btn-submit {
            padding: 10px 16px;
            font-size: 0.9rem;
        }

        .form-title {
            font-size: 1.2rem;
            margin-bottom: 0.8rem;
        }

        .form-subtitle {
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: 1.5rem;
        }

        .section-subtitle {
            font-size: 0.9rem;
        }

        .mapa-container iframe {
            height: 250px;
        }

        .info-icon {
            width: 45px;
            height: 45px;
            font-size: 1.2rem;
        }

        .redes-container {
            gap: 0.8rem;
        }

        .red-link {
            width: 45px;
            height: 45px;
            font-size: 1rem;
        }
    }
</style>

<?php
require_once 'includes/footer.php';
?>