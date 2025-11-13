<?php
session_start();
include("../backend/db_config.php");

$usuario_logado_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : (isset($_SESSION['ID']) ? $_SESSION['ID'] : null);

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$filtro_tabuleiro = isset($_GET['tabuleiro']) ? intval($_GET['tabuleiro']) : null;
$filtro_modalidade = isset($_GET['modalidade']) ? $conn->real_escape_string($_GET['modalidade']) : null;
$filtro_ordenacao = isset($_GET['ordenacao']) ? $_GET['ordenacao'] : 'tempo';

if ($filtro_tabuleiro && !in_array($filtro_tabuleiro, [2, 4, 6, 8])) {
    $filtro_tabuleiro = null;
}

if ($filtro_modalidade && !in_array(strtoupper($filtro_modalidade), ['CLASSICO', 'CONTRA O TEMPO'])) {
    $filtro_modalidade = null;
}

$sql = "SELECT 
            u.ID as Usuario_ID,
            u.Usuario,
            p.ID as Partida_ID,
            p.Tabuleiro,
            p.Modalidade,
            p.Duracao_partida,
            p.Jogadas,
            p.Data_partida,
            p.Tempo_regressivo
        FROM Partidas p
        INNER JOIN Usuarios u ON p.Usuario_ID = u.ID
        WHERE p.Resultado = 'VITÓRIA'";

if ($filtro_tabuleiro) {
    $sql .= " AND p.Tabuleiro = " . $filtro_tabuleiro;
}

if ($filtro_modalidade) {
    $filtro_modalidade_upper = strtoupper($filtro_modalidade);
    $sql .= " AND p.Modalidade = '" . $filtro_modalidade_upper . "'";
}

if ($filtro_ordenacao === 'jogadas') {
    $sql .= " ORDER BY p.Tabuleiro DESC, p.Jogadas ASC, p.Duracao_partida ASC";
} else {
    $sql .= " ORDER BY p.Tabuleiro DESC, p.Duracao_partida ASC, p.Jogadas ASC";
}

$sql .= " LIMIT 10";

$result = $conn->query($sql);

function formatarTempo($tempo)
{
    if (empty($tempo))
        return '--';

    if (substr($tempo, 0, 3) === '00:') {
        return substr($tempo, 3);
    }
    return $tempo;
}

function formatarData($data)
{
    return date('d/m/Y - H:i', strtotime($data));
}

function abreviarModalidade($modalidade)
{
    return $modalidade === 'CONTRA O TEMPO' ? 'CONTRA TEMPO' : 'CLÁSSICO';
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
    <link rel="stylesheet" href="../css/ranking.css">
    <link rel="stylesheet" href="../css/config_temporario.css">
    <link rel="stylesheet" href="../css/cookies.css">
    <link rel="icon" type="image/jpg" href="../img/robito.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
    <title>CodeMatch - Ranking Global</title>
</head>

<body class="index-body">
    <div class="botao-voltar">
        <a href="javascript:history.back()" title="Voltar">
            <span class="material-symbols-outlined">arrow_back_ios</span>
        </a>
    </div>

    <section id="ranking">
        <h1>RANKING GLOBAL</h1>

        <div class="div-pagina">
            <div class="div-filtro-box">
                <form method="GET" action="ranking.php" class="div-filtro">
                    <h2>FILTRAR</h2>

                    <p>TABULEIRO</p>
                    <select class="select-game" name="tabuleiro" aria-label="Filtrar por tamanho do tabuleiro">
                        <option value="">QUALQUER</option>
                        <option value="2" <?= $filtro_tabuleiro == 2 ? 'selected' : '' ?>>2X2</option>
                        <option value="4" <?= $filtro_tabuleiro == 4 ? 'selected' : '' ?>>4X4</option>
                        <option value="6" <?= $filtro_tabuleiro == 6 ? 'selected' : '' ?>>6X6</option>
                        <option value="8" <?= $filtro_tabuleiro == 8 ? 'selected' : '' ?>>8X8</option>
                    </select>

                    <p>MODO DE JOGO</p>
                    <select class="select-game" name="modalidade" aria-label="Filtrar por modo de jogo">
                        <option value="">QUALQUER</option>
                        <option value="CLASSICO" <?= $filtro_modalidade == 'CLASSICO' ? 'selected' : '' ?>>CLÁSSICO
                        </option>
                        <option value="CONTRA O TEMPO" <?= $filtro_modalidade == 'CONTRA O TEMPO' ? 'selected' : '' ?>>
                            CONTRA TEMPO</option>
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

                    <?php
                    $filtros_aplicados = isset($_GET['tabuleiro']) || isset($_GET['modalidade']);
                    ?>

                        <button type="submit" class="btn-filtro btn-aplicar">
                            APLICAR
                        </button>
                </form>
            </div>

            <div class="div-ranking">
                <table class="table-ranking">
                    <thead>
                        <tr>
                            <th class="tr-border-left"></th>
                            <th>USERNAME</th>
                            <th>TABULEIRO</th>
                            <th>MODALIDADE</th>
                            <th>TEMPO</th>
                            <th>JOGADAS</th>
                            <th class="tr-border-right">DATA/HORA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            $posicao = 1;
                            while ($row = $result->fetch_assoc()) {
                                $tempo = formatarTempo($row['Duracao_partida']);
                                $data = formatarData($row['Data_partida']);
                                $modalidade = abreviarModalidade($row['Modalidade']);

                                $classe_usuario = '';
                                if ($usuario_logado_id && $row['Usuario_ID'] == $usuario_logado_id) {
                                    $classe_usuario = 'usuario-logado';
                                }

                                echo "<tr class='{$classe_usuario}'>";
                                echo "<td class='tr-border-left'><strong>{$posicao}</strong></td>";
                                echo "<td>" . htmlspecialchars($row['Usuario']) . "</td>";
                                echo "<td><strong>{$row['Tabuleiro']}x{$row['Tabuleiro']}</strong></td>";
                                echo "<td>{$modalidade}</td>";
                                echo "<td>{$tempo}</td>";
                                echo "<td><strong>{$row['Jogadas']}</strong></td>";
                                echo "<td class='tr-border-right'>{$data}</td>";
                                echo "</tr>";

                                $posicao++;
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align: center; padding: 60px 20px; color: #666;'>
                        <strong style='font-size: 18px; display: block; margin-bottom: 8px;'>Nenhum resultado encontrado.</strong>
                        <span style='font-size: 14px;'>Tente ajustar os filtros ou seja o primeiro a conquistar este ranking!</span>
                      </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div class="partida-buttons buttons-absolute">
        <a href="index.php" title="Voltar ao início">
            <span class="material-symbols-outlined">home</span>
        </a>
        <a href="perfil.php" title="Meu perfil">
            <span class="material-symbols-outlined">person</span>
        </a>
        <a href="config.php" title="Jogar agora!">
            <img src="../img/robito_branco.png" alt="Jogar">
        </a>
    </div>

    <?php include("./cookies.php"); ?>
</body>

</html>

<?php
$conn->close();
?>