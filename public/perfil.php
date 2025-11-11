<?php
session_start();
include("../backend/db_config.php"); 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão com o banco de dados: " . $conn->connect_error);
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}
$usuario_id = $_SESSION['usuario_id'];

$sql_usuario = "SELECT Nome_Completo, Usuario, Email, Data_nasc, Cpf, Telefone FROM Usuarios WHERE ID = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $usuario_id);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();
$usuario = $result_usuario->fetch_assoc();
$stmt_usuario->close();

$filtro_modo = $_GET['modo_jogo'] ?? 'QUALQUER';
$filtro_tabuleiro = $_GET['tabuleiro'] ?? 'TODOS';

$sql_stats = "SELECT 
                COUNT(ID) as total_partidas,
                SUM(CASE WHEN Resultado = 'VITÓRIA' THEN 1 ELSE 0 END) as total_vitorias
              FROM Partidas
              WHERE Usuario_ID = ?";

$params_stats = [$usuario_id];
$types_stats = "i";

if ($filtro_modo != 'QUALQUER') {
    $sql_stats .= " AND Modalidade = ?";
    $params_stats[] = $filtro_modo;
    $types_stats .= "s";
}

if ($filtro_tabuleiro != 'TODOS') {
    $sql_stats .= " AND Tabuleiro = ?";
    $params_stats[] = $filtro_tabuleiro;
    $types_stats .= "i";
}

$stmt_stats = $conn->prepare($sql_stats);
if ($stmt_stats === false) {
    die("Erro ao preparar a consulta de estatísticas: " . $conn->error);
}
$stmt_stats->bind_param($types_stats, ...$params_stats);
$stmt_stats->execute();
$result_stats = $stmt_stats->get_result();
$stats = $result_stats->fetch_assoc();

$total_partidas = $stats['total_partidas'] ?? 0;
$total_vitorias = $stats['total_vitorias'] ?? 0;
$stmt_stats->close();

$sql_historico = "SELECT Tabuleiro, Modalidade, Duracao_partida, Resultado, Data_partida 
                  FROM Partidas 
                  WHERE Usuario_ID = ?";

$params_historico = [$usuario_id];
$types_historico = "i";

$sql_historico .= " ORDER BY Data_partida DESC";

$stmt_historico = $conn->prepare($sql_historico);
if ($stmt_historico === false) {
    die("Erro ao preparar a consulta de histórico: " . $conn->error);
}
$stmt_historico->bind_param($types_historico, ...$params_historico);
$stmt_historico->execute();
$result_historico = $stmt_historico->get_result();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/globals.css">
    <link rel="stylesheet" href="../css/config.css">
    <link rel="stylesheet" href="../css/config_temporario.css">
    <link rel="stylesheet" href="../css/perfil.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="icon" type="image/jpg" href="../img/robito.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
    <title>CodeMatch</title>
</head>

