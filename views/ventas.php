<?php
// Asegurar que la lista de combos esté inicializada
$surtidoresList = $surtidores ?? [];
?>
<div class="sales-container">
    
    <!-- NOTIFICACIONES Y ALERTAS DE ÉXITO -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert-success" role="alert" style="border-left: 4px solid #d97706; background-color: rgba(217, 119, 6, 0.05); color: #d97706;">
            <div class="alert-icon-box">
                <i class="bx bx-check-circle" style="color: #d97706;"></i>
            </div>
            <div class="alert-content">
                <p class="alert-title" style="color: #d97706; font-family: 'Outfit', sans-serif;">Pedido Registrado</p>
                <p class="alert-message"><?php echo htmlspecialchars($_SESSION['success']); ?></p>
                <?php if (isset($_SESSION['last_venta_id'])): ?>
                    <a href="boleta" class="btn-receipt-shortcut" style="background-color: #d97706;">
                        Ver Boleta Emitida <i class="bx bx-receipt"></i>
                    </a>
                <?php endif; ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- NOTIFICACIONES Y ALERTAS DE ERROR -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-error" role="alert" style="border-left: 4px solid #e11d48; background-color: rgba(225, 29, 72, 0.05); color: #e11d48;">
            <div class="alert-icon-box">
                <i class="bx bx-error-circle" style="color: #e11d48;"></i>
            </div>
            <div class="alert-content">
                <p class="alert-title" style="color: #e11d48; font-family: 'Outfit', sans-serif;">Incidencia en Proceso</p>
                <p class="alert-message"><?php echo htmlspecialchars($_SESSION['error']); ?></p>
            </div>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- TARJETA PRINCIPAL DEL DISPENSADOR -->
    <div class="dispatch-card">
        <div class="card-title-bar">
            <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800;">Comanda de Pedido Digital</h2>
            <p>Seleccione la opción del menú e indique la cantidad y número de mesa.</p>
        </div>

        <form action="ventas" method="POST" class="dispatch-form" id="dispatchForm">
            
            <!-- Campo: Selección de Surtidor -->
            <div class="form-group">
                <label for="surtidor_id" style="font-family: 'Outfit', sans-serif;">Combo / Opción del Menú *</label>
                <div class="select-wrapper">
                    <select name="surtidor_id" id="surtidor_id" required>
                        <option value="" disabled selected>-- Seleccione Combo del Menú --</option>
                        <?php foreach ($surtidoresList as $s): ?>
                            <option value="<?php echo $s['id']; ?>" data-precio="<?php echo $s['precio_litro']; ?>" data-combustible="<?php echo htmlspecialchars($s['combustible_nombre']); ?>">
                                <?php echo htmlspecialchars($s['nombre']); ?> (S/. <?php echo number_format($s['precio_litro'], 2); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Fila de Detalles Informativos del Combustible -->
            <div class="fuel-info-strip">
                <div class="info-block">
                    <span class="info-block-label">Plato Base</span>
                    <span class="info-block-val" id="infoFuelName">-</span>
                </div>
                <div class="info-block">
                    <span class="info-block-label">Precio Unitario</span>
                    <span class="info-block-val" id="infoFuelPrice">S/. 0.00</span>
                </div>
            </div>

            <!-- Fila Doble: Importe en Soles y Volumen en Galones -->
            <div class="form-double-row">
                <div class="form-group">
                    <label for="importe" style="font-family: 'Outfit', sans-serif;">Importe a Pagar (S/.) (Guía)</label>
                    <input type="number" step="0.01" min="0.50" id="importe" placeholder="S/. 0.00">
                </div>

                <div class="form-group">
                    <label for="litros" style="font-family: 'Outfit', sans-serif;">Cantidad Pedida *</label>
                    <input type="number" step="1" min="1" name="litros" id="litros" required placeholder="0" value="1">
                </div>
            </div>

            <!-- Fila Doble: Placa del Vehículo y Método de Pago -->
            <div class="form-double-row">
                <div class="form-group">
                    <label for="placa_vehiculo" style="font-family: 'Outfit', sans-serif;">Mesa / Nombre Cliente</label>
                    <input type="text" name="placa_vehiculo" id="placa_vehiculo" placeholder="Ej: Mesa 5 o Para Llevar" maxlength="30">
                </div>
                
                <div class="form-group">
                    <label for="metodo_pago" style="font-family: 'Outfit', sans-serif;">Forma de Pago del Cliente *</label>
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
            <div class="billboard-total" style="background-color: #18181b; border-left: 5px solid #e11d48;">
                <span class="billboard-label" style="color: #a1a1aa;">IMPORTE TOTAL (PEN)</span>
                <span class="billboard-amount" id="billboardAmount" style="color: #fff; font-family: 'Outfit', sans-serif;">S/. 0.00</span>
            </div>

            <!-- Botones de Envío -->
            <div class="form-actions-bar">
                <button type="submit" class="btn-submit-dispatch" style="background-color: #e11d48; border-radius: 10px;">Confirmar y Enviar a Cocina ⚡</button>
            </div>

        </form>
    </div>

</div>
