class ModoTrapaca {
  constructor() {
    this.cartasEncontradas = new Set(); 
  }

  mostrarTodasCartas() {

    if (window.cartas && Array.isArray(window.cartas)) {
      window.cartas.forEach(carta => {
        // define a imagem frontal (caso ainda não tenha sido aplicada)
        const front = carta.elemento.querySelector('.carta-front');
        if (front) {
          front.style.backgroundImage = `url('../img/devs/${carta.nomeDev}.jpg')`;
        }

        // marca visualmente como revelada pelo modo trapaça
        carta.elemento.classList.add('trapaca-revelada');
        carta.elemento.classList.add('virada');

        carta.virada = true; // opcional: mantém estado coerente enquanto estiver visível
      });
      return;
    }

   
    const cartasElementos = document.querySelectorAll('.carta');
    cartasElementos.forEach(cartaEl => {
      const front = cartaEl.querySelector('.carta-front');
  
      cartaEl.classList.add('trapaca-revelada');
      cartaEl.classList.add('virada');
    });
  }

  voltarExibicaoNormal() {

    if (window.cartas && Array.isArray(window.cartas)) {
      window.cartas.forEach(carta => {

        if (carta.elemento.classList.contains('trapaca-revelada') && !carta.encontrada) {
          carta.elemento.classList.remove('trapaca-revelada');
          carta.elemento.classList.remove('virada');
          carta.virada = false;
        }
      });
      return;
    }

    // Fallback
    const cartasElementos = document.querySelectorAll('.carta');
    cartasElementos.forEach(cartaEl => {
      if (cartaEl.classList.contains('trapaca-revelada')) {
        cartaEl.classList.remove('trapaca-revelada');
        cartaEl.classList.remove('virada');
      }
    });
  }

  marcarComoEncontrada(cartaElemento) {
    // mantém registro dos elementos permanentemente encontrados
    this.cartasEncontradas.add(cartaElemento);

    cartaElemento.classList.remove('trapaca-revelada');
    
    cartaElemento.classList.add('virada');
  }

  resetar() {
    // volta tudo ao normal e limpa registro
    this.voltarExibicaoNormal();
    this.cartasEncontradas.clear();
    console.log('Modo Trapaça RESETADO');
  }
}

const modoTrapaca = new ModoTrapaca();

document.addEventListener('DOMContentLoaded', () => {
  const btnMostrarTodas = document.getElementById('btn-mostrar-todas');
  const btnVoltarNormal = document.getElementById('btn-voltar-normal');

  if (btnMostrarTodas) {
    btnMostrarTodas.addEventListener('click', (e) => {
      e.preventDefault();
      modoTrapaca.mostrarTodasCartas();
      
      
      btnMostrarTodas.style.display = 'none';
      if (btnVoltarNormal) {
        btnVoltarNormal.style.display = 'inline-block';
      }
    });
  }

  if (btnVoltarNormal) {
    btnVoltarNormal.addEventListener('click', (e) => {
      e.preventDefault();
      modoTrapaca.voltarExibicaoNormal();
      

      btnVoltarNormal.style.display = 'none';
      if (btnMostrarTodas) {
        btnMostrarTodas.style.display = 'inline-block';
      }
    });
  }
});

function registrarParEncontrado(carta1, carta2) {

  if (carta1 && carta1.elemento) modoTrapaca.marcarComoEncontrada(carta1.elemento);
  if (carta2 && carta2.elemento) modoTrapaca.marcarComoEncontrada(carta2.elemento);
}

if (typeof window !== 'undefined') {
  window.modoTrapaca = modoTrapaca;
  window.registrarParEncontrado = registrarParEncontrado;
}
