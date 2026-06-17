/**
 * ==========================================================================
 * BROSTERIA 24/7 OPERACIONES — LÓGICA DE GESTIÓN DE PERSONAL (usuarios.js)
 * ==========================================================================
 */

function openDeleteModal(event, deleteUrl, nombreUsuario) {
    if (event) event.preventDefault();
    
    const modal = document.getElementById('deleteConfirmModal');
    const title = document.getElementById('deleteModalTitle');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    
    if (title) {
        title.textContent = `¿Eliminar a ${nombreUsuario}?`;
    }
    
    if (confirmBtn) {
        confirmBtn.href = deleteUrl;
    }
    
    if (modal) {
        modal.style.display = 'flex';
    }
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteConfirmModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Cerrar modal al hacer clic en el fondo gris translúcido del overlay
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('deleteConfirmModal');
    if (overlay) {
        overlay.addEventListener('click', function(event) {
            if (event.target === overlay) {
                closeDeleteModal();
            }
        });
    }
});
