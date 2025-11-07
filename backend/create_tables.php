<?php
include("db_config.php");

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Banco de dados criado ou já existente.<br>";
} else {
    die("Erro ao criar banco: " . $conn->error);
}

$conn->select_db($dbname);

$sqlUsuarios = "CREATE TABLE IF NOT EXISTS Usuarios (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Usuario VARCHAR(20) UNIQUE NOT NULL,
    Nome_Completo VARCHAR(100) NOT NULL,
    Data_nasc DATE NOT NULL,
    Cpf CHAR(11) UNIQUE NOT NULL,
    Telefone VARCHAR(15),
    Email VARCHAR(100) NOT NULL UNIQUE,
    Senha VARCHAR(255) NOT NULL,
    remember_token VARCHAR(255),
    token_expira_em DATETIME;
) ENGINE=InnoDB";

if ($conn->query($sqlUsuarios) === TRUE) {
    echo "Tabela 'Usuarios' criada com sucesso.<br>";
} else {
    echo "Erro ao criar tabela 'Usuarios': " . $conn->error . "<br>";
}

$sqlPartida = "CREATE TABLE IF NOT EXISTS Partidas (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Usuario_ID INT NOT NULL,
    Tabuleiro INT NOT NULL, 
    Modalidade CHAR(14) NOT NULL,
    Tempo_regressivo TIME,
    Duracao_partida TIME NOT NULL,
    Jogadas INT NOT NULL,
    Data_partida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Usuario_ID) REFERENCES Usuarios(ID)
) ENGINE=InnoDB";


if ($conn->query($sqlPartida) === TRUE) {
    echo "Tabela 'Partidas' criada com sucesso.<br>";
} else {
    echo "Erro ao criar tabela 'Partidas': " . $conn->error . "<br>";
}

$conn->close();
?>
