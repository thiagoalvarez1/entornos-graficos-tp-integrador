<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Promociones</title>
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
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.15);
        }

        .stat-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .stat-icon.pending {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .stat-icon.active {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .stat-icon.rejected {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        }

        .stat-icon.total {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2d3748;
        }

        .stat-label {
            color: #718096;
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: none;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1, #bee5eb);
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            margin-bottom: 30px;
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
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
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

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            margin: 0 4px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-success {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #f56565, #e53e3e);
            color: white;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
        }

        .badge-pending {
            background: linear-gradient(135deg, #ed8936, #dd6b20);
            color: white;
        }

        .promo-text {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .date-range {
            font-size: 0.9rem;
            color: #666;
        }

        .local-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .local-name {
            font-weight: 600;
            color: #2d3748;
        }

        .local-location {
            font-size: 0.85rem;
            color: #718096;
        }

        .owner-info {
            font-size: 0.9rem;
            color: #4a5568;
        }

        .category-tag {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
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

            .table-container {
                font-size: 0.9rem;
            }

            th,
            td {
                padding: 12px 8px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>
                <div class="header-icon">
                    <i class="fas fa-tags"></i>
                </div>
                Gestión de Promociones
            </h1>
        </div>

        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon pending">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="stat-number">5</div>
                        <div class="stat-label">Pendientes</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon active">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-number">18</div>
                        <div class="stat-label">Activas</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon rejected">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <div class="stat-number">3</div>
                        <div class="stat-label">Rechazadas</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon total">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div>
                        <div class="stat-number">26</div>
                        <div class="stat-label">Total</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas de ejemplo -->
        <div class="alert alert-success" style="display: none;">
            Promoción aprobada correctamente
        </div>

        <!-- Promociones Pendientes -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-hourglass-half"></i>
                Promociones Pendientes de Aprobación
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Promoción</th>
                                <th>Local</th>
                                <th>Dueño</th>
                                <th>Vigencia</th>
                                <th>Categoría</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>001</strong></td>
                                <td>
                                    <div class="promo-text">
                                        20% descuento en toda la colección de invierno
                                    </div>
                                </td>
                                <td>
                                    <div class="local-info">
                                        <div class="local-name">Fashion Store</div>
                                        <div class="local-location">Planta Baja, Local 12</div>
                                    </div>
                                </td>
                                <td class="owner-info">maria@fashionstore.com</td>
                                <td class="date-range">
                                    15/08/2025 - 30/08/2025
                                </td>
                                <td>
                                    <div class="category-tag">Premium</div>
                                </td>
                                <td>
                                    <form style="display: inline;">
                                        <input type="hidden" name="promocion_id" value="001">
                                        <input type="hidden" name="accion" value="aprobar">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check"></i> Aprobar
                                        </button>
                                    </form>
                                    <form style="display: inline;">
                                        <input type="hidden" name="promocion_id" value="001">
                                        <input type="hidden" name="accion" value="rechazar">
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('¿Rechazar esta promoción?')">
                                            <i class="fas fa-times"></i> Rechazar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>002</strong></td>
                                <td>
                                    <div class="promo-text">
                                        Compra 2 pares y lleva el tercero gratis
                                    </div>
                                </td>
                                <td>
                                    <div class="local-info">
                                        <div class="local-name">Shoes & More</div>
                                        <div class="local-location">Primer Piso, Local 5</div>
                                    </div>
                                </td>
                                <td class="owner-info">carlos@shoesmore.com</td>
                                <td class="date-range">
                                    20/08/2025 - 10/09/2025
                                </td>
                                <td>
                                    <div class="category-tag">Standard</div>
                                </td>
                                <td>
                                    <form style="display: inline;">
                                        <input type="hidden" name="promocion_id" value="002">
                                        <input type="hidden" name="accion" value="aprobar">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check"></i> Aprobar
                                        </button>
                                    </form>
                                    <form style="display: inline;">
                                        <input type="hidden" name="promocion_id" value="002">
                                        <input type="hidden" name="accion" value="rechazar">
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('¿Rechazar esta promoción?')">
                                            <i class="fas fa-times"></i> Rechazar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>003</strong></td>
                                <td>
                                    <div class="promo-text">
                                        Descuento especial en productos Apple
                                    </div>
                                </td>
                                <td>
                                    <div class="local-info">
                                        <div class="local-name">TechWorld</div>
                                        <div class="local-location">Segundo Piso, Local 8</div>
                                    </div>
                                </td>
                                <td class="owner-info">ana@techworld.com</td>
                                <td class="date-range">
                                    01/09/2025 - 15/09/2025
                                </td>
                                <td>
                                    <div class="category-tag">Premium</div>
                                </td>
                                <td>
                                    <form style="display: inline;">
                                        <input type="hidden" name="promocion_id" value="003">
                                        <input type="hidden" name="accion" value="aprobar">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check"></i> Aprobar
                                        </button>
                                    </form>
                                    <form style="display: inline;">
                                        <input type="hidden" name="promocion_id" value="003">
                                        <input type="hidden" name="accion" value="rechazar">
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('¿Rechazar esta promoción?')">
                                            <i class="fas fa-times"></i> Rechazar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Promociones Activas -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-fire"></i>
                Promociones Activas (Últimas 20)
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Promoción</th>
                                <th>Local</th>
                                <th>Vigencia</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>004</strong></td>
                                <td>
                                    <div class="promo-text">
                                        30% descuento en accesorios de temporada
                                    </div>
                                </td>
                                <td>
                                    <div class="local-info">
                                        <div class="local-name">Fashion Store</div>
                                    </div>
                                </td>
                                <td class="date-range">
                                    Hasta 31/08/2025
                                </td>
                                <td><span class="badge badge-success">Activa</span></td>
                            </tr>
                            <tr>
                                <td><strong>005</strong></td>
                                <td>
                                    <div class="promo-text">
                                        Promoción especial en laptops gaming
                                    </div>
                                </td>
                                <td>
                                    <div class="local-info">
                                        <div class="local-name">TechWorld</div>
                                    </div>
                                </td>
                                <td class="date-range">
                                    Hasta 15/09/2025
                                </td>
                                <td><span class="badge badge-success">Activa</span></td>
                            </tr>
                            <tr>
                                <td><strong>006</strong></td>
                                <td>
                                    <div class="promo-text">
                                        Descuento en calzado deportivo
                                    </div>
                                </td>
                                <td>
                                    <div class="local-info">
                                        <div class="local-name">Shoes & More</div>
                                    </div>
                                </td>
                                <td class="date-range">
                                    Hasta 10/09/2025
                                </td>
                                <td><span class="badge badge-success">Activa</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simulación de funcionalidad de botones
        document.querySelectorAll('button[type="submit"]').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const action = this.querySelector('input[name="accion"]') ?
                    this.parentElement.querySelector('input[name="accion"]').value : 'unknown';

                if (action === 'rechazar') {
                    if (!confirm('¿Rechazar esta promoción?')) {
                        return;
                    }
                }

                // Mostrar alerta de éxito
                const alert = document.querySelector('.alert-success');
                alert.style.display = 'block';
                alert.textContent = action === 'aprobar' ? 'Promoción aprobada correctamente' : 'Promoción rechazada correctamente';

                // Ocultar la alerta después de 3 segundos
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 3000);

                // Remover la fila de la tabla (simulación)
                if (action !== 'unknown') {
                    this.closest('tr').style.opacity = '0.5';
                    setTimeout(() => {
                        this.closest('tr').remove();
                    }, 1000);
                }
            });
        });
    </script>
</body>

</html>