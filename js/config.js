document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("form-jogo");

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const tamanho = document.querySelector('select[name="size-game"]').value;
    const modo = form["game-mode"].value;

    if (!tamanho || !modo) {
      alert("Por favor, selecione o tamanho do tabuleiro e o modo de jogo.");
      return;
    }

    //Salva o tamanho no localStorage
    localStorage.setItem("tamanhoTabuleiro", tamanho);

    //Redireciona para o modo escolhido
    window.location.href = modo;
  });
});
