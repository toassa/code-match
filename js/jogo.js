document.addEventListener("DOMContentLoaded", () => {
  const tabuleiro = document.querySelector(".tabuleiro");
  const infoJogadas = document.getElementById("infoJogadas");

  class Carta {
    constructor(id, nomeDev, elemento) {
      this.id = id;
      this.nomeDev = nomeDev;
      this.elemento = elemento;
      this.virada = false;
      this.encontrada = false;
    }

    virar() {
      if (this.encontrada || this.virada) return;
      this.virada = true;
      this.elemento.querySelector(".carta-front").style.backgroundImage = `url('../../img/devs/${this.nomeDev}.jpg')`;
      this.elemento.classList.add("virada");
    }

    desvirar() {
      this.virada = false;
      this.elemento.classList.remove("virada");
    }
  }

  let tamanho = parseInt(localStorage.getItem("tamanhoTabuleiro"));
  if (![2, 4, 6, 8].includes(tamanho)) tamanho = 4;
  const totalCartas = tamanho * tamanho;

  const totalDevs = 32;
  const devs = Array.from({ length: totalDevs }, (_, i) => `dev${i + 1}`);

  const embaralhar = (array) => array.sort(() => Math.random() - 0.5);

  const devsSelecionados = embaralhar(devs).slice(0, totalCartas / 2);
  const devsDuplicados = embaralhar([...devsSelecionados, ...devsSelecionados]);

  const cartas = [];
  tabuleiro.innerHTML = "";

  devsDuplicados.forEach((dev, index) => {
    const cartaEl = document.createElement("div");
    cartaEl.classList.add("carta");

    cartaEl.innerHTML = `
      <div class="carta-inner">
        <div class="carta-front"></div>
        <div class="carta-back"></div>
      </div>
    `;

    const carta = new Carta(index, dev, cartaEl);
    cartas.push(carta);
    tabuleiro.appendChild(cartaEl);
  });

  let primeiraCarta = null;
  let segundaCarta = null;
  let bloqueio = false;
  let jogadas = 0;

  const atualizarJogadas = () => {
    jogadas++;
    if (infoJogadas) infoJogadas.textContent = `Número de jogadas: ${jogadas}`;
  };

  configurarDisplayInicial(); 
    
    tabuleiro.addEventListener('click', (e) => {
        const carta = e.target.closest('.carta');
        if (carta) {
            iniciarCronometroNoClick();
        }
    });

  const handleClick = (carta) => {
    if (bloqueio || carta.virada || carta.encontrada) return;

    carta.virar();

    if (!primeiraCarta) {
      primeiraCarta = carta;
    } else if (!segundaCarta) {
      segundaCarta = carta;
      atualizarJogadas();
      verificarPar();
    }
  };

  const verificarPar = () => {
    if (!primeiraCarta || !segundaCarta) return;
    bloqueio = true;

    if (primeiraCarta.nomeDev === segundaCarta.nomeDev) {
      primeiraCarta.encontrada = true;
      segundaCarta.encontrada = true;
      resetarSelecao();
      verificarVitoria();
    } else {
      setTimeout(() => {
        primeiraCarta.desvirar();
        segundaCarta.desvirar();
        resetarSelecao();
      }, 1000);
    }
  };

  const modalDesistencia = document.getElementById("desistencia-modal");
  const btnDesistir = document.getElementById("btn-desistir");
  const modalCancelar = document.getElementById("modal-cancelar");

  const mostrarModalDesistencia = () => {
    bloqueio = true;

    if (window.CMContraTempo && typeof window.CMContraTempo.pararCronometro === 'function') {
      window.CMContraTempo.pararCronometro();
    } else if (typeof pararCronometro === 'function') {
      pararCronometro();
    }

    modalDesistencia.style.display = 'flex';
  };

  const verificarVitoria = () => {
    const todasEncontradas = cartas.every((c) => c.encontrada);
    if (todasEncontradas) {
      mostrarModalVitoria();
    }
  };

  const mostrarModalVitoria = () => {
    const modal = document.createElement("div");
    modal.classList.add("modal-vitoria");
    modal.innerHTML = `
      <div class="overlay">
          <div class="background-div standart-form-div ">
              <h2>Parabéns!</h2>
              <p>Você encontrou todos os pares em <strong>${jogadas}</strong> jogadas!</p>
              <p>Deseja jogar outra partida?</p>
              <div class="standart-btn-position">
                  <a href="../perfil.html" class="standart-form-buttons form-items-orange hover-background">Não</a>
                  <a href="../config.html" class="standart-form-buttons form-items-orange hover-background">Sim</a>
                </div>
          </div>
      </div>
    `;
    document.body.appendChild(modal);
    
    const btnSim = modal.querySelector('a[href="../config.html"]');
    const btnNao = modal.querySelector('a[href="../perfil.html"]');

    if (btnSim) {
      btnSim.addEventListener("click", () => {
        modal.remove();
        location.reload();
      });
    }

    if (btnNao) {
      btnNao.addEventListener("click", () => {
        modal.remove();
        window.location.href = "../perfil.html";
      });
    }
  };

  const resetarSelecao = () => {
    [primeiraCarta, segundaCarta] = [null, null];
    bloqueio = false;
  };

  if (btnDesistir) {
    btnDesistir.addEventListener('click', (e) => {
      e.preventDefault();
      mostrarModalDesistencia();
    });
  }


  if (modalCancelar) {
    modalCancelar.addEventListener('click', (e) => {
      e.preventDefault();
      modalDesistencia.style.display = 'none';
      bloqueio = false;
    });
  }

  cartas.forEach((carta) => {
    carta.elemento.addEventListener("click", () => handleClick(carta));
  });

  modalDesistencia.style.display = 'none';

  console.log(`Tabuleiro ${tamanho}x${tamanho} carregado com ${totalCartas} cartas.`);
});

