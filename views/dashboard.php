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
            <div class="kpi-icon-box blue"><i class="bx bx-money"></i></div>
            <div class="kpi-details">
                <span class="kpi-label">Ingresos de Hoy</span>
                <h3 class="kpi-value">S/. <?php echo number_format($kpiTotalDinero, 2); ?></h3>
                <p class="kpi-subtext">Caja registrada hoy</p>
            </div>
        </article>

        <!-- KPI 2: Volumen Despachados -->
        <article class="kpi-card">
            <div class="kpi-icon-box green"><i class="bx bx-gas-pump"></i></div>
            <div class="kpi-details">
                <span class="kpi-label">Volumen Vendido</span>
                <h3 class="kpi-value"><?php echo number_format($kpiTotalLitros, 2); ?> Gal</h3>
                <p class="kpi-subtext">Galones despachados hoy</p>
            </div>
        </article>

        <!-- KPI 3: Cantidad de Despachos -->
        <article class="kpi-card">
            <div class="kpi-icon-box teal"><i class="bx bx-transfer-alt"></i></div>
            <div class="kpi-details">
                <span class="kpi-label">Transacciones</span>
                <h3 class="kpi-value"><?php echo number_format($kpiTransacciones); ?></h3>
                <p class="kpi-subtext">Despachos completados hoy</p>
            </div>
        </article>
    </section>

    <!-- CUADRICULA CENTRAL: MONITOREO DE TANQUES Y TABLA RECIENTES -->
    <div class="dashboard-main-columns">
        
        <!-- COLUMNA 1: MONITOREO FÍSICO DE TANQUES Y HISTORIAL DE CISTERNAS -->
        <div class="dashboard-left-column">
            
            <section class="tanks-section">
                <div class="section-title-bar">
                    <h2>Monitoreo de Tanques</h2>
                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                        <button type="button" class="btn-shortcut-new btn-refill-trigger" onclick="openRefillModal()">
                            <i class='bx bx-truck'></i> Reabastecer
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
                                <span class="tank-name"><?php echo htmlspecialchars($tank['combustible_nombre']); ?></span>
                                <span class="tank-percent <?php echo $statusClass; ?>"><?php echo $percent; ?>%</span>
                            </div>
                            
                            <!-- Barra de nivel física -->
                            <div class="tank-progress-track">
                                <div class="tank-progress-fill <?php echo $statusClass; ?>" style="--progress: <?php echo $percent; ?>%;"></div>
                            </div>

                            <div class="tank-footer">
                                <span>Stock: <strong><?php echo number_format($actual, 2); ?> Gal</strong></span>
                                <span class="muted">/ <?php echo number_format($max, 0); ?> Gal</span>
                            </div>

                            <!-- Telemetría en vivo simulada Veeder-Root TLS-450 -->
                            <div class="tank-sensor-meta">
                                <span class="tank-sensor-title">
                                    <i class='bx bx-radio-circle-marked tank-sensor-status-dot'></i> Sonda ATG
                                </span>
                                <span>Temp: <?php echo number_format(19.2 + (($tank['id'] * 7) % 15) / 10, 1); ?> °C</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- SECCIÓN: HISTORIAL DE INGRESOS DE CISTERNA -->
            <section class="tanks-section section-history-cargas">
                <div class="section-title-bar">
                    <h2>Historial de Cargas (Cisternas)</h2>
                    <span class="badge-online">
                        <i class='bx bx-check-double'></i> Control Activo
                    </span>
                </div>
                
                <div class="table-wrapper">
                    <table class="recent-sales-table">
                        <thead>
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Combustible</th>
                                <th>Cantidad Agregada</th>
                                <th>Encargado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($refillsHistoryList)): ?>
                                <tr>
                                    <td colspan="4" class="table-empty">No se han registrado llegadas de cisternas en este turno.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($refillsHistoryList as $refill): ?>
                                    <tr>
                                        <td><?php echo date('d/m H:i', strtotime($refill['fecha'])); ?></td>
                                        <td class="font-bold"><?php echo htmlspecialchars($refill['combustible_nombre']); ?></td>
                                        <td class="font-bold text-accent tank-refill-badge"><?php echo number_format($refill['cantidad'], 2); ?> Gal</td>
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
                <h2>Auditoría de Ventas Recientes</h2>
                <a href="ventas" class="btn-shortcut-new">+ Despachar</a>
            </div>

            <div class="table-wrapper">
                <table class="recent-sales-table">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Combustible</th>
                            <th>Galones</th>
                            <th>Importe</th>
                            <th>Placa</th>
                            <th>Operador</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentSalesList)): ?>
                            <tr>
                                <td colspan="6" class="table-empty">No se han registrado despachos en este turno.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentSalesList as $sale): ?>
                                <tr>
                                    <td><?php echo date('H:i:s', strtotime($sale['fecha'])); ?></td>
                                    <td class="font-bold"><?php echo htmlspecialchars($sale['combustible_nombre']); ?></td>
                                    <td><?php echo number_format($sale['litros'], 2); ?> Gal</td>
                                    <td class="font-bold text-accent">S/. <?php echo number_format($sale['total'], 2); ?></td>
                                    <td><span class="plate-chip"><?php echo !empty($sale['placa_vehiculo']) ? htmlspecialchars($sale['placa_vehiculo']) : 'S/P'; ?></span></td>
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

<!-- MODAL DE REABASTECIMIENTO DE TANQUES (LIGHTBOX ADALINE) -->
<div id="refillModal" class="demo-modal-overlay">
    <div class="demo-modal-card">
        <header class="demo-modal-header">
            <h3><i class='bx bx-truck'></i> Agregar Stock de Combustible</h3>
            <button type="button" class="btn-close-modal" onclick="closeRefillModal()">&times;</button>
        </header>
        <form action="reabastecer" method="POST">
            <div class="sim-group">
                <label for="refill_inventario_id" class="font-mono">¿QUÉ COMBUSTIBLE LLEGÓ? ⛽</label>
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
                <label for="refill_cantidad" class="font-mono">¿CUÁNTOS GALONES VAS A AGREGAR? 💧</label>
                <input type="number" step="0.01" min="0.10" name="cantidad" id="refill_cantidad" required placeholder="Ingresar cantidad (e.g. 500)">
                <span id="refillCapacityLabel" class="font-mono refill-capacity-label">Espacio libre en tanque: 0.00 Galones</span>
            </div>

            <div class="modal-actions-wrapper">
                <button type="button" class="btn-action-new" onclick="closeRefillModal()">Cancelar</button>
                <button type="submit" class="btn-submit-dispatch">¡Recargar Tanque ahora! ⚡</button>
            </div>
        </form>
    </div>
</div>
