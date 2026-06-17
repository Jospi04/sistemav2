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
        if (typeof showPremiumToast === 'function') {
            showPremiumToast("Por favor, ingrese un número de boleta válido.", "error");
        } else {
            alert("Por favor, ingrese un número de boleta válido.");
        }
        return;
    }
    window.location.href = `/boleta?id=${id}`;
}
