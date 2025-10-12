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

  // Ajusta espaçamento e tamanho do tabuleiro conforme o tamanho escolhido
  switch (tamanho) {
    case 2:
      tabuleiro.style.gridGap = "20px";
      tabuleiro.style.width = "400px";
      tabuleiro.style.height = "400px";
      break;
    case 4:
      tabuleiro.style.gridGap = "15px";
      tabuleiro.style.width = "500px";
      tabuleiro.style.height = "500px";
      break;
    case 6:
      tabuleiro.style.gridGap = "12px";
      tabuleiro.style.width = "600px";
      tabuleiro.style.height = "600px";
      break;
    case 8:
      tabuleiro.style.gridGap = "11px";
      tabuleiro.style.width = "650px";
      tabuleiro.style.height = "650px";
      break;
    default:
      tabuleiro.style.gridGap = "10px";
      tabuleiro.style.width = "650px";
      tabuleiro.style.height = "650px";
  }

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
