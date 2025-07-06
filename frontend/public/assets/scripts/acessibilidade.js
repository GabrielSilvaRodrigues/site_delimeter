function toggleContraste() {
    document.body.classList.toggle('alto-contraste');
    const btn = document.getElementById('contraste-btn');
    if (btn) {
        btn.setAttribute('aria-pressed', document.body.classList.contains('alto-contraste'));
    }
}

// Controle de tamanho da fonte
let fontSize = 100;
function aumentarFonte() {
    if (fontSize < 150) {
        fontSize += 10;
        document.documentElement.style.fontSize = fontSize + '%';
    }
}
function diminuirFonte() {
    if (fontSize > 80) {
        fontSize -= 10;
        document.documentElement.style.fontSize = fontSize + '%';
    }
}
function toggleDaltonismo(tipo) {
    document.body.classList.remove('protanopia', 'deuteranopia', 'tritanopia');
    if (!document.body.classList.contains(tipo)) {
        document.body.classList.add(tipo);
    } else {
        document.body.classList.remove(tipo);
    }
}
function resetarAcessibilidade() {
    // Remove alto contraste
    document.body.classList.remove('alto-contraste');

    // Restaura tamanho da fonte
    document.documentElement.style.fontSize = '';

    // Remove filtros de daltonismo
    document.body.classList.remove('protanopia', 'deuteranopia', 'tritanopia');

    // Atualiza atributos ARIA
    const btn = document.getElementById('contraste-btn');
    if (btn) btn.setAttribute('aria-pressed', 'false');
}
