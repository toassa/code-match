<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/globals.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/cadastro.css">
    <link rel="stylesheet" href="../css/cookies.css">
    <link rel="icon" type="image/jpg" href="../img/robito.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
    <title>CodeMatch - Cadastro</title>
</head>
<body class="index-body">
    <div id="index-cadastro">
        <div id="cadastro-title">
            <h2>CodeMatch</h2>
        </div>

        <form action="../backend/cadastro_usuario.php" method="POST" id="cadastro-form">
            <div class="input-box input-box-cadastro">
                <span class="material-symbols-outlined">person</span>
                <input type="text" name="nome_completo" placeholder="Nome Completo" class="standart-form-items form-items-cadastro form-items-gray" required>
            </div>

            <div class="input-box input-box-cadastro">
                <span class="material-symbols-outlined">calendar_month</span>
                <input type="text" name="data_nasc" placeholder="Data de Nascimento" class="standart-form-items form-items-cadastro form-items-gray" onfocus="this.type='date'" onblur="if (!this.value) this.type='text'" required>
            </div>

            <div class="input-box input-box-cadastro">
                <span class="material-symbols-outlined">badge</span>
                <input type="text" name="cpf" placeholder="CPF" class="standart-form-items form-items-cadastro form-items-gray" maxlength="14" required>
            </div>

            <div class="input-box input-box-cadastro">
                <span class="material-symbols-outlined">phone_in_talk</span>
                <input type="text" name="telefone" placeholder="Telefone" class="standart-form-items form-items-cadastro form-items-gray" maxlength="15" required>
            </div>

            <div class="input-box input-box-cadastro">
                <span class="material-symbols-outlined">alternate_email</span>
                <input type="email" name="email" placeholder="Email" class="standart-form-items form-items-cadastro form-items-gray" required>
            </div>

            <div class="input-box input-box-cadastro">
                <span class="material-symbols-outlined">account_circle</span>
                <input type="text" name="usuario" placeholder="Usuário" class="standart-form-items form-items-cadastro form-items-gray" required>
            </div>

            <div class="input-box input-box-cadastro">
                <span class="material-symbols-outlined">lock</span>
                <input type="password" name="senha" placeholder="Senha" class="standart-form-items form-items-cadastro form-items-gray" required>
            </div>

            <div class="standart-btn-position">
                <button type="submit" class="standart-form-buttons form-items-orange hover-background">Cadastrar</button>
            </div>
        </form>
    </div>

    <div class="index-logo">
        <img src="../img/robito_inclinado.png" alt="Robô verde água mascote do jogo CodeMatch">
        <a href="index.php" id="cadastro-link" class="form-items-green hover-border">Já possui cadastro?</a>
    </div>

    <script src="../js/validation.js"></script>
    <?php include("./cookies.php"); ?>
</body>
</html>
