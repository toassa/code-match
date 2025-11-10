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
      this.elemento.querySelector(".carta-front").style.backgroundImage = `url('../img/devs/${this.nomeDev}.jpg')`;
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
  window.cartas = cartas;
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


      if (typeof registrarParEncontrado === 'function') {
        registrarParEncontrado(primeiraCarta, segundaCarta);
      }

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
  const btnVoltarDesistir = document.getElementById("btn-voltar-desistir");
  const modalCancelar = document.getElementById("modal-cancelar");

  const mostrarModalDesistencia = (destino = "perfil.php") => {
    bloqueio = true;

    if (window.CMContraTempo && typeof window.CMContraTempo.pararCronometro === 'function') {
      window.CMContraTempo.pararCronometro();
    } else if (typeof pararCronometro === 'function') {
      pararCronometro();
    }

    const modalDesistencia = document.getElementById("desistencia-modal");
    const confirmarBtn = document.getElementById("modal-confirmar");

    confirmarBtn.setAttribute("href", destino);

    modalDesistencia.style.display = 'flex';
  };


  const verificarVitoria = () => {
    const todasEncontradas = cartas.every((c) => c.encontrada);
    if (todasEncontradas) {
      pararCronometro();
      mostrarModalVitoria();
    }
  };

  function mostrarModalVitoria() {
    // SALVA A PARTIDA COMO VITÓRIA
    salvarPartida('VITÓRIA');
    
    const infoJogadasEl = document.getElementById('infoJogadas');
    const jogadasText = infoJogadasEl ? infoJogadasEl.textContent : 'Número de jogadas: 0';
    const jogadas = jogadasText.match(/\d+/) ? jogadasText.match(/\d+/)[0] : '0';
    
    const modal = document.createElement("div");
    modal.classList.add("modal-vitoria");
    modal.innerHTML = `
      <div class="overlay">
          <div class="background-div standart-form-div ">
              <h2>Parabéns!</h2>
              <p>Você encontrou todos os pares em <strong>${jogadas}</strong> jogadas!</p>
              <p>Deseja jogar outra partida?</p>
              <div class="standart-btn-position">
                  <a href="perfil.php" class="standart-form-buttons form-items-orange hover-background">Não</a>
                  <a href="config.php" class="standart-form-buttons form-items-orange hover-background">Sim</a>
                </div>
          </div>
      </div>
    `;
    document.body.appendChild(modal);
  
    const btnSim = modal.querySelector('a[href="config.php"]');
    const btnNao = modal.querySelector('a[href="perfil.php"]');
  
    if (btnSim) {
      btnSim.addEventListener("click", () => {
        modal.remove();
        location.reload();
      });
    }
  
    if (btnNao) {
      btnNao.addEventListener("click", () => {
        modal.remove();
        window.location.href = "perfil.php";
      });
    }
  }

  const resetarSelecao = () => {
    [primeiraCarta, segundaCarta] = [null, null];
    bloqueio = false;
  };

  if (btnDesistir) {
    btnDesistir.addEventListener('click', (e) => {
      e.preventDefault();
      mostrarModalDesistencia("perfil.php");
    });
  }

  if (btnVoltarDesistir) {
    btnVoltarDesistir.addEventListener('click', (e) => {
      e.preventDefault();
      mostrarModalDesistencia("config.php");
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

function salvarPartida(resultado) {
  // Pega o tamanho do tabuleiro
  const tabuleiro = tamanhoGlobal;
  
  // Determina a modalidade
  const modalidade = (modoDeJogo === 'contra_tempo') ? 'CONTRA O TEMPO' : 'CLASSICO';
  
  // Converte o tempo decorrido para formato HH:MM:SS
  const horas = Math.floor(tempoDecorrido / 3600);
  const minutos = Math.floor((tempoDecorrido % 3600) / 60);
  const segundos = tempoDecorrido % 60;
  const duracao = `${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
  
  // Pega o número de jogadas do elemento
  const infoJogadasEl = document.getElementById('infoJogadas');
  const jogadasText = infoJogadasEl ? infoJogadasEl.textContent : 'Número de jogadas: 0';
  const jogadas = jogadasText.match(/\d+/) ? parseInt(jogadasText.match(/\d+/)[0]) : 0;
  
  // Prepara o tempo regressivo (só para modo contra o tempo)
  let tempo_regressivo = null;
  if (modalidade === 'CONTRA O TEMPO') {
    const tempoRestanteAtual = Math.max(0, tempoRestante);
    const minReg = Math.floor(tempoRestanteAtual / 60);
    const segReg = tempoRestanteAtual % 60;
    tempo_regressivo = `00:${minReg.toString().padStart(2, '0')}:${segReg.toString().padStart(2, '0')}`;
  }
  
  // Monta o objeto com os dados da partida
  const dadosPartida = {
    tabuleiro: tabuleiro,
    modalidade: modalidade,
    resultado: resultado, // 'VITÓRIA' ou 'DERROTA'
    duracao: duracao,
    jogadas: jogadas,
    tempo_regressivo: tempo_regressivo
  };
  
  // Envia os dados para o PHP via fetch
  fetch('../backend/salvar_partida.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(dadosPartida)
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      console.log('Partida salva com sucesso!', data);
    } else {
      console.error('Erro ao salvar partida:', data.message);
    }
  })
  .catch(error => {
    console.error('Erro na requisição:', error);
  });
}

function registrarDesistencia() {
  salvarPartida('DERROTA');
}

const mostrarModalDesistencia = (destino = "perfil.php") => {
  bloqueio = true;

  // Para o cronômetro
  if (window.CMContraTempo && typeof window.CMContraTempo.pararCronometro === 'function') {
    window.CMContraTempo.pararCronometro();
  } else if (typeof pararCronometro === 'function') {
    pararCronometro();
  }

  const modalDesistencia = document.getElementById("desistencia-modal");
  const confirmarBtn = document.getElementById("modal-confirmar");

  // CORREÇÃO: Remove o evento anterior e adiciona novo com salvamento
  const novoConfirmarBtn = confirmarBtn.cloneNode(true);
  confirmarBtn.parentNode.replaceChild(novoConfirmarBtn, confirmarBtn);
  
  // Adiciona evento de click que salva antes de redirecionar
  novoConfirmarBtn.addEventListener('click', (e) => {
    e.preventDefault();
    
    // Salva a partida como DERROTA
    registrarDesistencia();
    
    // Aguarda um pouco para garantir que a requisição foi enviada
    setTimeout(() => {
      window.location.href = destino;
    }, 500);
  });

  modalDesistencia.style.display = 'flex';
};

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

let progressivoInterval = null;
let regressivoInterval = null;
let primeiroClick = true;
let tempoInicio = null;

let tempoDecorrido = 0;

function calcularTempoPorTamanho(tamanho) {
  const tempo = {
    2: 15,
    4: 45,
    6: 120,
    8: 300
  };
  return tempo[tamanho] || 60;
}

let tamanhoGlobal = parseInt(localStorage.getItem("tamanhoTabuleiro"));
if (![2, 4, 6, 8].includes(tamanhoGlobal)) tamanhoGlobal = 4;

const TEMPO_LIMITE = calcularTempoPorTamanho(tamanhoGlobal);
let tempoRestante = TEMPO_LIMITE;

function pararCronometro() {
  if (progressivoInterval) {
    clearInterval(progressivoInterval);
    progressivoInterval = null;
  }
  if (regressivoInterval) {
    clearInterval(regressivoInterval);
    regressivoInterval = null;
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
  if (progressivoInterval) return;
  progressivoInterval = setInterval(() => {
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
  if (regressivoInterval) return;
  tempoRestante = TEMPO_LIMITE;
  regressivoInterval = setInterval(() => {
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