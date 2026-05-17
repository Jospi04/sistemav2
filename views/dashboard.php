<?php
// Capturar variables del controlador
$kpiTotalDinero = $kpis['total_dinero'] ?? 0;
$kpiTotalLitros = $kpis['total_litros'] ?? 0;
$kpiTransacciones = $kpis['transacciones'] ?? 0;
$tankStocks = $tanks ?? [];
$recentSalesList = $recentSales ?? [];
?>
<div class="dashboard-grid">

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
        
        <!-- COLUMNA 1: MONITOREO FÍSICO DE TANQUES (INVENTARIO) -->
        <section class="tanks-section">
            <div class="section-title-bar">
                <h2>Monitoreo de Tanques de Almacenamiento</h2>
                <span class="badge-online">Lectura Activa</span>
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
                        <!-- Regla ESTRICTA CSS: Se usa variable CSS inline (--progress), interpretada en assets/dashboard.css -->
                        <div class="tank-progress-track">
                            <div class="tank-progress-fill <?php echo $statusClass; ?>" style="--progress: <?php echo $percent; ?>%;"></div>
                        </div>

                        <div class="tank-footer">
                            <span>Stock: <strong><?php echo number_format($actual, 2); ?> Gal</strong></span>
                            <span class="muted">/ <?php echo number_format($max, 0); ?> Gal</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

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
