document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('sidebarToggle');
    const layoutContainer = document.querySelector('.layout-container');
    
    if (layoutContainer) {
        let backdrop = document.querySelector('.sidebar-backdrop');
        if (!backdrop) {
            backdrop = document.createElement('div');
            backdrop.className = 'sidebar-backdrop no-print';
            layoutContainer.appendChild(backdrop);
            
            backdrop.addEventListener('click', function() {
                layoutContainer.classList.remove('sidebar-mobile-open');
            });
        }
        
        if (toggleBtn) {
            if (window.innerWidth > 768) {
                if (localStorage.getItem('sidebar-collapsed') === 'true') {
                    layoutContainer.classList.add('sidebar-collapsed');
                }
            }
            
            toggleBtn.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    layoutContainer.classList.toggle('sidebar-mobile-open');
                } else {
                    layoutContainer.classList.toggle('sidebar-collapsed');
                    localStorage.setItem('sidebar-collapsed', layoutContainer.classList.contains('sidebar-collapsed'));
                }
            });
        }
    }
});

window.showPremiumToast = function(message, type = 'success') {
    const container = document.getElementById('premiumToastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `premium-toast-item toast-${type}`;
    
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
