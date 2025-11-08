<?php
session_start();
include("db_config.php");

header('Content-Type: application/json');

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Erro de conexão: " . $conn->connect_error]);
    exit;
}

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["status" => "error", "message" => "Usuário não autenticado."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $usuario_id = $_SESSION['usuario_id'];

    $nome = $_POST['nome_completo'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (!empty($senha)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        
        $sql = "UPDATE Usuarios SET Nome_Completo = ?, Email = ?, Telefone = ?, Senha = ? WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nome, $email, $telefone, $senha_hash, $usuario_id);
        
    } else {
        $sql = "UPDATE Usuarios SET Nome_Completo = ?, Email = ?, Telefone = ? WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nome, $email, $telefone, $usuario_id);
    }

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Perfil atualizado com sucesso!",
            "newData" => [
                "nome" => $nome,
                "email" => $email,
                "telefone" => $telefone
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Erro ao atualizar o perfil: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();

} else {
    echo json_encode(["status" => "error", "message" => "Método de requisição inválido."]);
}
?>