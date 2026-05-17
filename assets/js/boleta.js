/**
 * ==========================================================================
 * JOSPERÚ OPERACIONES — VISOR E INTERACTIVIDAD DE TICKETS (boleta.js)
 * ==========================================================================
 */

function checkSearchSubmit(event) {
    if (event.key === 'Enter') {
        submitBoletaSearch();
    }
}

function submitBoletaSearch() {
    const idInput = document.getElementById('searchBoletaId');
    if (!idInput) return;

    const id = parseInt(idInput.value) || 0;
    if (id <= 0) {
        // showPremiumToast es una función global declarada en layout.php
        if (typeof showPremiumToast === 'function') {
            showPremiumToast("Por favor, ingrese un número de boleta válido.", "error");
        } else {
            alert("Por favor, ingrese un número de boleta válido.");
        }
        return;
    }
    // Redireccionar al visor de forma absoluta
    window.location.href = `/boleta?id=${id}`;
}
