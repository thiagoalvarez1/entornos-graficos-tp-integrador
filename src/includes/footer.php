<!-- Footer incluido en el flujo normal del documento -->
<?php if ($currentPage != 'login.php' && $currentPage != 'registro.php'): ?>
    <!-- Solo cerrar container y main si no es login/registro y no es panel -->
    <?php if (strpos($_SERVER['REQUEST_URI'], '/panel.php') === false): ?>
        </div><!-- Cierre del container del main content -->
    <?php endif; ?>
    </main>

    <footer class="footer-modern">
        <div class="footer-content">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-section">
                            <h5>
                                <i class="fas fa-store me-2"></i>PromoShopping
                            </h5>
                            <p class="mb-4">
                                El mejor lugar para encontrar promociones exclusivas en tu shopping favorito.
                                Descubre ofertas increíbles y ahorra en tus compras con más de 500 promociones activas.
                            </p>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="fas fa-award text-white"></i>
                                </div>
                                <div>
                                    <div class="fw-600 text-white">+50K usuarios</div>
                                    <small class="text-muted">confían en nosotros</small>
                                </div>
                            </div>
                            <div class="social-links-modern">
                                <a href="#" class="social-link" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="social-link" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="social-link" title="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="social-link" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="#" class="social-link" title="LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <div class="footer-section">
                            <h5>Navegación</h5>
                            <ul class="footer-links-modern">
                                <li>
                                    <a href="<?php echo SITE_URL; ?>index.php" class="footer-link">
                                        <i class="fas fa-home"></i>Inicio
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo SITE_URL; ?>index.php#promociones" class="footer-link">
                                        <i class="fas fa-tags"></i>Promociones
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo SITE_URL; ?>index.php#locales" class="footer-link">
                                        <i class="fas fa-store"></i>Locales
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo SITE_URL; ?>contacto.php" class="footer-link">
                                        <i class="fas fa-envelope"></i>Contacto
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="footer-link">
                                        <i class="fas fa-question-circle"></i>FAQ
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="footer-section">
                            <h5>Mi Cuenta</h5>
                            <ul class="footer-links-modern">
                                <?php if ($isLoggedIn): ?>
                                    <li>
                                        <a href="<?php echo SITE_URL . $userType . '/panel.php'; ?>" class="footer-link">
                                            <i class="fas fa-tachometer-alt"></i>Panel de Control
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo SITE_URL; ?>perfil.php" class="footer-link">
                                            <i class="fas fa-user"></i>Mi Perfil
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="footer-link">
                                            <i class="fas fa-heart"></i>Favoritos
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo SITE_URL; ?>logout.php" class="footer-link">
                                            <i class="fas fa-sign-out-alt"></i>Cerrar Sesión
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li>
                                        <a href="<?php echo SITE_URL; ?>login.php" class="footer-link">
                                            <i class="fas fa-sign-in-alt"></i>Iniciar Sesión
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo SITE_URL; ?>registro.php" class="footer-link">
                                            <i class="fas fa-user-plus"></i>Registrarse
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="footer-link">
                                            <i class="fas fa-gift"></i>Beneficios
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="footer-link">
                                            <i class="fas fa-mobile-alt"></i>Descargar App
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="footer-section">
                            <h5>Información de Contacto</h5>
                            <ul class="footer-links-modern">
                                <li>
                                    <a href="#" class="footer-link">
                                        <i class="fas fa-map-marker-alt"></i>Shopping del Sol, Rosario
                                    </a>
                                </li>
                                <li>
                                    <a href="tel:+5493414123456" class="footer-link">
                                        <i class="fas fa-phone"></i>(0341) 123-4567
                                    </a>
                                </li>
                                <li>
                                    <a href="mailto:info@promoshopping.com" class="footer-link">
                                        <i class="fas fa-envelope"></i>info@promoshopping.com
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="footer-link">
                                        <i class="fas fa-clock"></i>Lun-Dom: 10:00 - 22:00
                                    </a>
                                </li>
                            </ul>

                            <!-- Newsletter Signup -->
                            <div class="mt-4">
                                <h6 class="text-white mb-3">Newsletter</h6>
                                <div class="d-flex">
                                    <input type="email" class="form-control me-2" placeholder="tu@email.com"
                                        style="border-radius: var(--border-radius); border: 1px solid var(--gray-600); background: var(--gray-800); color: var(--gray-300);">
                                    <button class="btn btn-primary-modern">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">
                            &copy; 2025 <strong>PromoShopping</strong>. Todos los derechos reservados.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="footer-bottom-links">
                            <a href="#" class="footer-bottom-link">Términos y Condiciones</a>
                            <a href="#" class="footer-bottom-link">Política de Privacidad</a>
                            <a href="#" class="footer-bottom-link">Cookies</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
<?php endif; ?>

</body>

</html>