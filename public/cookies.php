<?php
if (!isset($_COOKIE['cookies_aceitos'])):
?>
  <div id="cookiesModal" class="cookies-modal">
    <div class="cookies-content">
      <p>🍪 Este site utiliza cookies para melhorar sua experiência. Ao continuar navegando, você concorda com o uso de cookies.</p>
      <button id="aceitarCookies">Aceitar</button>
    </div>
  </div>
  <script>
    document.getElementById("aceitarCookies").addEventListener("click", function() {
      document.cookie = "cookies_aceitos=true; path=/; max-age=" + (60 * 60 * 24 * 7);
      document.getElementById("cookiesModal").style.display = "none";
    });
  </script>

<?php
endif;
?>
