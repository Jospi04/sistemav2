<?php
// Capturar variables del controlador
$kpiTotalDinero = $kpis['total_dinero'] ?? 0;
$kpiTotalLitros = $kpis['total_litros'] ?? 0;
$kpiTransacciones = $kpis['transacciones'] ?? 0;

$desdeInput = $_GET['desde'] ?? date('Y-m-d');
$hastaInput = $_GET['hasta'] ?? date('Y-m-d');
$turnoActivo = $_GET['turno'] ?? 'todos';
$griferoActivo = $_GET['grifero_id'] ?? 'todos';
?>

<!-- DISEÑO ESTÉTICO ADALINE CANVAS ICE -->
<style>
    /* Panel de Filtros Premium */
    .filter-card-premium {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        box-shadow: rgba(32, 59, 20, 0.04) 0px 8px 24px;
        margin-bottom: 24px;
    }
    
    .filter-grid-premium {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)) auto;
        gap: 16px;
        align-items: flex-end;
    }

    .form-group-premium {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-group-premium label {
        font-family: 'Inter', sans-serif;
        font-size: 10px;
        font-weight: 700;
        color: var(--text-muted);
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .input-premium {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background-color: var(--bg-primary);
        color: var(--text-main);
        font-family: inherit;
        font-size: 0.9rem;
        outline: none;
        box-sizing: border-box;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .input-premium:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(32, 59, 20, 0.1);
    }

    /* Botón de acción con estilo */
    .btn-submit-premium {
        background-color: var(--accent-color);
        color: var(--bg-primary);
        border: none;
        border-radius: 10px;
        padding: 11px 22px;
        font-family: 'Inter', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        height: 41px;
        box-sizing: border-box;
        transition: all 0.2s ease;
    }

    .btn-submit-premium:hover {
        opacity: 0.95;
        transform: translateY(-1px);
    }

    /* Tarjetas de participación de combustibles */
    .fuel-breakdown-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        box-shadow: rgba(32, 59, 20, 0.03) 0px 8px 24px;
    }

    .fuel-bar-track {
        height: 8px;
        background-color: var(--bg-primary);
        border-radius: 4px;
        overflow: hidden;
        margin-top: 8px;
        margin-bottom: 16px;
        border: 1px solid rgba(0,0,0,0.02);
    }

    .fuel-bar-fill {
        height: 100%;
        border-radius: 4px;
        width: 0%;
        transition: width 0.6s cubic-bezier(0.1, 0.8, 0.2, 1);
    }

    /* Rejilla de KPIs Rediseñada de Lujo */
    .report-kpi-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }
    .report-kpi-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: rgba(32, 59, 20, 0.03) 0px 8px 24px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .report-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: rgba(32, 59, 20, 0.06) 0px 12px 30px;
    }
    .report-kpi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .report-kpi-title {
        font-family: 'Inter', sans-serif;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .report-kpi-icon {
        font-size: 1.3rem;
        color: var(--accent-color);
        background-color: rgba(32, 59, 20, 0.05);
        padding: 8px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .report-kpi-value {
        font-family: 'JetBrains Mono', monospace;
        font-size: 1.85rem;
        font-weight: 700;
        color: var(--text-main);
        letter-spacing: -0.03em;
    }
    .report-kpi-footer {
        font-size: 0.78rem;
        color: var(--text-muted);
        border-top: 1px dashed var(--border-color);
        padding-top: 8px;
        margin-top: 4px;
    }

    /* Badges de métodos de pago */
    .badge-method {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 6px;
        text-transform: uppercase;
        display: inline-block;
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

    /* Colores personalizados para cada combustible */
    .color-g90 { background-color: #f59e0b; }
    .color-g95 { background-color: #0f766e; }
    .color-g97 { background-color: #7c3aed; }
    .color-db5 { background-color: #475569; }
    .color-glp { background-color: #10b981; }

    /* Estilo de la tabla de auditoría con scrollbar fino */
    .scroll-table-premium::-webkit-scrollbar {
        width: 6px;
    }
    .scroll-table-premium::-webkit-scrollbar-track {
        background: transparent;
    }
    .scroll-table-premium::-webkit-scrollbar-thumb {
        background: var(--border-color);
        border-radius: 3px;
    }

    /* Botones de acción en reportes */
    .btn-edit-action {
        color: var(--accent-color);
        background-color: rgba(85, 108, 77, 0.06);
        border: 1px solid rgba(85, 108, 77, 0.15);
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-edit-action:hover {
        background-color: var(--accent-color);
        color: var(--bg-primary);
        border-color: var(--accent-color);
        transform: scale(1.05);
    }

    .btn-delete-action {
        color: #ef4444;
        background-color: rgba(239, 68, 68, 0.05);
        border: 1px solid rgba(239, 68, 68, 0.15);
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-delete-action:hover {
        background-color: #ef4444;
        color: #ffffff;
        border-color: #ef4444;
        transform: scale(1.05);
    }

    /* Modal de Confirmación y Edición Premium */
    .delete-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        animation: fadeInOverlay 0.25s ease forwards;
        box-sizing: border-box;
    }

    .delete-modal-box {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 32px;
        width: 100%;
        max-width: 400px;
        box-shadow: rgba(0, 0, 0, 0.1) 0px 20px 40px;
        text-align: center;
        box-sizing: border-box;
        transform: translateY(20px);
        animation: slideInBox 0.3s cubic-bezier(0.1, 0.8, 0.2, 1) forwards;
    }

    @keyframes fadeInOverlay {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideInBox {
        to { transform: translateY(0); }
    }

    .delete-modal-icon {
        width: 56px;
        height: 56px;
        background-color: rgba(239, 68, 68, 0.08);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #ef4444;
        font-size: 2.2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px auto;
    }

    .delete-modal-title {
        font-family: 'Inter', sans-serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 10px 0;
    }

    .delete-modal-text {
        font-family: 'Inter', sans-serif;
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin: 0 0 24px 0;
    }

    .delete-modal-actions {
        display: flex;
        gap: 12px;
    }

    .btn-delete-cancel {
        flex: 1;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background-color: var(--bg-primary);
        color: var(--text-main);
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-delete-cancel:hover {
        background-color: rgba(0,0,0,0.02);
    }

    .btn-delete-confirm {
        flex: 1;
        padding: 12px;
        border-radius: 10px;
        border: none;
        background-color: #ef4444;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .btn-delete-confirm:hover {
        background-color: #dc2626;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    }

    .input-field-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 16px;
    }

    .input-field-group label {
        font-family: 'Inter', sans-serif;
        font-size: 10px;
        font-weight: 700;
        color: var(--text-muted);
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .input-style-premium {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background-color: var(--bg-primary);
        color: var(--text-main);
        font-family: inherit;
        font-size: 0.9rem;
        outline: none;
        box-sizing: border-box;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .input-style-premium:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(32, 59, 20, 0.1);
    }

    .btn-create-user {
        background-color: var(--accent-color);
        color: var(--bg-primary);
        border: none;
        border-radius: 10px;
        width: 100%;
        padding: 12px;
        font-family: 'Inter', sans-serif;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
        margin-top: 8px;
    }

    .btn-create-user:hover {
        opacity: 0.95;
        transform: translateY(-1px);
    }
</style>

<div class="dashboard-grid" style="margin-top: 8px;">

    <!-- PANEL DE CONTROL MULTICRITERIO DIRECTO -->
    <div class="filter-card-premium">
        <form action="reportes" method="GET">
            <div class="filter-grid-premium">
                <!-- Fecha Inicio -->
                <div class="form-group-premium">
                    <label for="rep_desde">Fecha Inicio</label>
                    <input type="date" name="desde" id="rep_desde" value="<?php echo htmlspecialchars($desdeInput); ?>" class="input-premium" required>
                </div>

                <!-- Fecha Fin -->
                <div class="form-group-premium">
                    <label for="rep_hasta">Fecha Fin</label>
                    <input type="date" name="hasta" id="rep_hasta" value="<?php echo htmlspecialchars($hastaInput); ?>" class="input-premium" required>
                </div>

                <!-- Turno Operativo -->
                <div class="form-group-premium">
                    <label for="rep_turno">Turno Operativo</label>
                    <select name="turno" id="rep_turno" class="input-premium">
                        <option value="todos" <?php echo $turnoActivo === 'todos' ? 'selected' : ''; ?>>Todos los Turnos</option>
                        <option value="manana" <?php echo $turnoActivo === 'manana' ? 'selected' : ''; ?>>Mañana (06 AM - 02 PM)</option>
                        <option value="tarde" <?php echo $turnoActivo === 'tarde' ? 'selected' : ''; ?>>Tarde (02 PM - 10 PM)</option>
                        <option value="noche" <?php echo $turnoActivo === 'noche' ? 'selected' : ''; ?>>Noche (10 PM - 06 AM)</option>
                    </select>
                </div>

                <!-- Grifero / Despachador -->
                <div class="form-group-premium">
                    <label for="rep_grifero">Grifero Despachador</label>
                    <select name="grifero_id" id="rep_grifero" class="input-premium">
                        <option value="todos" <?php echo $griferoActivo === 'todos' ? 'selected' : ''; ?>>Todos los Griferos</option>
                        <?php foreach ($griferosList as $grifero): ?>
                            <option value="<?php echo $grifero['id']; ?>" <?php echo intval($griferoActivo) === intval($grifero['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($grifero['nombre']); ?> (<?php echo $grifero['rol'] === 'admin' ? 'Admin' : 'Grifero'; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Botón Generar -->
                <button type="submit" class="btn-submit-premium">
                    <i class='bx bx-filter-alt'></i> Generar Reporte
                </button>
            </div>
        </form>
    </div>

    <!-- SECCIÓN 1: METRICAS CONSOLIDADAS DEL REPORTE (REDISEÑO DE LUJO BOX CARDS) -->
    <div class="report-kpi-container">
        <!-- KPI 1 -->
        <div class="report-kpi-card">
            <div class="report-kpi-header">
                <span class="report-kpi-title">Recaudación de Turno</span>
                <span class="report-kpi-icon"><i class='bx bx-money-withdraw'></i></span>
            </div>
            <div class="report-kpi-value">S/ <?php echo number_format($kpiTotalDinero, 2); ?></div>
            <div class="report-kpi-footer">Caja total facturada en rango</div>
        </div>

        <!-- KPI 2 -->
        <div class="report-kpi-card">
            <div class="report-kpi-header">
                <span class="report-kpi-title">Volumen Despachado</span>
                <span class="report-kpi-icon"><i class='bx bx-droplet'></i></span>
            </div>
            <div class="report-kpi-value"><?php echo number_format($kpiTotalLitros, 4); ?> <span style="font-size: 1rem; font-weight: 500; color: var(--text-muted);">Gal</span></div>
            <div class="report-kpi-footer">Combustible retirado de surtidores</div>
        </div>

        <!-- KPI 3 -->
        <div class="report-kpi-card">
            <div class="report-kpi-header">
                <span class="report-kpi-title">Despachos Totales</span>
                <span class="report-kpi-icon"><i class='bx bx-receipt'></i></span>
            </div>
            <div class="report-kpi-value"><?php echo number_format($kpiTransacciones); ?> <span style="font-size: 1rem; font-weight: 500; color: var(--text-muted);">Boletas</span></div>
            <div class="report-kpi-footer">Boletas de venta emitidas</div>
        </div>
    </div>

    <!-- SECCIÓN 2: PARTICIPACIÓN DE COMBUSTIBLES Y AUDITORÍA DETALLADA -->
    <div class="dashboard-main-columns" style="align-items: start;">
        
        <!-- COLUMNA 1: GRÁFICO DE APORTACIÓN Y RENDIMIENTO -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div class="fuel-breakdown-card">
                <div class="section-title-bar" style="margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border-color);">
                    <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px;"><i class='bx bx-pie-chart-alt-2' style="color: var(--accent-color);"></i> Aportación por Combustible</h3>
                </div>

                <?php if (empty($combustiblesReport) || $kpiTotalDinero == 0): ?>
                    <div style="text-align: center; padding: 40px 20px; color: var(--text-muted); font-size: 0.9rem;">
                        No se registraron ventas para evaluar la participación en este turno/filtro.
                    </div>
                <?php else: ?>
                    <?php foreach ($combustiblesReport as $fuel): 
                        $totalF = floatval($fuel['total_combustible']);
                        $litrosF = floatval($fuel['litros_combustible']);
                        $percent = $kpiTotalDinero > 0 ? round(($totalF / $kpiTotalDinero) * 100, 1) : 0;
                        
                        // Asignar color según combustible
                        $colorClass = 'color-g90';
                        $nombreComb = strtolower($fuel['combustible_nombre']);
                        if (strpos($nombreComb, '95') !== false) {
                            $colorClass = 'color-g95';
                        } elseif (strpos($nombreComb, '97') !== false || strpos($nombreComb, 'premium') !== false) {
                            $colorClass = 'color-g97';
                        } elseif (strpos($nombreComb, 'diesel') !== false || strpos($nombreComb, 'petroleo') !== false) {
                            $colorClass = 'color-db5';
                        } elseif (strpos($nombreComb, 'glp') !== false) {
                            $colorClass = 'color-glp';
                        }
                        ?>
                        <div style="margin-bottom: 18px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem;">
                                <span class="font-bold" style="color: var(--text-main);"><?php echo htmlspecialchars($fuel['combustible_nombre']); ?></span>
                                <span class="font-mono font-bold" style="color: var(--text-main);"><?php echo $percent; ?>% <span style="font-weight: 400; color: var(--text-muted); font-size: 0.75rem;">(S/ <?php echo number_format($totalF, 2); ?>)</span></span>
                            </div>
                            
                            <!-- Barra de progreso con color del combustible -->
                            <div class="fuel-bar-track">
                                <div class="fuel-bar-fill <?php echo $colorClass; ?>" style="width: <?php echo $percent; ?>%;"></div>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--text-muted); margin-top: -12px; margin-bottom: 12px;">
                                <span>Volumen: <?php echo number_format($litrosF, 4); ?> Gal</span>
                                <span><?php echo $fuel['transacciones_combustible']; ?> ventas</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- COLUMNA 2: REGISTRO DE BOLETAS EMITIDAS EN EL TURNO -->
        <section class="transactions-section" style="margin-top: 0;">
            <div class="section-title-bar">
                <h2>Ventas Auditadas en el Turno</h2>
                <span class="badge-online" style="background-color: rgba(32, 59, 20, 0.06); color: var(--success-color); border: 1px solid rgba(32, 59, 20, 0.15); font-weight: 700; font-size: 0.72rem; padding: 4px 8px; border-radius: 4px;">Control de Surtidor</span>
            </div>

            <div class="table-wrapper scroll-table-premium" style="max-height: 480px; overflow-y: auto;">
                <table class="recent-sales-table">
                    <thead>
                        <tr>
                            <th>Hora / Fecha</th>
                            <th>Combustible</th>
                            <th>Despachado</th>
                            <th>Importe</th>
                            <th>Pago</th>
                            <th>Grifero</th>
                            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                                <th style="width: 80px; text-align: center;">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ventasReport)): ?>
                            <tr>
                                <td colspan="<?php echo (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') ? 7 : 6; ?>" class="table-empty">No se encontraron boletas con los filtros especificados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ventasReport as $venta): ?>
                                <tr>
                                    <td>
                                        <div style="font-size: 0.82rem; font-weight: 600; color: var(--text-main);"><?php echo date('H:i:s', strtotime($venta['fecha'])); ?></div>
                                        <div style="font-size: 0.7rem; color: var(--text-muted);"><?php echo date('d/m/Y', strtotime($venta['fecha'])); ?></div>
                                    </td>
                                    <td class="font-bold"><?php echo htmlspecialchars($venta['combustible_nombre']); ?></td>
                                    <td class="font-mono text-accent" style="font-size: 0.8rem; font-weight: 600;"><?php echo number_format(floatval($venta['litros']), 4); ?> Gal</td>
                                    <td class="font-mono font-bold">S/ <?php echo number_format($venta['total'], 2); ?></td>
                                    <td>
                                        <span class="badge-method badge-<?php echo strtolower($venta['metodo_pago']); ?>">
                                            <?php echo htmlspecialchars($venta['metodo_pago']); ?>
                                        </span>
                                    </td>
                                    <td style="font-size: 0.82rem; font-weight: 500;"><?php echo htmlspecialchars($venta['usuario_nombre']); ?></td>
                                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                                        <td>
                                            <div style="display: flex; gap: 8px; justify-content: center;">
                                                <a href="#" class="btn-edit-action" title="Editar boleta" 
                                                   onclick="openEditVentaModal(event, <?php echo $venta['id']; ?>)">
                                                    <i class='bx bx-edit-alt'></i>
                                                </a>
                                                <a href="#" class="btn-delete-action" title="Anular boleta" 
                                                   onclick="openDeleteVentaModal(event, <?php echo $venta['id']; ?>)">
                                                    <i class='bx bx-trash'></i>
                                                </a>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </div>

</div>

<!-- MODAL DE EDICIÓN DE VENTA PREMIUM (GLASSMORPHIC) -->
<div id="editVentaModal" class="delete-modal-overlay" style="display: none;">
    <div class="delete-modal-box" style="max-width: 450px; text-align: left;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
            <h4 class="delete-modal-title" style="margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class='bx bx-edit-alt' style="color: var(--accent-color); font-size: 1.3rem;"></i>
                Editar Boleta #<span id="editVentaIdLabel">0</span>
            </h4>
            <span onclick="closeEditVentaModal()" style="cursor: pointer; font-size: 1.4rem; color: var(--text-muted);"><i class='bx bx-x'></i></span>
        </div>
        
        <form action="/ventas/editar" method="POST" id="editVentaForm">
            <input type="hidden" name="id" id="editVentaIdInput">
            <input type="hidden" id="editPrecioLitroInput">

            <!-- Combustible (Lectura) -->
            <div class="input-field-group" style="margin-bottom: 14px;">
                <label>Combustible Despachado</label>
                <input type="text" id="editCombustibleName" class="input-style-premium" style="background-color: var(--bg-primary); opacity: 0.8;" readonly>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                <!-- Volumen en Galones -->
                <div class="input-field-group">
                    <label for="editLitrosInput">Cantidad (Galones)</label>
                    <input type="number" step="0.0001" name="litros" id="editLitrosInput" class="input-style-premium" oninput="recalculateEditTotal()" required>
                </div>

                <!-- Importe en Soles -->
                <div class="input-field-group">
                    <label for="editTotalInput">Total (S/.)</label>
                    <input type="number" step="0.01" name="total" id="editTotalInput" class="input-style-premium" oninput="recalculateEditLitros()" required>
                </div>
            </div>

            <!-- Placa de Vehículo -->
            <div class="input-field-group" style="margin-bottom: 14px;">
                <label for="editPlacaInput">Placa de Vehículo</label>
                <input type="text" name="placa_vehiculo" id="editPlacaInput" class="input-style-premium" placeholder="Ej: ABC-123">
            </div>

            <!-- Método de Pago -->
            <div class="input-field-group" style="margin-bottom: 24px;">
                <label for="editMetodoInput">Método de Pago</label>
                <select name="metodo_pago" id="editMetodoInput" class="input-style-premium">
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta">Tarjeta</option>
                </select>
            </div>

            <div class="delete-modal-actions">
                <button type="button" class="btn-delete-cancel" onclick="closeEditVentaModal()">Cancelar</button>
                <button type="submit" class="btn-create-user" style="margin-top: 0; flex: 1;">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DE ANULACIÓN DE VENTA PREMIUM -->
<div id="deleteVentaConfirmModal" class="delete-modal-overlay" style="display: none;">
    <div class="delete-modal-box">
        <div class="delete-modal-icon">
            <i class='bx bx-error-circle'></i>
        </div>
        <h4 class="delete-modal-title" id="deleteVentaModalTitle">¿Anular Boleta?</h4>
        <p class="delete-modal-text">Esta acción es permanente. El importe será borrado de caja y los galones de combustible se **devolverán de forma automática al stock** del tanque subterráneo.</p>
        <div class="delete-modal-actions">
            <button type="button" class="btn-delete-cancel" onclick="closeDeleteVentaModal()">Cancelar</button>
            <a href="#" id="confirmDeleteVentaBtn" class="btn-delete-confirm">Sí, Anular</a>
        </div>
    </div>
</div>

<script>
function openDeleteVentaModal(event, id) {
    event.preventDefault();
    const modal = document.getElementById('deleteVentaConfirmModal');
    const title = document.getElementById('deleteVentaModalTitle');
    const confirmBtn = document.getElementById('confirmDeleteVentaBtn');
    
    title.textContent = `¿Anular Boleta #${id}?`;
    confirmBtn.href = `/ventas/eliminar?id=${id}`;
    modal.style.display = 'flex';
}

function closeDeleteVentaModal() {
    const modal = document.getElementById('deleteVentaConfirmModal');
    modal.style.display = 'none';
}

function openEditVentaModal(event, id) {
    event.preventDefault();
    
    // Traer datos de la venta vía fetch JSON
    fetch(`/ventas/editar?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                document.getElementById('editVentaIdLabel').textContent = data.id;
                document.getElementById('editVentaIdInput').value = data.id;
                document.getElementById('editPrecioLitroInput').value = data.precio_litro;
                document.getElementById('editCombustibleName').value = data.combustible_nombre;
                document.getElementById('editLitrosInput').value = parseFloat(data.litros).toFixed(4);
                document.getElementById('editTotalInput').value = parseFloat(data.total).toFixed(2);
                document.getElementById('editPlacaInput').value = data.placa_vehiculo || '';
                document.getElementById('editMetodoInput').value = data.metodo_pago;
                
                document.getElementById('editVentaModal').style.display = 'flex';
            }
        })
        .catch(err => console.error("Error al cargar datos de venta: ", err));
}

function closeEditVentaModal() {
    document.getElementById('editVentaModal').style.display = 'none';
}

function recalculateEditTotal() {
    const litros = parseFloat(document.getElementById('editLitrosInput').value) || 0;
    const precio = parseFloat(document.getElementById('editPrecioLitroInput').value) || 0;
    const total = litros * precio;
    document.getElementById('editTotalInput').value = total.toFixed(2);
}

function recalculateEditLitros() {
    const total = parseFloat(document.getElementById('editTotalInput').value) || 0;
    const precio = parseFloat(document.getElementById('editPrecioLitroInput').value) || 0;
    if (precio > 0) {
        const litros = total / precio;
        document.getElementById('editLitrosInput').value = litros.toFixed(4);
    }
}
</script>
