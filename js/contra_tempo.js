// ========== CRONÔMETRO MODO CONTRA O TEMPO (REGRESSIVO) ==========

let primeiroClick = true;
let tempoInicio = null;
let dataInicio = null;
let cronometroInterval = null;

// Configuração do tempo limite (em segundos)
const TEMPO_LIMITE = 300;
let tempoRestante = TEMPO_LIMITE;

// ========== INICIALIZAR ==========
window.addEventListener('DOMContentLoaded', () => {
    const cartas = document.querySelectorAll('.carta');
    
    cartas.forEach(carta => {
        carta.addEventListener('click', iniciarCronometroNoClick);
    });
    
    // Inicializar display com tempo inicial
    atualizarRelogio();
});

// ========== INICIAR CRONÔMETRO NO PRIMEIRO CLIQUE ==========
function iniciarCronometroNoClick() {
    if (primeiroClick) {
        primeiroClick = false;
        tempoInicio = Date.now();
        dataInicio = new Date();
        
        console.log('Partida iniciada em:', dataInicio.toLocaleString('pt-BR'));
        
        iniciarCronometroRegressivo();
    }
}

// ========== CRONÔMETRO REGRESSIVO ==========
function iniciarCronometroRegressivo() {
    cronometroInterval = setInterval(() => {
        tempoRestante--;
        atualizarRelogio();
        
        // Verificar se o tempo acabou
        if (tempoRestante <= 0) {
            gameOver();
        }
        
        // Opcional: Alerta visual quando falta pouco tempo
        if (tempoRestante === 10) {
            const relogio = document.querySelector('.relogio h2');
            if (relogio) {
                relogio.style.color = 'red';
            }
        }
    }, 1000);
}

function atualizarRelogio() {
    const minutos = Math.floor(tempoRestante / 60);
    const segundos = tempoRestante % 60;
    const display = `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
    
    // Atualizar no HTML
    const relogioElement = document.querySelector('.relogio h2');
    if (relogioElement) {
        relogioElement.textContent = display;
    }
}

// ========== GAME OVER ==========
function gameOver() {
    pararCronometro();
    alert('⏰ Tempo esgotado! Game Over');
    
    // Opcional: redirecionar ou reiniciar
    // window.location.href = '../config.html';
}

// ========== PARAR CRONÔMETRO (use quando o jogo terminar com vitória) ==========
function pararCronometro() {
    if (cronometroInterval) {
        clearInterval(cronometroInterval);
    }
}

// ========== OBTER TEMPO GASTO (para vitória) ==========
function obterTempoGasto() {
    const tempoGasto = TEMPO_LIMITE - tempoRestante;
    const minutos = Math.floor(tempoGasto / 60);
    const segundos = tempoGasto % 60;
    return `${minutos}:${segundos.toString().padStart(2, '0')}`;
}

// ========== OBTER DATA E HORA DE INÍCIO ==========
function obterDataInicio() {
    if (dataInicio) {
        return {
            dataCompleta: dataInicio,
            dataFormatada: dataInicio.toLocaleDateString('pt-BR'),
            horaFormatada: dataInicio.toLocaleTimeString('pt-BR'),
            dataHoraFormatada: dataInicio.toLocaleString('pt-BR'),
            timestamp: dataInicio.getTime()
        };
    }
    return null;
}

// ========== OBTER TODOS OS DADOS DA PARTIDA ==========
function obterDadosPartida() {
    const tempoGasto = TEMPO_LIMITE - tempoRestante;
    return {
        dataInicio: obterDataInicio(),
        tempoGasto: tempoGasto,
        tempoGastoFormatado: obterTempoGasto(),
        tempoRestante: tempoRestante,
        tempoLimite: TEMPO_LIMITE
    };
}