<?php

error_reporting(0);
ini_set('display_errors', 0);

session_start();
header('Content-Type: application/json');
include("db_config.php");


if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['ID'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}


$user_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : $_SESSION['ID'];


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$dados = json_decode(file_get_contents('php://input'), true);

if (!isset($dados['tabuleiro']) || !isset($dados['modalidade']) || 
    !isset($dados['resultado']) || !isset($dados['duracao']) || !isset($dados['jogadas'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}


$tabuleiro = intval($dados['tabuleiro']);
$modalidade = strtoupper($dados['modalidade']);
$resultado = strtoupper($dados['resultado']);
$duracao = $dados['duracao'];
$jogadas = intval($dados['jogadas']);
$tempo_regressivo = isset($dados['tempo_regressivo']) ? $dados['tempo_regressivo'] : null;


if (!in_array($tabuleiro, [2, 4, 6, 8])) {
    echo json_encode(['success' => false, 'message' => 'Tamanho de tabuleiro inválido']);
    exit;
}

if (!in_array($modalidade, ['CLASSICO', 'CONTRA O TEMPO'])) {
    echo json_encode(['success' => false, 'message' => 'Modalidade inválida']);
    exit;
}

if (!in_array($resultado, ['VITÓRIA', 'DERROTA'])) {
    echo json_encode(['success' => false, 'message' => 'Resultado inválido']);
    exit;
}


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco de dados']);
    exit;
}


$sql = "INSERT INTO Partidas (Usuario_ID, Tabuleiro, Modalidade, Resultado, Tempo_regressivo, Duracao_partida, Jogadas) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Erro ao preparar consulta: ' . $conn->error]);
    $conn->close();
    exit;
}

$stmt->bind_param("iissssi", $user_id, $tabuleiro, $modalidade, $resultado, $tempo_regressivo, $duracao, $jogadas);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true, 
        'message' => 'Partida registrada com sucesso!',
        'partida_id' => $stmt->insert_id
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar partida: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>