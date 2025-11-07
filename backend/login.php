<?php
session_start();
include("db_config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST["usuario"]);
    $senha = $_POST["senha"];
    $lembrar = isset($_POST["lembrar"]);

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT ID, Usuario, Senha FROM Usuarios WHERE Usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($senha, $row["Senha"])) {
            $_SESSION["usuario"] = $row["Usuario"];

            if ($lembrar) {
                setcookie("usuario_salvo", $usuario, time() + (7 * 24 * 60 * 60), "/");
                setcookie("senha_salva", $senha, time() + (7 * 24 * 60 * 60), "/");
            } else {
                setcookie("usuario_salvo", "", time() - 3600, "/");
                setcookie("senha_salva", "", time() - 3600, "/");
            }
            header("Location: ../public/config.php");
            exit;
        } else {
            echo "<script>
                    alert('Senha incorreta!');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('Usuário não encontrado!');
                window.history.back();
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>