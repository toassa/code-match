<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/globals.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="icon" type="image/jpg" href="../img/robito.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
    <title>CodeMatch</title>
</head>
<body class="index-body">
    <section id="index-login">
        <h1>CodeMatch</h1>
        <h3>Jogue no código da memória!</h3>
        <div class="background-div standart-form-div">
            <form action="../backend/login.php" method="POST" class="standart-form">
                <div class="input-box input-box-login">
                    <span class="material-symbols-outlined">person</span>
                    <input type="text" name="usuario" placeholder="Usuário" class="standart-form-items form-items-login form-items-gray" 
                    value="<?php echo isset($_COOKIE['usuario_salvo']) ? $_COOKIE['usuario_salvo'] : ''; ?>" required>
                </div>
                <div class="input-box input-box-login">
                    <span class="material-symbols-outlined">lock</span>
                    <input type="password" name="senha" placeholder="Senha" class="standart-form-items form-items-login form-items-gray"
                    value="<?php echo isset($_COOKIE['senha_salva']) ? $_COOKIE['senha_salva'] : ''; ?>" required>
                </div>
                <div class="input-box">
                    <label class="checkbox-label">
                        <input type="checkbox" name="lembrar"  <?php echo isset($_COOKIE['usuario_salvo']) ? 'checked' : ''; ?> >
                        <span class="checkmark"></span>
                        Lembrar-me
                    </label>
                </div>
                <div class="standart-btn-position">
                    <a href="cadastro.php" class="standart-form-buttons form-items-gray hover-border">Cadastre-se</a>
                    <button type="submit" class="standart-form-buttons form-items-orange hover-background">Entrar</button>
                </div>
            </form>
        </div>
    </section>
    <div class="index-logo">
        <img src="../img/robito_inclinado.png" alt="Robô verde água mascote do jogo CodeMatch">
    </div>
</body>
</html> 