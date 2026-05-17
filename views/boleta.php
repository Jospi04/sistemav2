<?php
// Capturar los detalles de la venta cargada por el controlador
$v = $ventaDetalle ?? null;
?>

<div class="receipt-container">

    <!-- BUSCADOR DE BOLETAS EN CALIENTE (NO-PRINT) -->
    <div class="search-boleta-container no-print">
        <span class="search-boleta-icon"><i class='bx bx-search'></i></span>
        <input type="number" id="searchBoletaId" placeholder="Buscar Boleta por ID... (Ej: 12)" class="input-style-premium search-boleta-input" onkeypress="checkSearchSubmit(event)">
        <button type="button" class="btn-submit-premium search-boleta-btn" onclick="submitBoletaSearch()">Buscar</button>
    </div>

    <?php if (!$v): ?>
        <!-- Vista alternativa si no hay boletas seleccionadas o no encontradas -->
        <div class="no-receipt-box">
            <div class="box-icon box-icon-centered">
                <i class="bx bx-receipt"></i>
            </div>
            <h2>Comprobantes de Despacho</h2>
            <p>No se ha cargado ninguna boleta en el visor, o la boleta consultada no existe. Realice un despacho o ingrese un ID válido en el buscador superior.</p>
            <div class="box-actions">
                <a href="ventas" class="btn-primary-go">Ir a Despacho</a>
            </div>
        </div>
    <?php else: ?>
        
        <!-- TICKET TÉRMICO VIRTUAL -->
        <div class="ticket-thermal" id="ticketPrintArea">
            
            <!-- Encabezado de la Estación -->
            <header class="ticket-header">
                <h3>ESTACIÓN DE SERVICIO CENTRAL</h3>
                <p class="company-name">JOSPERÚ S.A.C.</p>
                <p class="ruc">R.U.C. N° 20601234567</p>
                <p class="address">Av. Javier Prado Este 1040, San Isidro, Lima</p>
                <p class="phone">Telf: (01) 456-7890</p>
            </header>

            <div class="ticket-separator"></div>

            <!-- Información General de la Boleta -->
            <section class="ticket-details">
                <h4>BOLETA DE VENTA ELECTRÓNICA</h4>
                <p class="serial-number">B001 - <?php echo str_pad($v['id'], 8, '0', STR_PAD_LEFT); ?></p>
                
                <div class="detail-row">
                    <span class="detail-label">Fecha Emisión:</span>
                    <span class="detail-value"><?php echo date('d/m/Y H:i:s', strtotime($v['fecha'])); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Operador:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($v['usuario_nombre']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Surtidor:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($v['surtidor_nombre']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Placa Vehículo:</span>
                    <span class="detail-value"><?php echo !empty($v['placa_vehiculo']) ? htmlspecialchars($v['placa_vehiculo']) : 'SIN PLACA'; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Método de Pago:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($v['metodo_pago']); ?></span>
                </div>
            </section>

            <div class="ticket-separator"></div>

            <!-- Tabla de Ítems despachados -->
            <table class="ticket-items-table">
                <thead>
                    <tr>
                        <th class="align-left">Combustible</th>
                        <th class="align-right">Gal.</th>
                        <th class="align-right">P.U.</th>
                        <th class="align-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="align-left"><?php echo htmlspecialchars($v['combustible_nombre']); ?></td>
                        <td class="align-right"><?php echo floatval($v['litros']); ?></td>
                        <td class="align-right">S/. <?php echo number_format($v['precio_unitario'], 2); ?></td>
                        <td class="align-right">S/. <?php echo number_format($v['total'], 2); ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="ticket-separator"></div>

            <!-- Totales -->
            <section class="ticket-totals">
                <div class="total-row">
                    <span>OP. GRAVADA:</span>
                    <span>S/. <?php echo number_format($v['total'] / 1.18, 2); ?></span>
                </div>
                <div class="total-row">
                    <span>I.G.V. (18%):</span>
                    <span>S/. <?php echo number_format($v['total'] - ($v['total'] / 1.18), 2); ?></span>
                </div>
                <div class="total-row grand-total">
                    <span>TOTAL A PAGAR:</span>
                    <span>S/. <?php echo number_format($v['total'], 2); ?></span>
                </div>
            </section>

            <div class="ticket-separator"></div>

            <!-- Sección de Pie y QR -->
            <footer class="ticket-footer">
                <div class="qr-container">
                    <div class="simulated-qr-pattern"></div>
                </div>
                <p class="sunat-disclaimer">Representación impresa de la Boleta de Venta Electrónica. Consulte su validez en el portal oficial de SUNAT.</p>
                <p class="thank-you">¡Muchas gracias por su preferencia!</p>
            </footer>

        </div>

        <!-- Botones de Acción (Se ocultan automáticamente al imprimir físicamente) -->
        <div class="ticket-actions-row no-print ticket-actions-centered">
            <!-- Imprimir físicamente -->
            <button onclick="window.print();" class="btn-action-print btn-pos-print">
                <i class='bx bx-printer'></i> Imprimir POS
            </button>
            
            <!-- Compartir WhatsApp -->
            <?php
                $wsText = "¡Hola! Te comparto tu Boleta de Venta Electrónica del Grifo JOSPERÚ:\n\n"
                        . "Boleta: B001-" . str_pad($v['id'], 8, '0', STR_PAD_LEFT) . "\n"
                        . "Combustible: " . $v['combustible_nombre'] . "\n"
                        . "Galones: " . number_format($v['litros'], 4) . " Gal\n"
                        . "Total Pagado: S/. " . number_format($v['total'], 2) . "\n"
                        . "Placa: " . (!empty($v['placa_vehiculo']) ? $v['placa_vehiculo'] : 'Sin Placa') . "\n\n"
                        . "¡Muchas gracias por tu preferencia!";
                $wsUrl = "https://api.whatsapp.com/send?text=" . urlencode($wsText);
            ?>
            <a href="<?php echo $wsUrl; ?>" target="_blank" class="btn-action-whatsapp">
                <i class='bx bxl-whatsapp'></i> Compartir WS
            </a>

            <!-- Nuevo Despacho -->
            <a href="ventas" class="btn-action-new btn-action-pos-new">
                <i class='bx bx-plus-circle'></i> Nuevo
            </a>
        </div>

    <?php endif; ?>

</div>
