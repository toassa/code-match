<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db_name = "code_match_bd";
    
    
    $conn = new mysqli($host, $user, $pass, $db_name);

    if ($conn->connect_error) { 
        die("". $conn->connect_error);
    }
?>