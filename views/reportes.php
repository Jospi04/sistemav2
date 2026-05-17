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

<div class="dashboard-grid reporting-dashboard-grid">

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

    <!-- SECCIÓN 1: METRICAS CONSOLIDADAS DEL REPORTE -->
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
            <div class="report-kpi-value"><?php echo number_format($kpiTotalLitros, 4); ?> <span class="reportes-time-sub">Gal</span></div>
            <div class="report-kpi-footer">Combustible retirado de surtidores</div>
        </div>

        <!-- KPI 3 -->
        <div class="report-kpi-card">
            <div class="report-kpi-header">
                <span class="report-kpi-title">Despachos Totales</span>
                <span class="report-kpi-icon"><i class='bx bx-receipt'></i></span>
            </div>
            <div class="report-kpi-value"><?php echo number_format($kpiTransacciones); ?> <span class="reportes-time-sub">Boletas</span></div>
            <div class="report-kpi-footer">Boletas de venta emitidas</div>
        </div>
    </div>

    <!-- SECCIÓN 2: PARTICIPACIÓN DE COMBUSTIBLES Y AUDITORÍA DETALLADA -->
    <div class="dashboard-main-columns reportes-main-columns">
        
        <!-- COLUMNA 1: GRÁFICO DE APORTACIÓN Y RENDIMIENTO -->
        <div class="fuel-column">
            <div class="fuel-breakdown-card">
                <div class="section-title-bar fuel-title-bar">
                    <h3 class="fuel-title">
                        <i class='bx bx-pie-chart-alt-2' style="color: var(--accent-color);"></i> Aportación por Combustible
                    </h3>
                </div>

                <?php if (empty($combustiblesReport) || $kpiTotalDinero == 0): ?>
                    <div class="empty-report-info">
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
                        <div class="fuel-row-item">
                            <div class="fuel-row-header">
                                <span class="font-bold fuel-name"><?php echo htmlspecialchars($fuel['combustible_nombre']); ?></span>
                                <span class="font-mono font-bold fuel-percentage-text"><?php echo $percent; ?>% <span class="reportes-time-sub">(S/ <?php echo number_format($totalF, 2); ?>)</span></span>
                            </div>
                            
                            <!-- Barra de progreso con color del combustible -->
                            <div class="fuel-bar-track">
                                <div class="fuel-bar-fill <?php echo $colorClass; ?>" style="width: <?php echo $percent; ?>%;"></div>
                            </div>
                            
                            <div class="fuel-row-footer">
                                <span>Volumen: <?php echo number_format($litrosF, 4); ?> Gal</span>
                                <span><?php echo $fuel['transacciones_combustible']; ?> ventas</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- COLUMNA 2: REGISTRO DE BOLETAS EMITIDAS EN EL TURNO -->
        <section class="transactions-section reset-margin-top">
            <div class="section-title-bar">
                <h2>Ventas Auditadas en el Turno</h2>
                <span class="badge-online badge-online-surtidor">Control de Surtidor</span>
            </div>

            <div class="table-wrapper scroll-table-premium reportes-table-scroll">
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
                                        <div class="reportes-time-main"><?php echo date('H:i:s', strtotime($venta['fecha'])); ?></div>
                                        <div class="reportes-time-sub"><?php echo date('d/m/Y', strtotime($venta['fecha'])); ?></div>
                                    </td>
                                    <td class="font-bold"><?php echo htmlspecialchars($venta['combustible_nombre']); ?></td>
                                    <td class="font-mono text-accent reportes-gal-text"><?php echo number_format(floatval($venta['litros']), 4); ?> Gal</td>
                                    <td class="font-mono font-bold">S/ <?php echo number_format($venta['total'], 2); ?></td>
                                    <td>
                                        <span class="badge-method badge-<?php echo strtolower($venta['metodo_pago']); ?>">
                                            <?php echo htmlspecialchars($venta['metodo_pago']); ?>
                                        </span>
                                    </td>
                                    <td class="reportes-time-main"><?php echo htmlspecialchars($venta['usuario_nombre']); ?></td>
                                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                                        <td>
                                            <div class="actions-flex-center">
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
<div id="editVentaModal" class="delete-modal-overlay">
    <div class="delete-modal-box edit-modal-box">
        <div class="edit-modal-header">
            <h4 class="delete-modal-title edit-modal-title">
                <i class='bx bx-edit-alt edit-modal-icon-inline'></i>
                Editar Boleta #<span id="editVentaIdLabel">0</span>
            </h4>
            <span onclick="closeEditVentaModal()" class="btn-close-edit-modal"><i class='bx bx-x'></i></span>
        </div>
        
        <form action="/ventas/editar" method="POST" id="editVentaForm">
            <input type="hidden" name="id" id="editVentaIdInput">
            <input type="hidden" id="editPrecioLitroInput">

            <!-- Combustible (Lectura) -->
            <div class="input-field-group input-field-group-spacing">
                <label>Combustible Despachado</label>
                <input type="text" id="editCombustibleName" class="input-style-premium readonly-input" readonly>
            </div>

            <div class="grid-two-columns">
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
            <div class="input-field-group input-field-group-spacing">
                <label for="editPlacaInput">Placa de Vehículo</label>
                <input type="text" name="placa_vehiculo" id="editPlacaInput" class="input-style-premium" placeholder="Ej: ABC-123">
            </div>

            <!-- Método de Pago -->
            <div class="input-field-group select-field-group-spacing">
                <label for="editMetodoInput">Método de Pago</label>
                <select name="metodo_pago" id="editMetodoInput" class="input-style-premium">
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta">Tarjeta</option>
                </select>
            </div>

            <div class="delete-modal-actions">
                <button type="button" class="btn-delete-cancel" onclick="closeEditVentaModal()">Cancelar</button>
                <button type="submit" class="btn-create-user btn-save-edit-submit">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DE ANULACIÓN DE VENTA PREMIUM -->
<div id="deleteVentaConfirmModal" class="delete-modal-overlay">
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
