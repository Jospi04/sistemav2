/**
 * ==========================================================================
 * BROSTERIA 24/7 OPERACIONES — LÓGICA DE REGISTRO DE COMANDAS (ventas.js)
 * ==========================================================================
 */

document.addEventListener('DOMContentLoaded', function() {
    const comboSelect = document.getElementById('surtidor_id'); // Mapea al ID en la vista
    const cantidadInput = document.getElementById('litros');    // Mapea al ID en la vista (litros)
    const importeInput = document.getElementById('importe');      // Mapea al ID en la vista (importe)
    const infoComboName = document.getElementById('infoFuelName');
    const infoComboPrice = document.getElementById('infoFuelPrice');
    const billboardAmount = document.getElementById('billboardAmount');

    if (!comboSelect || !cantidadInput || !importeInput) return;

    let precioPorUnidad = 0;

    comboSelect.addEventListener('change', function() {
        const option = comboSelect.options[comboSelect.selectedIndex];
        if (option && option.value !== "") {
            precioPorUnidad = parseFloat(option.getAttribute('data-precio')) || 0;
            const nombreCombo = option.getAttribute('data-combustible') || '-';
            
            if (infoComboName) infoComboName.textContent = nombreCombo;
            if (infoComboPrice) infoComboPrice.textContent = 'S/. ' + precioPorUnidad.toFixed(2);
        } else {
            precioPorUnidad = 0;
            if (infoComboName) infoComboName.textContent = '-';
            if (infoComboPrice) infoComboPrice.textContent = 'S/. 0.00';
        }
        recalcularDesdeUnidades();
    });

    cantidadInput.addEventListener('input', recalcularDesdeUnidades);
    importeInput.addEventListener('input', recalcularDesdeSoles);

    function recalcularDesdeUnidades() {
        const cantidad = parseFloat(cantidadInput.value) || 0;
        if (precioPorUnidad > 0 && cantidad > 0) {
            const total = cantidad * precioPorUnidad;
            importeInput.value = total.toFixed(2);
            if (billboardAmount) billboardAmount.textContent = 'S/. ' + total.toFixed(2);
        } else if (cantidad === 0) {
            importeInput.value = '';
            if (billboardAmount) billboardAmount.textContent = 'S/. 0.00';
        }
    }

    function recalcularDesdeSoles() {
        const soles = parseFloat(importeInput.value) || 0;
        if (precioPorUnidad > 0 && soles > 0) {
            const cantidad = soles / precioPorUnidad;
            // Redondear a cantidad entera para plato/combo
            cantidadInput.value = Math.round(cantidad);
            if (billboardAmount) billboardAmount.textContent = 'S/. ' + soles.toFixed(2);
        } else if (soles === 0) {
            cantidadInput.value = '';
            if (billboardAmount) billboardAmount.textContent = 'S/. 0.00';
        }
    }
});
