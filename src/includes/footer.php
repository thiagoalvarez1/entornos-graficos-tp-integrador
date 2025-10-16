<?php if ($currentPage != 'login.php' && $currentPage != 'registro.php'): ?>
    <?php if (strpos($_SERVER['REQUEST_URI'], '/panel.php') === false): ?>
        </div>
    <?php endif; ?>
    </main>

    <footer class="footer-modern">
        <div class="footer-content">
            <div class="container">
                <div class="row g-4">
                    <!-- Sección About -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-section">
                            <h5><i class="fas fa-store me-2"></i>Bandera</h5>
                            <p class="mb-4">El mejor lugar para encontrar promociones exclusivas en tu shopping favorito.
                                Descubre ofertas increíbles y ahorra en tus compras con más de 500 promociones activas.</p>
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
                                <?php
                                $socials = [
                                    ['icon' => 'facebook-f', 'title' => 'Facebook'],
                                    ['icon' => 'instagram', 'title' => 'Instagram'],
                                    ['icon' => 'twitter', 'title' => 'Twitter'],
                                    ['icon' => 'whatsapp', 'title' => 'WhatsApp'],
                                    ['icon' => 'linkedin-in', 'title' => 'LinkedIn']
                                ];
                                foreach ($socials as $social):
                                    ?>
                                    <a href="#" class="social-link" title="<?= $social['title'] ?>" rel="noopener noreferrer">
                                        <i class="fab fa-<?= $social['icon'] ?>"></i>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Navegación -->
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-section">
                            <h5>Navegación</h5>
                            <ul class="footer-links-modern">
                                <?php
                                $navLinks = [
                                    ['url' => 'index.php', 'icon' => 'home', 'text' => 'Inicio'],
                                    ['url' => 'index.php#promociones', 'icon' => 'tags', 'text' => 'Promociones'],
                                    ['url' => 'index.php#locales', 'icon' => 'store', 'text' => 'Locales'],
                                    ['url' => 'contacto.php', 'icon' => 'envelope', 'text' => 'Contacto'],
                                    ['url' => '#', 'icon' => 'question-circle', 'text' => 'FAQ']
                                ];
                                foreach ($navLinks as $link):
                                    ?>
                                    <li>
                                        <a href="<?= SITE_URL . $link['url'] ?>" class="footer-link">
                                            <i class="fas fa-<?= $link['icon'] ?>"></i><?= $link['text'] ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Mi Cuenta -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-section">
                            <h5>Mi Cuenta</h5>
                            <ul class="footer-links-modern">
                                <?php
                                $accountLinks = $isLoggedIn ? [
                                    ['url' => $userType . '/panel.php', 'icon' => 'tachometer-alt', 'text' => 'Panel de Control'],
                                    ['url' => 'perfil.php', 'icon' => 'user', 'text' => 'Mi Perfil'],
                                    ['url' => '#', 'icon' => 'heart', 'text' => 'Favoritos'],
                                    ['url' => 'logout.php', 'icon' => 'sign-out-alt', 'text' => 'Cerrar Sesión']
                                ] : [
                                    ['url' => 'login.php', 'icon' => 'sign-in-alt', 'text' => 'Iniciar Sesión'],
                                    ['url' => 'registro.php', 'icon' => 'user-plus', 'text' => 'Registrarse'],
                                    ['url' => '#', 'icon' => 'gift', 'text' => 'Beneficios'],
                                    ['url' => '#', 'icon' => 'mobile-alt', 'text' => 'Descargar App']
                                ];

                                foreach ($accountLinks as $link):
                                    ?>
                                    <li>
                                        <a href="<?= SITE_URL . $link['url'] ?>" class="footer-link">
                                            <i class="fas fa-<?= $link['icon'] ?>"></i><?= $link['text'] ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Contacto -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-section">
                            <h5>Información de Contacto</h5>
                            <ul class="footer-links-modern">
                                <?php
                                $contactInfo = [
                                    ['url' => '#', 'icon' => 'map-marker-alt', 'text' => 'Shopping del Sol, Rosario'],
                                    ['url' => 'tel:+5493414123456', 'icon' => 'phone', 'text' => '(0341) 123-4567'],
                                    ['url' => 'mailto:info@promoshopping.com', 'icon' => 'envelope', 'text' => 'info@promoshopping.com'],
                                    ['url' => '#', 'icon' => 'clock', 'text' => 'Lun-Dom: 10:00 - 22:00']
                                ];
                                foreach ($contactInfo as $info):
                                    ?>
                                    <li>
                                        <a href="<?= $info['url'] ?>" class="footer-link">
                                            <i class="fas fa-<?= $info['icon'] ?>"></i><?= $info['text'] ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; <?= date('Y') ?> <strong>Bandera Shopping</strong>. Todos los derechos
                            reservados.</p>
                    </div>
                    <div class="col-md-6">
                        <div class="footer-bottom-links">
                            <?php
                            $legalLinks = [
                                ['url' => '#', 'text' => 'Términos y Condiciones'],
                                ['url' => '#', 'text' => 'Política de Privacidad'],
                                ['url' => '#', 'text' => 'Cookies']
                            ];
                            foreach ($legalLinks as $legal):
                                ?>
                                <a href="<?= $legal['url'] ?>" class="footer-bottom-link"><?= $legal['text'] ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
<?php endif; ?>

</body>

</html>