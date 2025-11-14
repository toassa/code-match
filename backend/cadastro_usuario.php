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

    $stmt = $conn->prepare("SELECT ID FROM Usuarios WHERE Cpf = ?");
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>
                alert('Este CPF já está cadastrado.');
                window.history.back();
              </script>";
        exit;
    }

    $stmt->close();

    $stmt = $conn->prepare("SELECT ID FROM Usuarios WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>
                alert('Este e-mail já está cadastrado.');
                window.history.back();
              </script>";
        exit;
    }

    $stmt->close();

    $stmt = $conn->prepare("SELECT ID FROM Usuarios WHERE Usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>
                alert('Este nome de usuário já está em uso.');
                window.history.back();
              </script>";
        exit;
    }

    $stmt->close();

    $stmt = $conn->prepare("
        INSERT INTO Usuarios (Nome_Completo, Data_nasc, Cpf, Telefone, Email, Usuario, Senha)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

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