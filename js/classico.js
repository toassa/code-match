// ========== CRONÔMETRO MODO CLÁSSICO (PROGRESSIVO) ==========

let primeiroClick = true;
let tempoInicio = null;
let dataInicio = null;
let cronometroInterval = null;
let tempoDecorrido = 0;

// ========== INICIALIZAR ==========
window.addEventListener('DOMContentLoaded', () => {
    const cartas = document.querySelectorAll('.carta');
    
    cartas.forEach(carta => {
        carta.addEventListener('click', iniciarCronometroNoClick);
    });
    
    // Inicializar display
    atualizarDisplay();
});

// ========== INICIAR CRONÔMETRO NO PRIMEIRO CLIQUE ==========
function iniciarCronometroNoClick() {
    if (primeiroClick) {
        primeiroClick = false;
        tempoInicio = Date.now();
        iniciarCronometro();
    }
}

// ========== CRONÔMETRO PROGRESSIVO ==========
function iniciarCronometro() {
    cronometroInterval = setInterval(() => {
        tempoDecorrido = Math.floor((Date.now() - tempoInicio) / 1000);
        atualizarDisplay();
    }, 1000);
}

function atualizarDisplay() {
    const minutos = Math.floor(tempoDecorrido / 60);
    const segundos = tempoDecorrido % 60;
    const display = `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
    
    // Atualizar no HTML
    const tempoElement = document.querySelector('.partida-info p:nth-child(3)');
    if (tempoElement) {
        tempoElement.textContent = `Tempo de partida: ${display}`;
    }
}

// ========== PARAR CRONÔMETRO (use quando o jogo terminar) ==========
function pararCronometro() {
    if (cronometroInterval) {
        clearInterval(cronometroInterval);
    }
}

// ========== OBTER TEMPO DECORRIDO ==========
function obterTempoDecorrido() {
    const minutos = Math.floor(tempoDecorrido / 60);
    const segundos = tempoDecorrido % 60;
    return `${minutos}:${segundos.toString().padStart(2, '0')}`;
}
//deposis tenho que colocar uma forma de contar as jogadas