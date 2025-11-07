<?php
    session_start();
    include("db_config.php");

    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $usuario = $POST["usuario"];
        $senha = $POST["senha"];

        $conn =  new mysqli($servername, $username, $password, $dbname);

        if($conn->conect_error){
            die("Erro de conexão: ".$conn->connect_error);
        }

        $stmt = $conn->prepare("
            SELECT Usuario, Senha
            FROM Usuarios
            WHERE Usuario = ?
        ");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows === 1){
            $row = $result->fetch_assoc();

            if(password_verify($senha, $row["Senha"])){
                $_SESSION["usuario"] = $row["Usuario"];

                header("Location: config.php");
                exit;
            }else{
                echo "<script>alert('Senha incorreta!'); window.history.back();</script>";
            }
        }else{
            echo "<script>alert('Usuário não encontrado'); window.history.back();</script>";
        }

        $stmt->close();
        $conn->close();
    }
?>