function mostrarModalDerrota() {
    const cartasElements = document.querySelectorAll('.carta');
    const todasCartas = Array.from(cartasElements);
    const cartasEncontradas = todasCartas.filter(carta => carta.classList.contains('encontrada') || 
                                                           carta.style.opacity === '0.5' ||
                                                           carta.dataset.encontrada === 'true');
    
    const paresEncontrados = Math.floor(cartasEncontradas.length / 2);
    const totalPares = Math.floor(todasCartas.length / 2);
    
    const infoJogadasEl = document.getElementById('infoJogadas');
    const jogadasText = infoJogadasEl ? infoJogadasEl.textContent : 'Número de jogadas: 0';
    const jogadas = jogadasText.match(/\d+/) ? jogadasText.match(/\d+/)[0] : '0';
    
    const modal = document.createElement("div");
    modal.classList.add("modal-derrota");
    modal.innerHTML = `
      <div class="overlay">
          <div class="background-div standart-form-div ">
              <h2>Tempo Esgotado!</h2>
              <p>Você não conseguiu encontrar todos os pares a tempo.</p>
              <p>Pares encontrados: <strong>${paresEncontrados}/${totalPares}</strong></p>
              <p>Jogadas realizadas: <strong>${jogadas}</strong></p>
              <p>Deseja tentar novamente?</p>
              <div class="standart-btn-position">
                  <a href="../config.html" id="btn-voltar-menu" class="standart-form-buttons form-items-gray hover-border">Voltar ao Menu</a>
                  <a href="#" id="btn-tentar-novamente" class="standart-form-buttons form-items-orange hover-background">Tentar Novamente</a>
                </div>
          </div>
      </div>
    `;
    document.body.appendChild(modal);

    const btnTentarNovamente = modal.querySelector('#btn-tentar-novamente');
    const btnVoltarMenu = modal.querySelector('#btn-voltar-menu');

    if (btnTentarNovamente) {
      btnTentarNovamente.addEventListener("click", (e) => {
        e.preventDefault();
        modal.remove();
        location.reload();
      });
    }

    if (btnVoltarMenu) {
      btnVoltarMenu.addEventListener("click", () => {
        modal.remove();
        window.location.href = "../config.html";
      });
    }
}

function configurarDisplayInicial() {
    if (modoDeJogo === 'contra_tempo') {
        modoTitulo.textContent = 'Contra o Tempo';
        relogioRegressivo.style.display = 'block';
        
        const minutos = Math.floor(TEMPO_LIMITE / 60);
        const segundos = TEMPO_LIMITE % 60;
        infoTempoRegresivo.textContent = `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
        
    } else {
        modoTitulo.textContent = 'Clássico';
        relogioRegressivo.style.display = 'none';
    }
}

const urlParams = new URLSearchParams(window.location.search);
const modoDeJogo = urlParams.get('modo');

const modoTitulo = document.getElementById('modoTitulo');
const infoTempoClassico = document.getElementById('infoTempoClassico');
const relogioRegressivo = document.getElementById('relogioRegressivo');
const infoTempoRegresivo = document.getElementById('infoTempoRegresivo');

let cronometroInterval = null;
let primeiroClick = true;
let tempoInicio = null;

let tempoDecorrido = 0;

function calcularTempoPorTamanho(tamanho) {
  const tempo = {
    2: 15,
    4: 45,
    6: 120,
    8: 180
  };
  return tempo[tamanho] || 60;
}

let tamanhoGlobal = parseInt(localStorage.getItem("tamanhoTabuleiro"));
if (![2, 4, 6, 8].includes(tamanhoGlobal)) tamanhoGlobal = 4;

const TEMPO_LIMITE = calcularTempoPorTamanho(tamanhoGlobal);
let tempoRestante = TEMPO_LIMITE;

function pararCronometro() {
    if (cronometroInterval) {
        clearInterval(cronometroInterval);
    }
}

function iniciarCronometroNoClick() {
    if (primeiroClick) {
        primeiroClick = false;
        tempoInicio = Date.now();
        
        if (modoDeJogo === 'contra_tempo') {
            iniciarCronometroRegressivo();
            iniciarCronometroProgressivo();
        } else {
            iniciarCronometroProgressivo();
        }
    }
}

function iniciarCronometroProgressivo() {
    cronometroInterval = setInterval(() => {
        tempoDecorrido = Math.floor((Date.now() - tempoInicio) / 1000);
        const minutos = Math.floor(tempoDecorrido / 60);
        const segundos = tempoDecorrido % 60;
        const display = `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
        
        if (infoTempoClassico) {
            infoTempoClassico.textContent = `Tempo de partida: ${display}`;
        }
    }, 1000);
}

function iniciarCronometroRegressivo() {
    tempoRestante = TEMPO_LIMITE;
    cronometroInterval = setInterval(() => {
        if (tempoRestante <= 0) {
            pararCronometro();
            mostrarModalDerrota();
            return;
        }
        
        tempoRestante--;
        
        const tempoParaDisplay = Math.max(0, tempoRestante);
        const minutos = Math.floor(tempoParaDisplay / 60);
        const segundos = tempoParaDisplay % 60;
        const display = `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
        
        if (infoTempoRegresivo) {
            infoTempoRegresivo.textContent = display;
        }
    }, 1000);
}