<?php
session_start();
include("../backend/db_config.php");


$usuario_logado_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : (isset($_SESSION['ID']) ? $_SESSION['ID'] : null);

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Recebe e sanitiza os filtros
$filtro_tabuleiro = isset($_GET['tabuleiro']) ? intval($_GET['tabuleiro']) : null;
$filtro_modalidade = isset($_GET['modalidade']) ? $conn->real_escape_string($_GET['modalidade']) : null;

// Validação dos filtros
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

// Adiciona filtros quando selecionados
if ($filtro_tabuleiro) {
    $sql .= " AND p.Tabuleiro = " . $filtro_tabuleiro;
}

if ($filtro_modalidade) {
    $filtro_modalidade_upper = strtoupper($filtro_modalidade);
    $sql .= " AND p.Modalidade = '" . $filtro_modalidade_upper . "'";
}

// Ordenação conforme especificação
$sql .= " ORDER BY p.Tabuleiro DESC, p.Jogadas ASC, p.Duracao_partida ASC";


$sql .= " LIMIT 10";

$result = $conn->query($sql);


function formatarTempo($tempo) {
    if (empty($tempo)) return '--';
    
    // Remove horas se for 00
    if (substr($tempo, 0, 3) === '00:') {
        return substr($tempo, 3);
    }
    return $tempo;
}


function formatarData($data) {
    return date('d/m/Y H:i', strtotime($data));
}


