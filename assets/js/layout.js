/**
 * ==========================================================================
 * JOSPERÚ OPERACIONES — LÓGICA DEL LAYOUT MAESTRO GLOBAL (layout.js)
 * ==========================================================================
 */

document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('sidebarToggle');
    const layoutContainer = document.querySelector('.layout-container');
    
    if (toggleBtn && layoutContainer) {
        // Cargar estado de colapsado desde localStorage
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            layoutContainer.classList.add('sidebar-collapsed');
        }
        
        toggleBtn.addEventListener('click', function() {
            layoutContainer.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebar-collapsed', layoutContainer.classList.contains('sidebar-collapsed'));
        });
    }
});

// FUNCIÓN DE ALERTA/NOTIFICACIÓN PREMIUM TOAST (REEMPLAZO UNIVERSAL DE ALERT)
window.showPremiumToast = function(message, type = 'success') {
    const container = document.getElementById('premiumToastContainer');
    if (!container) return;

    // Crear la notificación
    const toast = document.createElement('div');
    toast.className = `premium-toast-item toast-${type}`;
    
    // Asignar icono según tipo
    let icon = "<i class='bx bx-check-circle'></i>";
    if (type === 'error') {
        icon = "<i class='bx bx-error-circle'></i>";
    } else if (type === 'warning') {
        icon = "<i class='bx bx-alarm-exclamation'></i>";
    }

    toast.innerHTML = `
        <div class="toast-icon-box">${icon}</div>
        <div class="toast-content" style="flex: 1;">
            <span class="toast-message" style="display: block;">${message}</span>
        </div>
        <span class="toast-close" onclick="this.parentElement.remove()" style="cursor: pointer;"><i class='bx bx-x'></i></span>
    `;

    container.appendChild(toast);

    // Auto remover después de 4 segundos con animación suave
    setTimeout(() => {
        if (toast && toast.parentElement) {
            toast.style.animation = 'fadeOutToast 0.4s ease forwards';
            setTimeout(() => {
                if (toast && toast.parentElement) {
                    toast.remove();
                }
            }, 400);
        }
    }, 4000);
};
