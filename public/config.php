<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/globals.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/config.css">
    <link rel="stylesheet" href="../css/partida.css">
    <link rel="stylesheet" href="../css/cookies.css">
    <link rel="icon" type="image/jpg" href="../img/robito.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
    <title>CodeMatch</title>
</head>

<body class="index-body">
    <section id="config-game">
        <div class="botao-voltar">
            <a href="./index.php"><span class="material-symbols-outlined">arrow_back_ios</span></a>
        </div>
        <h1>CodeMatch</h1>
        <div class="background-div standart-form-div">
            <h2>Configuração do Jogo</h2>
            <select class="select-game" name="size-game">
                <option selected hidden value="">Tamanho do Tabuleiro</option>
                <option value="2">2x2</option>
                <option value="4">4x4</option>
                <option value="6">6x6</option>
                <option value="8">8x8</option>
            </select>

            <form id="form-jogo">
                <select class="select-game" name="game-mode" required>
                    <option selected hidden value="">Modo de jogo</option>
                    <option value="./jogo.php?modo=classico">Clássico</option>
                    <option value="./jogo.php?modo=contra_tempo">Contra o tempo</option>
                </select>

                <div class="standart-btn-position">
                    <button type="submit" class="standart-form-buttons form-items-gray hover-background">Jogar</button>
                </div>
            </form>
        </div>

        <div class="partida-buttons buttons-absolute">
            <a href="../backend/logout.php" title="Sair">
                <span class="material-symbols-outlined">logout</span>
            </a>
            <a href="ranking.php" title="Ranking Global">
                <span class="material-symbols-outlined">social_leaderboard</span>
            </a>
            <a href="perfil.php" title="Perfil">
                <span class="material-symbols-outlined">person</span>
            </a>
        </div>

        <img src="../img/robito_inclinado.png" alt="Robô verde água mascote do jogo CodeMatch">

    </section>
    <script src="../js/config.js"></script>
    <?php include("./cookies.php"); ?>    
</body>

</html>