function abreviarModalidade($modalidade) {
    return $modalidade === 'CONTRA O TEMPO' ? 'C. TEMPO' : 'CLÁSSICO';
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
        <h1>🏆 RANKING GLOBAL</h1>
        <p style="color: var(--green-blue); font-size: 16px; margin-bottom: 20px; font-weight: 500;">
            Top 10 melhores desempenhos • Maiores tabuleiros e menos jogadas
        </p>
        
        <div class="div-pagina">
         
            <div class="div-filtro-box">
                <form method="GET" action="ranking.php" class="div-filtro">
                    <h2>FILTRAR</h2>
                    
                    <p>TAMANHO DO TABULEIRO</p>
                    <select class="select-game" name="tabuleiro" aria-label="Filtrar por tamanho do tabuleiro">
                        <option value="">TODOS OS TAMANHOS</option>
                        <option value="2" <?= $filtro_tabuleiro == 2 ? 'selected' : '' ?>>2×2 </option>
                        <option value="4" <?= $filtro_tabuleiro == 4 ? 'selected' : '' ?>>4×4 </option>
                        <option value="6" <?= $filtro_tabuleiro == 6 ? 'selected' : '' ?>>6×6 </option>
                        <option value="8" <?= $filtro_tabuleiro == 8 ? 'selected' : '' ?>>8×8</option>
                    </select>
                    
                    <p>MODO DE JOGO</p> 
                    <select class="select-game" name="modalidade" aria-label="Filtrar por modo de jogo">
                        <option value="">TODOS OS MODOS</option>
                        <option value="CLASSICO" <?= $filtro_modalidade == 'CLASSICO' ? 'selected' : '' ?>>🎯 CLÁSSICO</option>
                        <option value="CONTRA O TEMPO" <?= $filtro_modalidade == 'CONTRA O TEMPO' ? 'selected' : '' ?>>⏱️ CONTRA O TEMPO</option>
                    </select>
                    
                    <button type="submit" class="standart-form-buttons form-items-orange hover-background" 
                            style=" margin: 20px auto 0 auto; ; width: 100%; padding: 12px; border-radius: 12px; cursor: pointer; font-weight: 600; font-size: 16px;">
                        APLICAR FILTROS
                    </button>
                    
                    <?php if ($filtro_tabuleiro || $filtro_modalidade): ?>
                    <a href="ranking.php" class="standart-form-buttons form-items-gray hover-background" 
                       style="margin-top: 10px; width: 100%; display: block; text-align: center; text-decoration: none; padding: 12px; border-radius: 12px; font-weight: 600; box-sizing: border-box;">
                        LIMPAR FILTROS
                    </a>
                    <?php endif; ?>
                    
           
                
                </form>
            </div>
            
            
            <div class="div-ranking">
                <table class="table-ranking">
                    <thead>
                        <tr>
                            <th class="tr-border-left" style="width: 60px;">#</th>
                            <th style="width: 150px;">JOGADOR</th>
                            <th style="width: 100px;">TABULEIRO</th>
                            <th style="width: 140px;">MODO</th>
                            <th style="width: 90px;">JOGADAS</th>
                            <th style="width: 90px;">TEMPO</th>
                            <th class="tr-border-right" style="width: 130px;">DATA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            $posicao = 1;
                            while ($row = $result->fetch_assoc()) {
                                // Formata os dados
                                $tempo = formatarTempo($row['Duracao_partida']);
                                $data = formatarData($row['Data_partida']);
                                $modalidade_completa = $row['Modalidade'];
                                $modalidade_curta = abreviarModalidade($row['Modalidade']);
                                
                               
                                $classe_posicao = '';
                                $icone_posicao = $posicao;
                                
                                if ($posicao == 1) {
                                    $classe_posicao = 'posicao-primeiro';
                                  
                                } else if ($posicao == 2) {
                                    $classe_posicao = 'posicao-segundo';
                                    
                                } else if ($posicao == 3) {
                                    $classe_posicao = 'posicao-terceiro';
                                    
                                }
                                
                                // Destaca o usuário logado
                                $classe_usuario = '';
                                if ($usuario_logado_id && $row['Usuario_ID'] == $usuario_logado_id) {
                                    $classe_usuario = 'usuario-logado';
                                }
                                
                                echo "<tr class='{$classe_posicao} {$classe_usuario}' data-posicao='{$posicao}'>";
                                echo "<td class='tr-border-left'><strong>{$icone_posicao}</strong></td>";
                                echo "<td class='col-username' title='" . htmlspecialchars($row['Usuario']) . "'>" 
                                     . htmlspecialchars($row['Usuario']) 
                                     . ($classe_usuario ? " <span style='color: var(--green-blue);'>★</span>" : "")
                                     . "</td>";
                                echo "<td><strong>{$row['Tabuleiro']}×{$row['Tabuleiro']}</strong></td>";
                                echo "<td class='col-modalidade'><span class='modalidade-completa'>{$modalidade_completa}</span><span class='modalidade-curta'>{$modalidade_curta}</span></td>";
                                echo "<td><strong>{$row['Jogadas']}</strong></td>";
                                echo "<td>{$tempo}</td>";
                                echo "<td class='tr-border-right col-data'><small>{$data}</small></td>";
                                echo "</tr>";
                                
                                $posicao++;
                            }
                            
                            // Preenche linhas vazias se houver menos de 10 resultados
                            while ($posicao <= 10) {
                                echo "<tr class='linha-vazia'>";
                                echo "<td class='tr-border-left'>{$posicao}</td>";
                                echo "<td colspan='6' class='tr-border-right' style='color: #999; font-style: italic;'>Nenhuma partida registrada</td>";
                                echo "</tr>";
                                $posicao++;
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align: center; padding: 60px 20px; color: #666;'>
                                    <span style='font-size: 48px; display: block; margin-bottom: 15px;'>🔍</span>
                                    <strong style='font-size: 18px; display: block; margin-bottom: 8px;'>Nenhuma partida encontrada</strong>
                                    <span style='font-size: 14px;'>Tente ajustar os filtros ou seja o primeiro a conquistar este ranking!</span>
                                  </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                
                <?php if ($result && $result->num_rows > 0): ?>
                <div style="margin-top: 15px; text-align: center; color: #666; font-size: 12px;">
                    <?php 
                    $total_partidas = $result->num_rows;
                    echo "Exibindo {$total_partidas} " . ($total_partidas == 1 ? "partida" : "partidas");
                    if ($filtro_tabuleiro || $filtro_modalidade) {
                        echo " (filtrado)";
                    }
                    ?>
                </div>
                <?php endif; ?>
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