/**
 * ==========================================================================
 * BROSTERIA 24/7 OPERACIONES — LÓGICA DE REPORTES Y AUDITORÍA DE PEDIDOS (reportes.js)
 * ==========================================================================
 */

function openDeleteVentaModal(event, id) {
    event.preventDefault();
    const modal = document.getElementById('deleteVentaConfirmModal');
    const title = document.getElementById('deleteVentaModalTitle');
    const confirmBtn = document.getElementById('confirmDeleteVentaBtn');
    
    if (modal && title && confirmBtn) {
        title.textContent = `¿Anular Boleta #${id}?`;
        confirmBtn.href = `/ventas/eliminar?id=${id}`;
        modal.style.display = 'flex';
    }
}

function closeDeleteVentaModal() {
    const modal = document.getElementById('deleteVentaConfirmModal');
    if (modal) {
        modal.style.display = 'none';
    }
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
                // Redondear a cantidad entera para combos/platos
                document.getElementById('editLitrosInput').value = Math.round(parseFloat(data.litros));
                document.getElementById('editTotalInput').value = parseFloat(data.total).toFixed(2);
                document.getElementById('editPlacaInput').value = data.placa_vehiculo || '';
                document.getElementById('editMetodoInput').value = data.metodo_pago;
                
                const editModal = document.getElementById('editVentaModal');
                if (editModal) {
                    editModal.style.display = 'flex';
                }
            }
        })
        .catch(err => {
            console.error("Error al cargar datos de venta: ", err);
            if (typeof showPremiumToast === 'function') {
                showPremiumToast("Error al cargar la información de la venta.", "error");
            }
        });
}

function closeEditVentaModal() {
    const editModal = document.getElementById('editVentaModal');
    if (editModal) {
        editModal.style.display = 'none';
    }
}

function recalculateEditTotal() {
    const cantidadInput = document.getElementById('editLitrosInput');
    const precioInput = document.getElementById('editPrecioLitroInput');
    const totalInput = document.getElementById('editTotalInput');

    if (cantidadInput && precioInput && totalInput) {
        const cantidad = parseFloat(cantidadInput.value) || 0;
        const precio = parseFloat(precioInput.value) || 0;
        const total = cantidad * precio;
        totalInput.value = total.toFixed(2);
    }
}

function recalculateEditLitros() {
    const totalInput = document.getElementById('editTotalInput');
    const precioInput = document.getElementById('editPrecioLitroInput');
    const cantidadInput = document.getElementById('editLitrosInput');

    if (totalInput && precioInput && cantidadInput) {
        const total = parseFloat(totalInput.value) || 0;
        const precio = parseFloat(precioInput.value) || 0;
        if (precio > 0) {
            const cantidad = total / precio;
            cantidadInput.value = Math.round(cantidad);
        }
    }
}
