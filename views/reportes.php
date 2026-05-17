<?php
// Capturar variables del controlador
$kpiTotalDinero = $kpis['total_dinero'] ?? 0;
$kpiTotalLitros = $kpis['total_litros'] ?? 0;
$kpiTransacciones = $kpis['transacciones'] ?? 0;
$periodoActivo = $_GET['periodo'] ?? 'semana';
$desdeFiltro = $_GET['desde'] ?? '';
$hastaFiltro = $_GET['hasta'] ?? '';
?>

<!-- Estilos Específicos para Reportes -->
<style>
    .btn-filter-quick {
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-muted);
        padding: 6px 14px;
        border-radius: var(--radius-buttons);
        border: 1px solid var(--border-color);
        background-color: var(--bg-primary);
        transition: all 0.2s ease;
        display: inline-block;
    }
    .btn-filter-quick:hover {
        color: var(--text-main);
        border-color: var(--text-muted);
    }
    .btn-filter-quick.active {
        background-color: var(--accent-color);
        color: var(--bg-primary);
        border-color: var(--accent-color);
    }
    .report-date-input {
        padding: 6px 10px;
        border-radius: var(--radius-inputs);
        border: 1px solid var(--border-color);
        background-color: var(--bg-primary);
        color: var(--text-main);
        font-family: inherit;
        font-size: 0.85rem;
        outline: none;
    }
    .btn-submit-filter {
        background-color: var(--accent-color);
        color: var(--bg-primary);
        border: none;
        padding: 7px 12px;
        border-radius: var(--radius-buttons);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .btn-submit-filter:hover {
        opacity: 0.9;
    }
    .fuel-breakdown-card {
        background: var(--bg-secondary);
        border-radius: var(--radius-cards);
        border: 1px solid var(--border-color);
        padding: 24px;
        box-shadow: rgba(0, 0, 0, 0.02) 0px 4px 12px;
    }
    .fuel-bar-track {
        height: 6px;
        background-color: var(--bg-primary);
        border-radius: 3px;
        overflow: hidden;
        margin-top: 8px;
        margin-bottom: 16px;
    }
    .fuel-bar-fill {
        height: 100%;
        background-color: var(--accent-color);
        border-radius: 3px;
        width: 0%;
        transition: width 0.6s ease;
    }
    .badge-method {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
        text-transform: uppercase;
    }
    .badge-efectivo {
        background-color: rgba(32, 59, 20, 0.06);
        color: var(--success-color);
        border: 1px solid rgba(32, 59, 20, 0.15);
    }
    .badge-tarjeta {
        background-color: rgba(14, 165, 233, 0.06);
        color: #0ea5e9;
        border: 1px solid rgba(14, 165, 233, 0.15);
    }
</style>

<div class="dashboard-grid" style="margin-top: 8px;">

    <!-- BARRA DE FILTROS SUPERIOR -->
    <div class="report-filter-bar" style="background: var(--bg-secondary); border-radius: var(--radius-cards); padding: 16px 20px; border: 1px solid var(--border-color); margin-bottom: 24px; display: flex; flex-wrap: wrap; gap: 20px; justify-content: space-between; align-items: center;">
        <!-- Filtros Rápidos -->
        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <span class="font-mono" style="font-size: 11px; font-weight: 700; color: var(--text-muted); letter-spacing: 0.5px; margin-right: 8px;">PERÍODO:</span>
            <a href="reportes?periodo=hoy" class="btn-filter-quick <?php echo $periodoActivo === 'hoy' ? 'active' : ''; ?>">Hoy</a>
            <a href="reportes?periodo=semana" class="btn-filter-quick <?php echo $periodoActivo === 'semana' ? 'active' : ''; ?>">Últimos 7 días</a>
            <a href="reportes?periodo=mes" class="btn-filter-quick <?php echo $periodoActivo === 'mes' ? 'active' : ''; ?>">Este Mes</a>
            <a href="#" onclick="togglePersonalizado(event)" class="btn-filter-quick <?php echo $periodoActivo === 'personalizado' ? 'active' : ''; ?>" id="btnPersonalizado">Rango Elegible</a>
        </div>

        <!-- Formulario Personalizado -->
        <form action="reportes" method="GET" id="formPersonalizado" style="display: <?php echo $periodoActivo === 'personalizado' ? 'flex' : 'none'; ?>; gap: 12px; align-items: center; flex-wrap: wrap;">
            <input type="hidden" name="periodo" value="personalizado">
            <div style="display: flex; align-items: center; gap: 6px;">
                <span class="font-mono" style="font-size: 10px; color: var(--text-muted); font-weight: 600;">DESDE:</span>
                <input type="date" name="desde" value="<?php echo htmlspecialchars($desdeFiltro); ?>" class="report-date-input" required>
            </div>
            <div style="display: flex; align-items: center; gap: 6px;">
                <span class="font-mono" style="font-size: 10px; color: var(--text-muted); font-weight: 600;">HASTA:</span>
                <input type="date" name="hasta" value="<?php echo htmlspecialchars($hastaFiltro); ?>" class="report-date-input" required>
            </div>
            <button type="submit" class="btn-submit-filter" title="Filtrar ventas">
                <i class='bx bx-search-alt' style="font-size: 1.1rem;"></i>
            </button>
        </form>
    </div>

    <!-- SECCIÓN 1: KPIs FINANCIEROS DEL PERÍODO -->
    <div class="kpis-grid" style="margin-bottom: 24px;">
        <!-- KPI 1 -->
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Recaudación de Ventas</span>
                <span class="kpi-icon money"><i class='bx bx-money-withdraw'></i></span>
            </div>
            <div class="kpi-value font-mono">S/ <?php echo number_format($kpiTotalDinero, 2); ?></div>
            <div class="kpi-footer">Ingresos brutos facturados</div>
        </div>

        <!-- KPI 2 -->
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Volumen Despachado</span>
                <span class="kpi-icon volume"><i class='bx bx-droplet'></i></span>
            </div>
            <div class="kpi-value font-mono"><?php echo number_format($kpiTotalLitros, 2); ?> <span style="font-size: 1.1rem; font-weight: 500;">Gal</span></div>
            <div class="kpi-footer">Combustible retirado de tanques</div>
        </div>

        <!-- KPI 3 -->
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Transacciones</span>
                <span class="kpi-icon tx"><i class='bx bx-receipt'></i></span>
            </div>
            <div class="kpi-value font-mono"><?php echo number_format($kpiTransacciones); ?></div>
            <div class="kpi-footer">Boletas de venta emitidas</div>
        </div>
    </div>

    <!-- SECCIÓN 2: DESGLOSE POR COMBUSTIBLE Y LISTADO DETALLADO -->
    <div class="dashboard-main-columns" style="align-items: start;">
        
        <!-- COLUMNA 1: DESGLOSE DE COMBUSTIBLES -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div class="fuel-breakdown-card">
                <div class="section-title-bar" style="margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border-color);">
                    <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: var(--text-main);">Participación por Combustible</h3>
                </div>

                <?php if (empty($combustiblesReport) || $kpiTotalDinero == 0): ?>
                    <div style="text-align: center; padding: 30px; color: var(--text-muted); font-size: 0.9rem;">
                        No hay datos de ventas para graficar participación en este período.
                    </div>
                <?php else: ?>
                    <?php foreach ($combustiblesReport as $fuel): 
                        $totalF = floatval($fuel['total_combustible']);
                        $litrosF = floatval($fuel['litros_combustible']);
                        $percent = $kpiTotalDinero > 0 ? round(($totalF / $kpiTotalDinero) * 100, 1) : 0;
                        ?>
                        <div style="margin-bottom: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem;">
                                <span class="font-bold" style="color: var(--text-main);"><?php echo htmlspecialchars($fuel['combustible_nombre']); ?></span>
                                <span class="font-mono font-bold" style="color: var(--accent-color);"><?php echo $percent; ?>% <span style="font-weight: 400; color: var(--text-muted); font-size: 0.75rem;">(S/ <?php echo number_format($totalF, 2); ?>)</span></span>
                            </div>
                            
                            <!-- Barra de progreso visual -->
                            <div class="fuel-bar-track">
                                <div class="fuel-bar-fill" style="width: <?php echo $percent; ?>%;"></div>
                            </div>
                            
                            <!-- Datos del volumen vendido -->
                            <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--text-muted); margin-top: -12px; margin-bottom: 16px;">
                                <span>Volumen: <?php echo number_format($litrosF, 2); ?> Gal</span>
                                <span><?php echo $fuel['transacciones_combustible']; ?> despachos</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- COLUMNA 2: LISTADO AUDITABLE DE COMPROBANTES -->
        <section class="transactions-section" style="margin-top: 0;">
            <div class="section-title-bar">
                <h2>Registro Detallado del Período</h2>
                <span class="badge-online" style="background-color: rgba(32, 59, 20, 0.06); color: var(--success-color); border: 1px solid rgba(32, 59, 20, 0.15); font-weight: 700; font-size: 0.72rem; padding: 4px 8px; border-radius: 4px;">Auditoría Financiera</span>
            </div>

            <div class="table-wrapper" style="max-height: 480px; overflow-y: auto;">
                <table class="recent-sales-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Combustible</th>
                            <th>Despacho</th>
                            <th>Importe</th>
                            <th>Método</th>
                            <th>Operario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ventasReport)): ?>
                            <tr>
                                <td colspan="6" class="table-empty">No se encontraron boletas en el rango de fechas seleccionado.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ventasReport as $venta): ?>
                                <tr>
                                    <td><?php echo date('d/m H:i', strtotime($venta['fecha'])); ?></td>
                                    <td class="font-bold"><?php echo htmlspecialchars($venta['combustible_nombre']); ?></td>
                                    <td class="font-mono text-accent" style="font-size: 0.8rem;"><?php echo number_format(floatval($venta['litros']), 4); ?> Gal</td>
                                    <td class="font-mono font-bold">S/ <?php echo number_format($venta['total'], 2); ?></td>
                                    <td>
                                        <span class="badge-method badge-<?php echo strtolower($venta['metodo_pago']); ?>">
                                            <?php echo htmlspecialchars($venta['metodo_pago']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($venta['usuario_nombre']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </div>

</div>

<!-- Script interactivo para el calendario personalizado -->
<script>
function togglePersonalizado(e) {
    e.preventDefault();
    const form = document.getElementById('formPersonalizado');
    const btn = document.getElementById('btnPersonalizado');
    
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'flex';
        btn.classList.add('active');
    } else {
        form.style.display = 'none';
        btn.classList.remove('active');
    }
}
</script>
