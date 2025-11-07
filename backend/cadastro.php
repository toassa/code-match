<?php
    include("db_config.php");

    if($SERVER["REQUEST_METHOD"] === "POST"){
        $nome = $_POST["nome"];
        $data_nasc = $_POST["data_nasc"];
        $cpf = $_POST["cpf"];
        $telefone = $_POST["telefone"];
        $email = $_POST["email"];
        $usuario = $_POST["usuario"];
        $senha = $_POST["senha"];

        $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

        $conn = new mysqli($servername, $username, $password, $dbname);

        if($conn->connect_error){
            die("Erro de conexão: ".$conn->connect_error);
        }

        $stmt = $conn->prepare("
            INSERT INTO Usuarios(Usuario, Nome_Completo, Data_nasc, Cpf, Telefone, Email, Senha)
            VALUES (?, ?, ?, ?, ?, ?, ?);
        ");
        $stmt->bind_param("ssssssss", $usuario, $nome, $data_nasc, $cpf, $telefone, $email, $senhaCriptografada);

        
        if ($stmt->execute()) {
            echo "<script>alert('Cadastro realizado com sucesso!'); window.location='index.php';</script>";
            header("Location: login.php");
        } else {
            echo "<script>alert('Erro ao cadastrar: " . $stmt->error . "'); window.history.back();</script>";
        }

        $stmt->close();
        $conn->close();
    }
?>