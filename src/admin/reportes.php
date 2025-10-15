<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

$auth = new Auth();
$auth->checkAccess(['administrador']);

$pageTitle = "Reportes y Estadísticas";
require_once '../includes/header-panel.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes y Estadísticas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            color: white;
        }

        .header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-icon {
            background: rgba(255, 255, 255, 0.2);
            padding: 12px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.15);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent-color);
        }

        .stat-card.clients::before {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .stat-card.owners::before {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
        }

        .stat-card.promos::before {
            background: linear-gradient(135deg, #43e97b, #38f9d7);
        }

        .stat-card.uses::before {
            background: linear-gradient(135deg, #fa709a, #fee140);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-icon.clients {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .stat-icon.owners {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
        }

        .stat-icon.promos {
            background: linear-gradient(135deg, #43e97b, #38f9d7);
        }

        .stat-icon.uses {
            background: linear-gradient(135deg, #fa709a, #fee140);
        }

        .stat-content {
            flex: 1;
        }

        .stat-number {
            font-size: 2.8rem;
            font-weight: 700;
            color: #2d3748;
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #718096;
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 24px;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-body {
            padding: 24px;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            margin-top: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: linear-gradient(135deg, #f8f9ff 0%, #f1f3ff 100%);
            padding: 16px 12px;
            text-align: left;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 16px 12px;
            border-bottom: 1px solid #f7fafc;
            vertical-align: middle;
        }

        tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #f1f3ff 100%);
        }

        .promo-text {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-weight: 500;
            color: #2d3748;
        }

        .local-name {
            font-weight: 500;
            color: #4a5568;
        }

        .badge {
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .export-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .export-item {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            background: linear-gradient(135deg, #f8f9ff 0%, #f1f3ff 100%);
            border-radius: 12px;
            text-decoration: none;
            color: #4a5568;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .export-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            color: #2d3748;
            text-decoration: none;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .export-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
            font-size: 16px;
        }

        .export-item:hover .export-icon {
            background: rgba(255, 255, 255, 0.2);
        }

        .no-data {
            text-align: center;
            padding: 40px 20px;
            color: #718096;
            font-style: italic;
        }

        .no-data-icon {
            font-size: 3rem;
            color: #e2e8f0;
            margin-bottom: 16px;
        }

        .chart-placeholder {
            background: linear-gradient(135deg, #f8f9ff 0%, #f1f3ff 100%);
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            color: #718096;
            margin-top: 20px;
        }

        .chart-icon {
            font-size: 4rem;
            color: #e2e8f0;
            margin-bottom: 16px;
        }

        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 1.8rem;
            }

            .stat-number {
                font-size: 2.2rem;
            }

            .table-container {
                font-size: 0.9rem;
            }

            th,
            td {
                padding: 12px 8px;
            }

            .export-item {
                padding: 14px 16px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>
                <div class="header-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                Reportes y Estadísticas
            </h1>
        </div>

        <!-- Estadísticas generales -->
        <div class="stats-grid">
            <div class="stat-card clients">
                <div class="stat-header">
                    <div class="stat-content">
                        <div class="stat-number">127</div>
                        <div class="stat-label">Total Clientes</div>
                    </div>
                    <div class="stat-icon clients">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card owners">
                <div class="stat-header">
                    <div class="stat-content">
                        <div class="stat-number">24</div>
                        <div class="stat-label">Dueños de Locales</div>
                    </div>
                    <div class="stat-icon owners">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card promos">
                <div class="stat-header">
                    <div class="stat-content">
                        <div class="stat-number">18</div>
                        <div class="stat-label">Promociones Activas</div>
                    </div>
                    <div class="stat-icon promos">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card uses">
                <div class="stat-header">
                    <div class="stat-content">
                        <div class="stat-number">34</div>
                        <div class="stat-label">Usos Hoy</div>
                    </div>
                    <div class="stat-icon uses">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="content-grid">
            <!-- Top promociones -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-trophy"></i>
                    Top 5 Promociones Más Usadas
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Promoción</th>
                                    <th>Local</th>
                                    <th>Usos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="promo-text">
                                            20% descuento en toda la colección
                                        </div>
                                    </td>
                                    <td class="local-name">Fashion Store</td>
                                    <td>
                                        <span class="badge">
                                            <i class="fas fa-fire"></i>
                                            45
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="promo-text">
                                            Compra 2 pares y lleva el 3ro gratis
                                        </div>
                                    </td>
                                    <td class="local-name">Shoes & More</td>
                                    <td>
                                        <span class="badge">
                                            <i class="fas fa-fire"></i>
                                            38
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="promo-text">
                                            Descuento especial en productos Apple
                                        </div>
                                    </td>
                                    <td class="local-name">TechWorld</td>
                                    <td>
                                        <span class="badge">
                                            <i class="fas fa-fire"></i>
                                            32
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="promo-text">
                                            Menú completo por $15
                                        </div>
                                    </td>
                                    <td class="local-name">Food Court</td>
                                    <td>
                                        <span class="badge">
                                            <i class="fas fa-fire"></i>
                                            28
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="promo-text">
                                            30% off en accesorios
                                        </div>
                                    </td>
                                    <td class="local-name">Accessory World</td>
                                    <td>
                                        <span class="badge">
                                            <i class="fas fa-fire"></i>
                                            24
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Exportar reportes -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-download"></i>
                    Exportar Reportes
                </div>
                <div class="card-body">
                    <div class="export-list">
                        <a href="exportar_reportes.php?tipo=promociones" class="export-item">
                            <div class="export-icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; margin-bottom: 4px;">Exportar Promociones</div>
                                <div style="font-size: 0.85rem; color: #718096;">Lista completa de promociones del
                                    sistema</div>
                            </div>
                        </a>

                        <a href="exportar_reportes.php?tipo=usuarios" class="export-item">
                            <div class="export-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; margin-bottom: 4px;">Exportar Usuarios</div>
                                <div style="font-size: 0.85rem; color: #718096;">Información de todos los usuarios
                                    registrados</div>
                            </div>
                        </a>

                        <a href="exportar_reportes.php?tipo=locales" class="export-item">
                            <div class="export-icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; margin-bottom: 4px;">Exportar Locales</div>
                                <div style="font-size: 0.85rem; color: #718096;">Datos de locales comerciales
                                    registrados</div>
                            </div>
                        </a>

                        <a href="exportar_reportes.php?tipo=usos" class="export-item">
                            <div class="export-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; margin-bottom: 4px;">Exportar Usos de Promociones</div>
                                <div style="font-size: 0.85rem; color: #718096;">Estadísticas de uso y redención</div>
                            </div>
                        </a>
                    </div>

                    <!-- Placeholder para gráfico -->
                    <div class="chart-placeholder">
                        <div class="chart-icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div style="font-weight: 600; margin-bottom: 8px;">Gráfico de Tendencias</div>
                        <div style="font-size: 0.9rem;">Visualización de datos disponible próximamente</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animación de contadores
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');

            counters.forEach(counter => {
                const target = parseInt(counter.textContent);
                let current = 0;
                const increment = target / 50;

                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        counter.textContent = Math.ceil(current);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target;
                    }
                };

                updateCounter();
            });
        }

        // Efectos de hover para las tarjetas de estadísticas
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Simulación de carga de datos
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(animateCounters, 500);
        });

        // Efecto de clic en enlaces de exportación
        document.querySelectorAll('.export-item').forEach(item => {
            item.addEventListener('click', function (e) {
                e.preventDefault();

                // Efecto visual de descarga
                const originalText = this.innerHTML;
                const icon = this.querySelector('.export-icon i');

                icon.className = 'fas fa-spinner fa-spin';

                setTimeout(() => {
                    icon.className = 'fas fa-check';
                    this.style.background = 'linear-gradient(135deg, #48bb78, #38a169)';
                }, 1000);

                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.style.background = '';
                }, 2500);
            });
        });
    </script>
</body>

</html>