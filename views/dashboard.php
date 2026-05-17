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
        <div class="alert-success-dispatch" style="grid-column: 1 / -1; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; background-color: rgba(32, 59, 20, 0.06); border: 1px solid rgba(32, 59, 20, 0.12); padding: 14px 20px; border-radius: 8px; color: var(--success-color); font-weight: 600; font-size: 0.95rem;">
            <i class='bx bx-check-circle' style='font-size: 1.25rem;'></i>
            <span><?php echo $_SESSION['success']; ?></span>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-error-dispatch" style="grid-column: 1 / -1; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; background-color: rgba(184, 45, 29, 0.06); border: 1px solid rgba(184, 45, 29, 0.12); padding: 14px 20px; border-radius: 8px; color: #b82d1d; font-weight: 600; font-size: 0.95rem;">
            <i class='bx bx-error-circle' style='font-size: 1.25rem;'></i>
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
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            <section class="tanks-section">
                <div class="section-title-bar" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2>Monitoreo de Tanques</h2>
                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                        <button type="button" class="btn-shortcut-new" onclick="openRefillModal()" style="background-color: var(--accent-color); color: var(--bg-primary); border: none; font-family: inherit; cursor: pointer; display: flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: var(--radius-buttons); font-size: 0.82rem; font-weight: 700;">
                            <i class='bx bx-truck' style="font-size: 1rem;"></i> Reabastecer
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
                            <div class="tank-sensor-meta" style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--text-muted); margin-top: 8px; border-top: 1px dashed var(--border-color); padding-top: 8px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="display: flex; align-items: center; gap: 4px;"><i class='bx bx-radio-circle-marked' style='color: var(--success-color); font-size: 12px;'></i> Sonda ATG</span>
                                <span>Temp: <?php echo number_format(19.2 + (($tank['id'] * 7) % 15) / 10, 1); ?> °C</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- SECCIÓN: HISTORIAL DE INGRESOS DE CISTERNA -->
            <section class="tanks-section" style="margin-top: 0;">
                <div class="section-title-bar">
                    <h2>Historial de Cargas (Cisternas)</h2>
                    <span class="badge-online" style="background-color: rgba(32, 59, 20, 0.06); color: var(--success-color); border: 1px solid rgba(32, 59, 20, 0.15); display: flex; align-items: center; gap: 4px; padding: 4px 8px; border-radius: 4px; font-size: 0.72rem; font-weight: 700;"><i class='bx bx-check-double'></i> Control Activo</span>
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
                                        <td class="font-bold text-accent" style="color: var(--success-color);"><?php echo number_format($refill['cantidad'], 2); ?> Gal</td>
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
<div id="refillModal" class="demo-modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(10, 29, 8, 0.4); z-index: 1000; justify-content: center; align-items: center; backdrop-filter: blur(4px);">
    <div class="demo-modal-card" style="background: var(--bg-secondary); border-radius: var(--radius-cards); width: 100%; max-width: 450px; padding: 24px; box-shadow: rgba(32, 59, 20, 0.1) 0px 12px 36px; border: 1px solid var(--border-color);">
        <header class="demo-modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px; letter-spacing: -0.03em;"><i class='bx bx-truck' style="font-size: 1.3rem;"></i> Agregar Stock de Combustible</h3>
            <button type="button" class="btn-close-modal" onclick="closeRefillModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </header>
        <form action="reabastecer" method="POST">
            <div class="sim-group" style="margin-bottom: 16px; display: flex; flex-direction: column; gap: 6px;">
                <label for="refill_inventario_id" class="font-mono" style="font-size: 10px; font-weight: 700; color: var(--text-muted); letter-spacing: 0.5px;">¿QUÉ COMBUSTIBLE LLEGÓ? ⛽</label>
                <div class="select-wrapper" style="position: relative;">
                    <select name="inventario_id" id="refill_inventario_id" required style="width: 100%; padding: 10px 12px; border-radius: var(--radius-inputs); border: 1px solid var(--border-color); font-family: inherit; font-size: 0.9rem; background-color: var(--bg-primary); color: var(--text-main); cursor: pointer; outline: none;">
                        <?php foreach ($tankStocks as $tank): ?>
                            <option value="<?php echo $tank['id']; ?>" data-max="<?php echo $tank['capacidad_maxima']; ?>" data-actual="<?php echo $tank['stock_actual']; ?>">
                                <?php echo htmlspecialchars($tank['combustible_nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="sim-group" style="margin-bottom: 20px; display: flex; flex-direction: column; gap: 6px;">
                <label for="refill_cantidad" class="font-mono" style="font-size: 10px; font-weight: 700; color: var(--text-muted); letter-spacing: 0.5px;">¿CUÁNTOS GALONES VAS A AGREGAR? 💧</label>
                <input type="number" step="0.01" min="0.10" name="cantidad" id="refill_cantidad" required placeholder="Ingresar cantidad (e.g. 500)" style="width: 100%; padding: 10px 12px; border-radius: var(--radius-inputs); border: 1px solid var(--border-color); font-family: inherit; font-size: 0.95rem; background-color: var(--bg-primary); color: var(--text-main); outline: none;">
                <span id="refillCapacityLabel" class="font-mono" style="font-size: 11px; color: var(--success-color); margin-top: 4px; display: block; font-weight: 600;">Espacio libre en tanque: 0.00 Galones</span>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end; border-top: 1px solid var(--border-color); padding-top: 16px; margin-top: 20px;">
                <button type="button" class="btn-action-new" onclick="closeRefillModal()" style="border-radius: var(--radius-buttons); padding: 8px 16px; border: 1px solid var(--border-color); background: none; font-family: inherit; font-size: 0.85rem; font-weight: 700; cursor: pointer; color: var(--text-main); transition: all 0.2s;">Cancelar</button>
                <button type="submit" class="btn-submit-dispatch" style="border-radius: var(--radius-buttons); padding: 8px 20px; background-color: var(--accent-color); color: var(--bg-primary); border: none; font-family: inherit; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: all 0.2s;">¡Recargar Tanque ahora! ⚡</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRefillModal() {
    const modal = document.getElementById('refillModal');
    modal.style.display = 'flex';
    updateRefillCapacity();
}

function closeRefillModal() {
    const modal = document.getElementById('refillModal');
    modal.style.display = 'none';
}

function updateRefillCapacity() {
    const select = document.getElementById('refill_inventario_id');
    const option = select.options[select.selectedIndex];
    const max = parseFloat(option.getAttribute('data-max')) || 0;
    const actual = parseFloat(option.getAttribute('data-actual')) || 0;
    const available = max - actual;
    
    const label = document.getElementById('refillCapacityLabel');
    label.textContent = `Espacio libre en tanque: ${available.toFixed(2)} Galones`;
    document.getElementById('refill_cantidad').max = available.toFixed(2);
}

document.getElementById('refill_inventario_id').addEventListener('change', updateRefillCapacity);

// Cerrar modal al hacer clic en el fondo grisáceo
window.addEventListener('click', function(event) {
    const modal = document.getElementById('refillModal');
    if (event.target === modal) {
        closeRefillModal();
    }
});
</script>
