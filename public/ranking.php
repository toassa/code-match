<?php
session_start();
include("../backend/db_config.php");

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$filtro_tabuleiro = isset($_GET['tabuleiro']) ? intval($_GET['tabuleiro']) : null;
$filtro_modalidade = isset($_GET['modalidade']) ? $_GET['modalidade'] : null;
$filtro_ordenacao = isset($_GET['ordenacao']) ? $_GET['ordenacao'] : 'tempo';

$sql = "SELECT 
            u.Usuario,
            p.Tabuleiro,
            p.Modalidade,
            p.Duracao_partida,
            p.Jogadas,
            p.Data_partida
        FROM Partidas p
        INNER JOIN Usuarios u ON p.Usuario_ID = u.ID
        WHERE p.Resultado = 'VITÓRIA'";

// Adiciona filtros
if ($filtro_tabuleiro) {
    $sql .= " AND p.Tabuleiro = $filtro_tabuleiro";
}

if ($filtro_modalidade) {
    $filtro_modalidade = strtoupper($filtro_modalidade);
    $sql .= " AND p.Modalidade = '$filtro_modalidade'";
}

if ($filtro_ordenacao === 'jogadas') {
    $sql .= " ORDER BY p.Jogadas ASC, p.Duracao_partida ASC";
} else {
    $sql .= " ORDER BY p.Duracao_partida ASC, p.Jogadas ASC";
}

$sql .= " LIMIT 50";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/globals.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/config.css">
    <link rel="stylesheet" href="../css/ranking.css">
    <link rel="stylesheet" href="../css/config_temporario.css">
    <link rel="icon" type="image/jpg" href="../img/robito.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
    <title>CodeMatch - Ranking Global</title>
</head>

<body class="index-body">
    <div class="botao-voltar">
        <a href="javascript:history.back()"><span class="material-symbols-outlined">arrow_back_ios</span></a>
    </div>
    <section id="ranking">
        <h1>RANKING GLOBAL</h1>
        <div class="div-pagina">
            <div class="div-filtro-box">
                <form method="GET" action="ranking.php" class="div-filtro">
                    <h2>FILTRAR</h2>
                    <p>TABULEIRO</p>
                    <select class="select-game" name="tabuleiro">
                        <option value="">QUALQUER</option>
                        <option value="2" <?= $filtro_tabuleiro == 2 ? 'selected' : '' ?>>2X2</option>
                        <option value="4" <?= $filtro_tabuleiro == 4 ? 'selected' : '' ?>>4X4</option>
                        <option value="6" <?= $filtro_tabuleiro == 6 ? 'selected' : '' ?>>6X6</option>
                        <option value="8" <?= $filtro_tabuleiro == 8 ? 'selected' : '' ?>>8X8</option>
                    </select>
                    
                    <p>MODO DE JOGO</p>
                    <select class="select-game" name="modalidade">
                        <option value="">QUALQUER</option>
                        <option value="CLASSICO" <?= $filtro_modalidade == 'CLASSICO' ? 'selected' : '' ?>>CLÁSSICO</option>
                        <option value="CONTRA O TEMPO" <?= $filtro_modalidade == 'CONTRA O TEMPO' ? 'selected' : '' ?>>CONTRA TEMPO</option>
                    </select>
                    
                    <p>FILTRO</p>
                    <div class="filtros-radio">
                        <input type="radio" id="filtro-tempo" name="ordenacao" value="tempo" 
                               <?= $filtro_ordenacao == 'tempo' ? 'checked' : '' ?>>
                        <label for="filtro-tempo" class="label-filtro">TEMPO</label>

                        <input type="radio" id="filtro-jogadas" name="ordenacao" value="jogadas"
                               <?= $filtro_ordenacao == 'jogadas' ? 'checked' : '' ?>>
                        <label for="filtro-jogadas" class="label-filtro">Nº DE JOGADAS</label>
                    </div>
                    
                    <button type="submit" class="standart-form-buttons form-items-orange hover-background" 
                            style="margin-top: 20px; width: 100%;">
                        APLICAR FILTROS
                    </button>
                </form>
            </div>
            
            <div class="div-ranking">
                <table class="table-ranking">
                    <thead>
                        <tr>
                            <th class="tr-border-left">#</th>
                            <th>USERNAME</th>
                            <th>TABULEIRO</th>
                            <th>MODALIDADE</th>
                            <th>TEMPO</th>
                            <th>JOGADAS</th>
                            <th>DATA/HORA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            $posicao = 1;
                            while ($row = $result->fetch_assoc()) {
                                // Formata o tempo (remove horas se for 00)
                                $tempo = $row['Duracao_partida'];
                                if (substr($tempo, 0, 3) === '00:') {
                                    $tempo = substr($tempo, 3);
                                }
                                
                                // Formata data
                                $data = date('d/m/Y - H:i', strtotime($row['Data_partida']));
                                
                                echo "<tr>";
                                echo "<td class='tr-border-left'>{$posicao}</td>";
                                echo "<td>" . htmlspecialchars($row['Usuario']) . "</td>";
                                echo "<td>{$row['Tabuleiro']}x{$row['Tabuleiro']}</td>";
                                echo "<td>{$row['Modalidade']}</td>";
                                echo "<td>{$tempo}</td>";
                                echo "<td>{$row['Jogadas']}</td>";
                                echo "<td class='tr-border-right'>{$data}</td>";
                                echo "</tr>";
                                
                                $posicao++;
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align: center; padding: 40px;'>
                                    Nenhuma partida encontrada com os filtros selecionados.
                                  </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div class="partida-buttons buttons-absolute">
        <a href="index.php" title="Sair">
            <span class="material-symbols-outlined">logout</span>
        </a>
        <a href="perfil.php" title="Perfil">
            <span class="material-symbols-outlined">person</span>
        </a>
        <a href="config.php" title="Jogar agora!">
            <img src="../img/robito_branco.png" alt="">
        </a>
    </div>
    <?php include("./cookies.php"); ?>
</body>
</html>

<?php
$conn->close();
?>