<?php
// Asegurar que la lista de surtidores esté inicializada
$surtidoresList = $surtidores ?? [];
?>
<div class="sales-container">
    
    <!-- NOTIFICACIONES Y ALERTAS DE ÉXITO -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert-success" role="alert">
            <div class="alert-icon-box">
                <i class="bx bx-check-circle"></i>
            </div>
            <div class="alert-content">
                <p class="alert-title">Despacho Registrado</p>
                <p class="alert-message"><?php echo htmlspecialchars($_SESSION['success']); ?></p>
                <?php if (isset($_SESSION['last_venta_id'])): ?>
                    <a href="boleta" class="btn-receipt-shortcut">
                        Ver Boleta Emitida <i class="bx bx-receipt"></i>
                    </a>
                <?php endif; ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- NOTIFICACIONES Y ALERTAS DE ERROR -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-error" role="alert">
            <div class="alert-icon-box">
                <i class="bx bx-error-circle"></i>
            </div>
            <div class="alert-content">
                <p class="alert-title">Incidencia en Proceso</p>
                <p class="alert-message"><?php echo htmlspecialchars($_SESSION['error']); ?></p>
            </div>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- TARJETA PRINCIPAL DEL DISPENSADOR -->
    <div class="dispatch-card">
        <div class="card-title-bar">
            <h2>Panel de Despacho Digital</h2>
            <p>Seleccione la manguera de combustible e indique la medida del flujo.</p>
        </div>

        <form action="ventas" method="POST" class="dispatch-form" id="dispatchForm">
            
            <!-- Campo: Selección de Surtidor -->
            <div class="form-group">
                <label for="surtidor_id">Surtidor / Manguera de Origen *</label>
                <div class="select-wrapper">
                    <select name="surtidor_id" id="surtidor_id" required>
                        <option value="" disabled selected>-- Seleccione Manguera Activa --</option>
                        <?php foreach ($surtidoresList as $s): ?>
                            <option value="<?php echo $s['id']; ?>" data-precio="<?php echo $s['precio_litro']; ?>" data-combustible="<?php echo htmlspecialchars($s['combustible_nombre']); ?>">
                                <?php echo htmlspecialchars($s['nombre']); ?> (S/. <?php echo number_format($s['precio_litro'], 2); ?> / Galón)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Fila de Detalles Informativos del Combustible (Actualizados con JS) -->
            <div class="fuel-info-strip">
                <div class="info-block">
                    <span class="info-block-label">Tipo de Combustible</span>
                    <span class="info-block-val" id="infoFuelName">-</span>
                </div>
                <div class="info-block">
                    <span class="info-block-label">Tarifa por Galón</span>
                    <span class="info-block-val" id="infoFuelPrice">S/. 0.00</span>
                </div>
            </div>

            <!-- Fila Doble: Importe en Soles y Volumen en Galones -->
            <div class="form-double-row">
                <div class="form-group">
                    <label for="importe">Importe a Despachar (S/.)</label>
                    <input type="number" step="0.01" min="0.50" id="importe" placeholder="S/. 0.00">
                </div>

                <div class="form-group">
                    <label for="litros">Volumen Despachado (Galones) *</label>
                    <input type="number" step="0.0001" min="0.01" name="litros" id="litros" required placeholder="0.0000">
                </div>
            </div>

            <!-- Fila Doble: Placa del Vehículo y Método de Pago -->
            <div class="form-double-row">
                <div class="form-group">
                    <label for="placa_vehiculo">Placa del Vehículo</label>
                    <input type="text" name="placa_vehiculo" id="placa_vehiculo" placeholder="ABC-123" maxlength="10">
                </div>
                
                <div class="form-group">
                    <label for="metodo_pago">Forma de Pago del Cliente *</label>
                    <div class="select-wrapper">
                        <select name="metodo_pago" id="metodo_pago" required>
                            <option value="" disabled selected>-- Seleccione Forma de Pago --</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Tarjeta">Tarjeta de Crédito / Débito</option>
                            <option value="Yape/Plin">Monedero Digital (Yape / Plin)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Caja de Visualización de Cuentas (Calculado dinámicamente) -->
            <div class="billboard-total">
                <span class="billboard-label">IMPORTE TOTAL (PEN)</span>
                <span class="billboard-amount" id="billboardAmount">S/. 0.00</span>
            </div>

            <!-- Botones de Envío -->
            <div class="form-actions-bar">
                <button type="submit" class="btn-submit-dispatch">Confirmar y Despachar</button>
            </div>

        </form>
    </div>

</div>
