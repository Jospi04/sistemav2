<?php
// Capturar variables del controlador
$kpiTotalDinero = $kpis['total_dinero'] ?? 0;
$kpiTotalLitros = $kpis['total_litros'] ?? 0;
$kpiTransacciones = $kpis['transacciones'] ?? 0;
$tankStocks = $tanks ?? [];
$recentSalesList = $recentSales ?? [];
$refillsHistoryList = $refillsList ?? [];
?>
<div class="dashboard-grid">

    <!-- ALERTAS DE TRANSACCIÓN (SUCCESS & ERROR FLASHES) -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert-success-dispatch">
            <i class='bx bx-check-circle'></i>
            <span><?php echo $_SESSION['success']; ?></span>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-error-dispatch">
            <i class='bx bx-error-circle'></i>
            <span><?php echo $_SESSION['error']; ?></span>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- FILA DE ESTADÍSTICAS RÁPIDAS (KPI CARDS) -->
    <section class="kpi-row">
        <!-- KPI 1: Ingresos del Día -->
        <article class="kpi-card">
            <div class="kpi-icon-box blue" style="background-color: rgba(225, 29, 72, 0.1); color: #e11d48;"><i class="bx bx-money"></i></div>
            <div class="kpi-details">
                <span class="kpi-label">Ingresos de Hoy</span>
                <h3 class="kpi-value" style="font-family: 'Outfit', sans-serif;">S/. <?php echo number_format($kpiTotalDinero, 2); ?></h3>
                <p class="kpi-subtext">Caja registrada hoy</p>
            </div>
        </article>

        <!-- KPI 2: Pedidos Servidos -->
        <article class="kpi-card">
            <div class="kpi-icon-box green" style="background-color: rgba(217, 119, 6, 0.1); color: #d97706;"><i class="bx bx-dish"></i></div>
            <div class="kpi-details">
                <span class="kpi-label">Porciones Vendidas</span>
                <h3 class="kpi-value" style="font-family: 'Outfit', sans-serif;"><?php echo number_format($kpiTotalLitros, 0); ?> Und</h3>
                <p class="kpi-subtext">Platos servidos hoy</p>
            </div>
        </article>

        <!-- KPI 3: Cantidad de Despachos -->
        <article class="kpi-card">
            <div class="kpi-icon-box teal" style="background-color: rgba(24, 24, 27, 0.05); color: #18181b;"><i class="bx bx-receipt"></i></div>
            <div class="kpi-details">
                <span class="kpi-label">Pedidos Totales</span>
                <h3 class="kpi-value" style="font-family: 'Outfit', sans-serif;"><?php echo number_format($kpiTransacciones); ?></h3>
                <p class="kpi-subtext">Comandas procesadas hoy</p>
            </div>
        </article>
    </section>

    <!-- CUADRICULA CENTRAL: MONITOREO DE ALMACÉN Y TABLA RECIENTES -->
    <div class="dashboard-main-columns">
        
        <!-- COLUMNA 1: MONITOREO FÍSICO DE ALMACÉN Y HISTORIAL DE ABASTECIMIENTOS -->
        <div class="dashboard-left-column">
            
            <section class="tanks-section">
                <div class="section-title-bar">
                    <h2 style="font-family: 'Outfit', sans-serif; font-weight: 700;">Stock de Almacén (Cocina)</h2>
                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                        <button type="button" class="btn-shortcut-new btn-refill-trigger" onclick="openRefillModal()">
                            <i class='bx bx-plus'></i> Abastecer Stock
                        </button>
                    <?php endif; ?>
                </div>

                <div class="tanks-grid">
                    <?php foreach ($tankStocks as $tank): ?>
                        <?php 
                        $max = floatval($tank['capacidad_maxima']);
                        $actual = floatval($tank['stock_actual']);
                        $percent = $max > 0 ? round(($actual / $max) * 100, 1) : 0;
                        
                        // Determinar clase de estado según stock
                        $statusClass = 'optimal';
                        if ($percent <= 15) {
                            $statusClass = 'critical';
                        } elseif ($percent <= 40) {
                            $statusClass = 'warning';
                        }
                        ?>
                        <div class="tank-card">
                            <div class="tank-header">
                                <span class="tank-name" style="font-family: 'Outfit', sans-serif; font-weight: 600;"><?php echo htmlspecialchars($tank['combustible_nombre']); ?></span>
                                <span class="tank-percent <?php echo $statusClass; ?>"><?php echo $percent; ?>%</span>
                            </div>
                            
                            <!-- Barra de nivel física -->
                            <div class="tank-progress-track">
                                <div class="tank-progress-fill <?php echo $statusClass; ?>" style="--progress: <?php echo $percent; ?>%;"></div>
                            </div>

                            <div class="tank-footer">
                                <span>Disponible: <strong><?php echo number_format($actual, 0); ?> Und</strong></span>
                                <span class="muted">/ <?php echo number_format($max, 0); ?> Max</span>
                            </div>

                            <!-- Telemetría en vivo simulada de Cámara de Frío -->
                            <div class="tank-sensor-meta">
                                <span class="tank-sensor-title">
                                    <i class='bx bx-radio-circle-marked tank-sensor-status-dot'></i> Temp. Almacén
                                </span>
                                <span>Temp: <?php echo number_format(3.2 + (($tank['id'] * 2) % 6) / 10, 1); ?> °C</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- SECCIÓN: HISTORIAL DE REABASTECIMIENTO DE INSUMOS -->
            <section class="tanks-section section-history-cargas">
                <div class="section-title-bar">
                    <h2 style="font-family: 'Outfit', sans-serif; font-weight: 700;">Historial de Abastecimiento</h2>
                    <span class="badge-online">
                        <i class='bx bx-check-double'></i> Control Activo
                    </span>
                </div>
                
                <div class="table-wrapper">
                    <table class="recent-sales-table">
                        <thead>
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Insumo / Producto</th>
                                <th>Cantidad Agregada</th>
                                <th>Encargado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($refillsHistoryList)): ?>
                                <tr>
                                    <td colspan="4" class="table-empty">No se han registrado ingresos de stock en este turno.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($refillsHistoryList as $refill): ?>
                                    <tr>
                                        <td><?php echo date('d/m H:i', strtotime($refill['fecha'])); ?></td>
                                        <td class="font-bold"><?php echo htmlspecialchars($refill['combustible_nombre']); ?></td>
                                        <td class="font-bold text-accent tank-refill-badge" style="color: #d97706; background-color: rgba(217,119,6,0.1);"><?php echo number_format($refill['cantidad'], 0); ?> Und</td>
                                        <td><?php echo htmlspecialchars($refill['usuario_nombre']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>

        <!-- COLUMNA 2: REGISTRO DE ÚLTIMAS TRANSACCIONES -->
        <section class="transactions-section">
            <div class="section-title-bar">
                <h2 style="font-family: 'Outfit', sans-serif; font-weight: 700;">Últimos Pedidos Procesados</h2>
                <a href="ventas" class="btn-shortcut-new" style="background-color: #e11d48;">+ Tomar Pedido</a>
            </div>

            <div class="table-wrapper">
                <table class="recent-sales-table">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Combo / Plato</th>
                            <th>Cantidad</th>
                            <th>Importe</th>
                            <th>Mesa / Cliente</th>
                            <th>Cajero</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentSalesList)): ?>
                            <tr>
                                <td colspan="6" class="table-empty">No se han registrado comandas en este turno.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentSalesList as $sale): ?>
                                <tr>
                                    <td><?php echo date('H:i:s', strtotime($sale['fecha'])); ?></td>
                                    <td class="font-bold"><?php echo htmlspecialchars($sale['combustible_nombre']); ?></td>
                                    <td><?php echo number_format($sale['litros'], 0); ?> Und</td>
                                    <td class="font-bold text-accent" style="color: #e11d48;">S/. <?php echo number_format($sale['total'], 2); ?></td>
                                    <td><span class="plate-chip" style="background-color: #f4f4f5; color: #18181b; font-family: 'Outfit', sans-serif; font-weight: 600; padding: 4px 8px; border-radius: 6px; font-size: 11px; text-transform: uppercase;"><?php echo !empty($sale['placa_vehiculo']) ? htmlspecialchars($sale['placa_vehiculo']) : 'Llevar'; ?></span></td>
                                    <td><?php echo htmlspecialchars($sale['usuario_nombre']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </div>

</div>

<!-- MODAL DE REABASTECIMIENTO DE TANQUES -->
<div id="refillModal" class="demo-modal-overlay">
    <div class="demo-modal-card">
        <header class="demo-modal-header">
            <h3><i class='bx bx-plus-circle'></i> Abastecer Stock de Almacén</h3>
            <button type="button" class="btn-close-modal" onclick="closeRefillModal()">&times;</button>
        </header>
        <form action="reabastecer" method="POST">
            <div class="sim-group">
                <label for="refill_inventario_id" class="font-mono">¿QUÉ INSUMO VAS A REABASTECER? 🍗</label>
                <div class="select-wrapper">
                    <select name="inventario_id" id="refill_inventario_id" required>
                        <?php foreach ($tankStocks as $tank): ?>
                            <option value="<?php echo $tank['id']; ?>" data-max="<?php echo $tank['capacidad_maxima']; ?>" data-actual="<?php echo $tank['stock_actual']; ?>">
                                <?php echo htmlspecialchars($tank['combustible_nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="sim-group">
                <label for="refill_cantidad" class="font-mono">¿CUÁNTAS UNIDADES VAS A AGREGAR? 🍟</label>
                <input type="number" step="1" min="1" name="cantidad" id="refill_cantidad" required placeholder="Ingresar cantidad (e.g. 100)">
                <span id="refillCapacityLabel" class="font-mono refill-capacity-label">Espacio libre en almacén: 0 Unidades</span>
            </div>

            <div class="modal-actions-wrapper">
                <button type="button" class="btn-action-new" onclick="closeRefillModal()">Cancelar</button>
                <button type="submit" class="btn-submit-dispatch" style="background-color: #e11d48;">¡Guardar en Almacén! ⚡</button>
            </div>
        </form>
    </div>
</div>