<body class="perfil-body">
    <div class="botao-voltar">
        <a href="javascript:history.back()"><span class="material-symbols-outlined">arrow_back_ios</span></a>
    </div>

    <section id="perfil-table">
        <div class="div-pagina">
            <div class="div-tabela">
                <h2>HISTORICO PESSOAL</h2>
                <table class="table-perfil">
                    <thead>
                        <tr>
                            <th class="tr-border-left"></th>
                            <th>TABULEIRO</th>
                            <th>MODALIDADE</th>
                            <th>TEMPO</th>
                            <th>RESULTADO</th>
                            <th>DATA/HORA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_historico->num_rows > 0): ?>
                            <?php $count = 1; ?>
                            <?php while($partida = $result_historico->fetch_assoc()): ?>
                                <?php
                                $classe_resultado = ($partida['Resultado'] == 'VITÓRIA') ? 'resultado-vitoria' : 'resultado-derrota';
                                ?>
                                <tr class="<?php echo $classe_resultado; ?>">
                                    <td class="tr-border-left"><?php echo $count++; ?>.</td>
                                    <td><?php echo htmlspecialchars($partida['Tabuleiro']); ?>x<?php echo htmlspecialchars($partida['Tabuleiro']); ?></td>
                                    <td><?php echo htmlspecialchars($partida['Modalidade']); ?></td>
                                    <td><?php echo htmlspecialchars(date('H:i:s', strtotime($partida['Duracao_partida']))); ?></td>
                                    <td><?php echo htmlspecialchars($partida['Resultado']); ?></td>
                                    <td class="tr-border-right"><?php echo htmlspecialchars(date('d/m/Y - H:i', strtotime($partida['Data_partida']))); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align:center; color: black; background-color: #f0f0f0;">Nenhuma partida encontrada.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="div-info-perfil">
                <h2>PERFIL</h2>
                <div class="personal-info">
                    <div id="view-mode">
                        <p class="title">USERNAME</p>
                        <p class="content"><?php echo htmlspecialchars($usuario['Usuario']); ?></p>
                        <p class="title">NOME COMPLETO</p>
                        <p class="content" data-field="nome"><?php echo htmlspecialchars($usuario['Nome_Completo']); ?></p>
                        <p class="title">E-MAIL</p>
                        <p class="content" data-field="email"><?php echo htmlspecialchars($usuario['Email']); ?></p>
                        <a href="#" id="edit-button" class="edit-icon" title="Editar">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                    </div>

                    <form id="edit-mode" style="display: none;">
                        <p class="title">USERNAME</p>
                        <p class="content"><?php echo htmlspecialchars($usuario['Usuario']); ?></p>

                        <div class="input-box">
                            <p class="title">NOME COMPLETO</p>
                            <input type="text" id="edit-nome" name="nome_completo" class="standart-form-items form-items-gray"
                                value="<?php echo htmlspecialchars($usuario['Nome_Completo']); ?>">
                        </div>

                        <div class="input-box">
                            <p class="title">E-MAIL</p>
                            <input type="email" id="edit-email" name="email" class="standart-form-items form-items-gray"
                                value="<?php echo htmlspecialchars($usuario['Email']); ?>">
                        </div>

                        <div class="input-box">
                            <p class="title">SENHA</p>
                            <input type="password" id="edit-senha" name="senha" class="standart-form-items form-items-gray"
                                placeholder="Deixe em branco para não alterar">
                        </div>

                        <div class="input-box">
                            <p class="title">TELEFONE</p>
                            <input type="tel" id="edit-telefone" class="standart-form-items form-items-gray"
                                value="<?php echo htmlspecialchars($usuario['Telefone']); ?>" maxlength="15">
                        </div>

                        <p class="title">DATA DE NASCIMENTO</p>
                        <p class="content"><?php echo htmlspecialchars(date('d/m/Y', strtotime($usuario['Data_nasc']))); ?></p>

                        <p class="title">CPF</p>
                        <p class="content"><?php echo htmlspecialchars($usuario['Cpf']); ?></p>

                        <div class="standart-btn-position">
                            <button type="button" id="cancel-button"
                                class="standart-form-buttons form-items-gray hover-border">Cancelar</button>
                            <button type="submit" id="save-button"
                                class="standart-form-buttons form-items-orange hover-background">Salvar</button>
                        </div>
                    </form>

                </div>
                <div class="div-estatistica">
                    <h2>ESTATÍSTICAS</h2>
                    <div class="estatistica">
                        <form method="GET" action="perfil.php" class="estatistica-botoes">
                            <div class="selects-container"> <select class="select-game" name="modo_jogo">
                                    <option value="QUALQUER" <?php if ($filtro_modo == 'QUALQUER') echo 'selected'; ?>>MODO DE JOGO</option>
                                    <option value="CLASSICO" <?php if ($filtro_modo == 'CLASSICO') echo 'selected'; ?>>CLÁSSICO</option>
                                    <option value="CONTRA O TEMPO" <?php if ($filtro_modo == 'CONTRA O TEMPO') echo 'selected'; ?>>CONTRA O TEMPO</option>
                                </select>

                                <select class="select-game" name="tabuleiro">
                                    <option value="TODOS" <?php if ($filtro_tabuleiro == 'TODOS') echo 'selected'; ?>>TABULEIRO</option>
                                    <option value="2" <?php if ($filtro_tabuleiro == '2') echo 'selected'; ?>>2X2</option>
                                    <option value="4" <?php if ($filtro_tabuleiro == '4') echo 'selected'; ?>>4X4</option>
                                    <option value="6" <?php if ($filtro_tabuleiro == '6') echo 'selected'; ?>>6X6</option>
                                    <option value="8" <?php if ($filtro_tabuleiro == '8') echo 'selected'; ?>>8X8</option>
                                </select>
                            </div>

                            <button type="submit" class="standart-form-buttons form-items-orange hover-background">
                                Filtrar
                            </button>
                        </form>
                        <div class="estatistica-info">
                            <div class="estatistica-item">
                                <p class="title">PARTIDAS</p>
                                <p class="content"><?php echo $total_partidas; ?></p>
                            </div>
                            <div class="estatistica-item">
                                <p class="title">VITÓRIA</p>
                                <p class="content"><?php echo $total_vitorias; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="partida-buttons buttons-absolute">
        <a href="index.php" title="Sair">
            <span class="material-symbols-outlined">logout</span>
        </a>
        <a href="ranking.php" title="Ranking Global">
            <span class="material-symbols-outlined">social_leaderboard</span>
        </a>
        <a href="config.php" title="Jogar agora!">
            <img src="../img/robito_branco.png" alt="">
        </a>
    </div>
    <script src="../js/perfil.js"></script>
    <?php include("./cookies.php"); ?>
</body>
</html>
<?php
    $stmt_historico->close();
    $conn->close();
?>