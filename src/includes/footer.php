</div><!-- Cierre del container del main content -->
</main>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <h5><i class="fas fa-store me-2"></i>PromoShopping</h5>
                <p>El mejor lugar para encontrar promociones exclusivas en tu shopping favorito. Descubre ofertas
                    increíbles y ahorra en tus compras.</p>
                <div class="social-links">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 mb-4">
                <h5>Enlaces Rápidos</h5>
                <ul class="footer-links">
                    <li><a href="<?php echo SITE_URL; ?>index.php"><i class="fas fa-home me-2"></i>Inicio</a></li>
                    <li><a href="<?php echo SITE_URL; ?>index.php#promociones"><i
                                class="fas fa-tags me-2"></i>Promociones</a></li>
                    <li><a href="<?php echo SITE_URL; ?>index.php#locales"><i class="fas fa-store me-2"></i>Locales</a>
                    </li>
                    <li><a href="<?php echo SITE_URL; ?>contacto.php"><i class="fas fa-envelope me-2"></i>Contacto</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5>Mi Cuenta</h5>
                <ul class="footer-links">
                    <?php if ($isLoggedIn): ?>
                        <li><a href="<?php echo SITE_URL . $userType . '/panel.php'; ?>"><i
                                    class="fas fa-tachometer-alt me-2"></i>Panel de Control</a></li>
                        <li><a href="<?php echo SITE_URL; ?>perfil.php"><i class="fas fa-user me-2"></i>Mi Perfil</a></li>
                        <li><a href="<?php echo SITE_URL; ?>logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar
                                Sesión</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo SITE_URL; ?>login.php"><i class="fas fa-sign-in-alt me-2"></i>Iniciar
                                Sesión</a></li>
                        <li><a href="<?php echo SITE_URL; ?>registro.php"><i
                                    class="fas fa-user-plus me-2"></i>Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5>Contacto</h5>
                <ul class="footer-links">
                    <li><i class="fas fa-map-marker-alt me-2"></i> Shopping del Sol, Rosario</li>
                    <li><i class="fas fa-phone me-2"></i> (0341) 123-4567</li>
                    <li><i class="fas fa-envelope me-2"></i> info@promoshopping.com</li>
                    <li><i class="fas fa-clock me-2"></i> Lun-Dom: 10:00 - 22:00</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2025 PromoShopping. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-white me-3">Términos y Condiciones</a>
                    <a href="#" class="text-white">Política de Privacidad</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script>
    // Funcionalidades adicionales
    document.addEventListener('DOMContentLoaded', function () {
        // Tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Popovers
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });

        // Smooth scrolling para anchors
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
</script>
</body>

</html>