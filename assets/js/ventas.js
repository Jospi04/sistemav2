/**
 * ==========================================================================
 * JOSPERÚ OPERACIONES — LÓGICA DE REGISTRO DE DESPACHOS (ventas.js)
 * ==========================================================================
 */

document.addEventListener('DOMContentLoaded', function() {
    const surtidorSelect = document.getElementById('surtidor_id');
    const litrosInput = document.getElementById('litros');
    const importeInput = document.getElementById('importe');
    const infoFuelName = document.getElementById('infoFuelName');
    const infoFuelPrice = document.getElementById('infoFuelPrice');
    const billboardAmount = document.getElementById('billboardAmount');

    if (!surtidorSelect || !litrosInput || !importeInput) return;

    let precioPorGalon = 0;

    surtidorSelect.addEventListener('change', function() {
        const option = surtidorSelect.options[surtidorSelect.selectedIndex];
        if (option && option.value !== "") {
            precioPorGalon = parseFloat(option.getAttribute('data-precio')) || 0;
            const nombreCombustible = option.getAttribute('data-combustible') || '-';
            
            if (infoFuelName) infoFuelName.textContent = nombreCombustible;
            if (infoFuelPrice) infoFuelPrice.textContent = 'S/. ' + precioPorGalon.toFixed(2);
        } else {
            precioPorGalon = 0;
            if (infoFuelName) infoFuelName.textContent = '-';
            if (infoFuelPrice) infoFuelPrice.textContent = 'S/. 0.00';
        }
        recalcularDesdeGalones();
    });

    litrosInput.addEventListener('input', recalcularDesdeGalones);
    importeInput.addEventListener('input', recalcularDesdeSoles);

    function recalcularDesdeGalones() {
        const galones = parseFloat(litrosInput.value) || 0;
        if (precioPorGalon > 0 && galones > 0) {
            const total = galones * precioPorGalon;
            importeInput.value = total.toFixed(2);
            if (billboardAmount) billboardAmount.textContent = 'S/. ' + total.toFixed(2);
        } else if (galones === 0) {
            importeInput.value = '';
            if (billboardAmount) billboardAmount.textContent = 'S/. 0.00';
        }
    }

    function recalcularDesdeSoles() {
        const soles = parseFloat(importeInput.value) || 0;
        if (precioPorGalon > 0 && soles > 0) {
            const galones = soles / precioPorGalon;
            litrosInput.value = galones.toFixed(4);
            if (billboardAmount) billboardAmount.textContent = 'S/. ' + soles.toFixed(2);
        } else if (soles === 0) {
            litrosInput.value = '';
            if (billboardAmount) billboardAmount.textContent = 'S/. 0.00';
        }
    }
});
