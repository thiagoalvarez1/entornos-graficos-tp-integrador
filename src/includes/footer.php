    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Initialize tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        
        // Initialize popovers
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
        
        // Global functions
        function formatDate(dateString) {
            const options = { 
                day: '2-digit', 
                month: '2-digit', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            return new Date(dateString).toLocaleDateString('es-AR', options);
        }
        
        function showLoading() {
            const loader = document.createElement('div');
            loader.className = 'loading-overlay';
            loader.innerHTML = `
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            `;
            document.body.appendChild(loader);
        }
        
        function hideLoading() {
            const loader = document.querySelector('.loading-overlay');
            if (loader) loader.remove();
        }
        
        // Search and filter functionality
        function initSearchFilter(searchInputId, filterSelectId, gridId) {
            const searchInput = document.getElementById(searchInputId);
            const filterSelect = document.getElementById(filterSelectId);
            const grid = document.getElementById(gridId);
            
            if (!searchInput || !filterSelect || !grid) return;
            
            const items = grid.querySelectorAll('.col-lg-4, .col-md-6, .card');
            
            function filterItems() {
                const searchText = searchInput.value.toLowerCase();
                const filterValue = filterSelect.value;
                
                items.forEach(item => {
                    const itemText = item.textContent.toLowerCase();
                    const itemCategory = item.getAttribute('data-category') || '';
                    const matchesSearch = itemText.includes(searchText);
                    const matchesFilter = filterValue === '' || itemCategory === filterValue;
                    
                    item.style.display = (matchesSearch && matchesFilter) ? 'block' : 'none';
                });
            }
            
            searchInput.addEventListener('input', filterItems);
            filterSelect.addEventListener('change', filterItems);
        }
        
        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize search filters
            initSearchFilter('buscadorPromociones', 'filtroCategoria', 'gridPromociones');
            
            // Add smooth scrolling
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