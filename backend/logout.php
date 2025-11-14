<?php
session_start();

setcookie("logout_msg", "Logout realizado com sucesso!", time() + 3, "/");

session_destroy();

header("Location: ../public/index.php");
exit;
?>