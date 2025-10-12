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

    document.getElementById("jogarNovamente").addEventListener("click", () => {
      modal.remove();
      location.reload();
    });

    document.getElementById("voltarMenu").addEventListener("click", () => {
      window.location.href = "../config.html";
    });
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
