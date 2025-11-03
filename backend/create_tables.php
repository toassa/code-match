<?php
    include("db_config.php");

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) { 
        die("". $conn->connect_error);
    }


    $sql = "CREATE DATABASE IF NOT EXISTS code_match_bd";
    if ($conn->query($sql) === TRUE) {
        echo "Banco de dados criado ou já existente.<br>";
    } else {
        die("Erro ao criar banco: " . $conn->error);
    }

    $conn->select_db($dbname);
    $sql = "CREATE TABLE IF NOT EXISTS Global_Ranking (
        Username CHAR(20) PRIMARY KEY,
        Tabuleiro CHAR(3)
    )";

    // VOU CONTINUAR MAIS TARDE...

    // NÃO TEM NADA FUNCIONANDO
?>