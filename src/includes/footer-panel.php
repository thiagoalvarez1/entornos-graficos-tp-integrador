</div><!-- Cierre del main-content -->
</div><!-- Cierre del container principal -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Toggle sidebar
    document.getElementById('sidebarToggle').addEventListener('click', function () {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');

        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('collapsed');

        if (sidebar.classList.contains('collapsed')) {
            sidebar.style.width = '60px';
            mainContent.style.marginLeft = '60px';
        } else {
            sidebar.style.width = '250px';
            mainContent.style.marginLeft = '250px';
        }
    });

    // Gráficos y otras funcionalidades específicas del panel
    // ... tu código de gráficos aquí ...
</script>

</body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');

                // Para móviles
                if (window.innerWidth <= 768) {
                    sidebar.classList.toggle('show');
                }
            });
        }

        // Cerrar sidebar en móvil al hacer click fuera
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 768 &&
                !sidebar.contains(e.target) &&
                !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });
    });
</script>