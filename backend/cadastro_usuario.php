<?php
include("db_config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome_completo"];
    $data_nasc = $_POST["data_nasc"];
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];

    $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("
        INSERT INTO Usuarios (Nome_Completo, Data_nasc, Cpf, Telefone, Email, Usuario, Senha)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssssss", $nome, $dataNascimento, $cpf, $telefone, $email, $usuario, $senhaHash);

    if ($stmt === false) {
        die("Erro na preparação: " . $conn->error);
    }

    $stmt->bind_param("sssssss", $nome, $data_nasc, $cpf, $telefone, $email, $usuario, $senhaCriptografada);

    if ($stmt->execute()) {
        echo "<script>
            alert('Cadastro realizado com sucesso!');
            window.location.href = '../public/index.php';
        </script>";
    } else {
        echo "<script>
            alert('Erro ao cadastrar. Tente novamente.');
            window.history.back();
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
