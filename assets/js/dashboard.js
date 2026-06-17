/**
 * ==========================================================================
 * BROSTERIA 24/7 OPERACIONES — LÓGICA DEL PANEL DE CONTROL (dashboard.js)
 * ==========================================================================
 */

function openRefillModal() {
    const modal = document.getElementById('refillModal');
    if (!modal) return;
    modal.style.display = 'flex';
    updateRefillCapacity();
}

function closeRefillModal() {
    const modal = document.getElementById('refillModal');
    if (!modal) return;
    modal.style.display = 'none';
}

function updateRefillCapacity() {
    const select = document.getElementById('refill_inventario_id');
    if (!select) return;
    
    const option = select.options[select.selectedIndex];
    if (!option) return;

    const max = parseFloat(option.getAttribute('data-max')) || 0;
    const actual = parseFloat(option.getAttribute('data-actual')) || 0;
    const available = max - actual;
    
    const label = document.getElementById('refillCapacityLabel');
    if (label) {
        label.textContent = `Espacio libre en almacén: ${available.toFixed(0)} Unidades`;
    }

    const cantidadInput = document.getElementById('refill_cantidad');
    if (cantidadInput) {
        cantidadInput.max = available.toFixed(0);
    }
}

// Inicializar escuchadores cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    const refillSelect = document.getElementById('refill_inventario_id');
    if (refillSelect) {
        refillSelect.addEventListener('change', updateRefillCapacity);
    }

    // Cerrar modal al hacer clic en el fondo grisáceo del lightbox
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('refillModal');
        if (modal && event.target === modal) {
            closeRefillModal();
        }
    });
});
