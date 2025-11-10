<?php
if (!isset($_COOKIE['cookies_aceitos'])):
?>
  <div id="cookiesModal" class="cookies-modal">
    <div class="cookies-content">
      <p>🍪 Este site utiliza cookies para melhorar sua experiência. Ao continuar navegando, você concorda com o uso de cookies.</p>
      <button id="aceitarCookies">Aceitar</button>
    </div>
  </div>

  <style>
    .cookies-modal {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background-color: rgba(0, 0, 0, 0.8);
      color: var(--light-gray);
      text-align: center;
      padding: 20px;
      z-index: 9999;
    }

    .cookies-content {
      max-width: 600px;
      margin: 0 auto;
    }

    .cookies-content p {
      margin-bottom: 15px;
      font-size: 16px;
    }

    .cookies-content button {
      background-color: var(--orange);
      color: white;
      border: none;
      padding: 10px 25px;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .cookies-content button:hover {
      background-color: var(--orange);
    }
  </style>

  <script>
    document.getElementById("aceitarCookies").addEventListener("click", function() {
      document.cookie = "cookies_aceitos=true; path=/; max-age=" + (60 * 60 * 24 * 7);
=      document.getElementById("cookiesModal").style.display = "none";
    });
  </script>

<?php
endif;
?>
