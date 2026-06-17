/**
 * ==========================================================================
 * BROSTERIA 24/7 OPERACIONES — LÓGICA DE LA LANDING PAGE PÚBLICA (home.js)
 * ==========================================================================
 */

// Funciones del Modal de Demo/Simulador Público
function openDemoModal() {
    const modal = document.getElementById('demoModal');
    if (modal) {
        modal.classList.add('active');
    }
    document.body.style.overflow = 'hidden'; // Detener scroll de fondo
    actualizarPrecioCombustible();
}

function closeDemoModal() {
    const modal = document.getElementById('demoModal');
    if (modal) {
        modal.classList.remove('active');
    }
    document.body.style.overflow = 'auto'; // Restaurar scroll
}

function actualizarPrecioCombustible() {
    const combustibleSelect = document.getElementById('demoCombustible');
    if (!combustibleSelect) return;

    const selectedOption = combustibleSelect.options[combustibleSelect.selectedIndex];
    if (!selectedOption) return;

    const stock = selectedOption.getAttribute('data-stock');
    const stockLabel = document.getElementById('demoStockLabel');

    if (stockLabel) {
        stockLabel.textContent = `Stock Disponible: ${stock} Unidades`;
        stockLabel.style.color = "var(--color-valley-green)";
    }

    calcularDemoTotal();
}

function calcularDemoTotal() {
    const litrosInput = document.getElementById('demoLitros');
    const combustibleSelect = document.getElementById('demoCombustible');
    const totalSpan = document.getElementById('demoTotal');
    const stockLabel = document.getElementById('demoStockLabel');

    if (!litrosInput || !combustibleSelect || !totalSpan) return;

    const selectedOption = combustibleSelect.options[combustibleSelect.selectedIndex];
    if (!selectedOption) return;

    const stock = parseFloat(selectedOption.getAttribute('data-stock')) || 0;
    const precioPorUnidad = parseFloat(combustibleSelect.value) || 0;
    const cantidad = parseFloat(litrosInput.value);

    if (isNaN(cantidad) || cantidad <= 0) {
        totalSpan.textContent = 'S/ 0.00';
        if (stockLabel) {
            stockLabel.textContent = `Stock Disponible: ${stock} Unidades`;
            stockLabel.style.color = "var(--color-valley-green)";
        }
    } else {
        if (stockLabel) {
            if (cantidad > stock) {
                stockLabel.textContent = `⚠️ Excede el Stock disponible (${stock} Und)`;
                stockLabel.style.color = "#d9534f"; // Rojo de alerta
            } else {
                stockLabel.textContent = `Stock Disponible: ${stock} Unidades`;
                stockLabel.style.color = "var(--color-valley-green)";
            }
        }
        const total = cantidad * precioPorUnidad;
        totalSpan.textContent = 'S/ ' + total.toFixed(2);
    }
}

// EFECTO DE ROTACIÓN 3D INTERACTIVA CON FÍSICA SUAVE (LERP) Y LINTERNA CINEMÁTICA
document.addEventListener('DOMContentLoaded', function() {
    // Escuchar el cambio en el selector de combustible del simulador
    const demoSelect = document.getElementById('demoCombustible');
    if (demoSelect) {
        demoSelect.addEventListener('change', actualizarPrecioCombustible);
    }

    // Cerrar modal de simulación al hacer clic en el fondo grisáceo
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('demoModal');
        if (modal && event.target === modal) {
            closeDemoModal();
        }
    });

    // DETECTAR PANTALLA TÁCTIL (MÓVIL): Si el dispositivo no tiene mouse, salir del LERP
    if (window.matchMedia('(hover: none)').matches) {
        return;
    }

    const card = document.querySelector('.full-bleed-landscape-wrapper');
    if (!card) return;

    let rect = card.getBoundingClientRect();
    
    // Variables de física de suavizado (Lerp)
    let currentX = rect.width / 2;
    let currentY = rect.height / 2;
    let targetX = currentX;
    let targetY = currentY;
    
    let currentRotX = 0;
    let currentRotY = 0;
    let targetRotX = 0;
    let targetRotY = 0;
    
    let isHovering = false;

    // Recalcular dimensiones al redimensionar pantalla
    window.addEventListener('resize', () => {
        rect = card.getBoundingClientRect();
    });

    card.addEventListener('mouseenter', function() {
        isHovering = true;
        rect = card.getBoundingClientRect();
    });

    card.addEventListener('mousemove', function(e) {
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        targetX = x;
        targetY = y;

        const centerX = rect.width / 2;
        const centerY = rect.height / 2;

        // Inclinación máxima de 8 grados (muy elegante y fluida)
        targetRotX = ((centerY - y) / centerY) * 8;
        targetRotY = ((x - centerX) / centerX) * 8;
    });

    card.addEventListener('mouseleave', function() {
        isHovering = false;
        targetRotX = 0;
        targetRotY = 0;
        // La linterna se desliza elegantemente al centro de la tarjeta al salir
        targetX = rect.width / 2;
        targetY = rect.height / 2;
    });

    // Bucle de renderizado continuo (60 FPS)
    function updateSmoothPhysics() {
        // Factor de suavizado (0.075 = movimiento con inercia muy orgánica y premium)
        const ease = 0.075;

        // Interpolar posiciones de linterna
        currentX += (targetX - currentX) * ease;
        currentY += (targetY - currentY) * ease;

        // Interpolar ángulos de rotación 3D
        currentRotX += (targetRotX - currentRotX) * ease;
        currentRotY += (targetRotY - currentRotY) * ease;

        // Aplicar transformaciones 3D fluidas
        if (isHovering) {
            card.style.transform = `perspective(1000px) rotateX(${currentRotX}deg) rotateY(${currentRotY}deg) scale(1.025)`;
        } else {
            // Retorno suave a la posición neutra antes de ceder control a la flotación CSS
            const distRot = Math.abs(currentRotX) + Math.abs(currentRotY);
            if (distRot > 0.05) {
                card.style.transform = `perspective(1000px) rotateX(${currentRotX}deg) rotateY(${currentRotY}deg) scale(1)`;
            }
        }

        // Calcular porcentajes y actualizar variables CSS de la linterna
        const pctX = (currentX / rect.width) * 100;
        const pctY = (currentY / rect.height) * 100;
        card.style.setProperty('--shine-x', `${pctX}%`);
        card.style.setProperty('--shine-y', `${pctY}%`);

        requestAnimationFrame(updateSmoothPhysics);
    }

    // Iniciar bucle físico
    updateSmoothPhysics();
});
