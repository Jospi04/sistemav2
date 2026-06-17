function openDemoModal() {
    const modal = document.getElementById('demoModal');
    if (modal) {
        modal.classList.add('active');
    }
    document.body.style.overflow = 'hidden';
    actualizarPrecioCombustible();
}

function closeDemoModal() {
    const modal = document.getElementById('demoModal');
    if (modal) {
        modal.classList.remove('active');
    }
    document.body.style.overflow = 'auto';
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
                stockLabel.style.color = "#d9534f";
            } else {
                stockLabel.textContent = `Stock Disponible: ${stock} Unidades`;
                stockLabel.style.color = "var(--color-valley-green)";
            }
        }
        const total = cantidad * precioPorUnidad;
        totalSpan.textContent = 'S/ ' + total.toFixed(2);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const demoSelect = document.getElementById('demoCombustible');
    if (demoSelect) {
        demoSelect.addEventListener('change', actualizarPrecioCombustible);
    }

    window.addEventListener('click', function(event) {
        const modal = document.getElementById('demoModal');
        if (modal && event.target === modal) {
            closeDemoModal();
        }
    });

    if (window.matchMedia('(hover: none)').matches) {
        return;
    }

    const card = document.querySelector('.full-bleed-landscape-wrapper');
    if (!card) return;

    let rect = card.getBoundingClientRect();
    
    let currentX = rect.width / 2;
    let currentY = rect.height / 2;
    let targetX = currentX;
    let targetY = currentY;
    
    let currentRotX = 0;
    let currentRotY = 0;
    let targetRotX = 0;
    let targetRotY = 0;
    
    let isHovering = false;

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

        targetRotX = ((centerY - y) / centerY) * 8;
        targetRotY = ((x - centerX) / centerX) * 8;
    });

    card.addEventListener('mouseleave', function() {
        isHovering = false;
        targetRotX = 0;
        targetRotY = 0;
        targetX = rect.width / 2;
        targetY = rect.height / 2;
    });

    function updateSmoothPhysics() {
        const ease = 0.075;

        currentX += (targetX - currentX) * ease;
        currentY += (targetY - currentY) * ease;

        currentRotX += (targetRotX - currentRotX) * ease;
        currentRotY += (targetRotY - currentRotY) * ease;

        if (isHovering) {
            card.style.transform = `perspective(1000px) rotateX(${currentRotX}deg) rotateY(${currentRotY}deg) scale(1.025)`;
        } else {
            const distRot = Math.abs(currentRotX) + Math.abs(currentRotY);
            if (distRot > 0.05) {
                card.style.transform = `perspective(1000px) rotateX(${currentRotX}deg) rotateY(${currentRotY}deg) scale(1)`;
            }
        }

        const pctX = (currentX / rect.width) * 100;
        const pctY = (currentY / rect.height) * 100;
        card.style.setProperty('--shine-x', `${pctX}%`);
        card.style.setProperty('--shine-y', `${pctY}%`);

        requestAnimationFrame(updateSmoothPhysics);
    }

    updateSmoothPhysics();
});
