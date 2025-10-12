document.addEventListener("DOMContentLoaded", () => {
  // Seleciona elementos da interface
  const tabuleiro = document.querySelector(".tabuleiro");
  const infoModo = document.getElementById("modoTitulo");
  const infoTabuleiro = document.getElementById("infoTabuleiro");
  const infoJogadas = document.getElementById("infoJogadas");
  const infoTempo = document.getElementById("infoTempo");

  let tamanho = parseInt(localStorage.getItem("tamanhoTabuleiro"));
  if (![2, 4, 6, 8].includes(tamanho)) tamanho = 4; // padrão: 4x4
  let modo = localStorage.getItem("modoJogo");
  if (modo !== "tempo" && modo !== "classico") {
    modo = location.pathname.includes("contra_tempo") ? "tempo" : "classico";
  }

  //interface da partida
  if (infoModo) infoModo.textContent = modo === "tempo" ? "Contra o Tempo" : "Clássico";
  if (infoTabuleiro) infoTabuleiro.textContent = `Tabuleiro ${tamanho}x${tamanho}`;
  if (infoJogadas) infoJogadas.textContent = "Número de jogadas: 0";

  //cronometro de exemplo (parte do wesley)
  if (infoTempo && modo === "tempo") {
    infoTempo.textContent = "01:00";
  }

  //criar tabuleiro
  tabuleiro.style.display = "grid";
  tabuleiro.style.gridTemplateColumns = `repeat(${tamanho}, 1fr)`;
  tabuleiro.style.gridTemplateRows = `repeat(${tamanho}, 1fr)`;
  tabuleiro.style.gridGap = tamanho === 8 ? "12px" : "10px";
  tabuleiro.style.maxWidth = tamanho === 8 ? "960px" : "900px";
  tabuleiro.innerHTML = "";

  const totalCartas = tamanho * tamanho;

  //cria cada carta
  for (let i = 0; i < totalCartas; i++) {
    const carta = document.createElement("div");
    carta.classList.add("carta");

    //exemplo de estrutura para virar uma carta
    carta.innerHTML = `
      <div class="carta-inner">
        <div class="carta-front"></div>
        <div class="carta-back"></div>
      </div>
    `;

    tabuleiro.appendChild(carta);
  }

  console.log(`✅ Tabuleiro ${tamanho}x${tamanho} criado no modo "${modo}"`);
});
