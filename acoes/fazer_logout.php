<?php

    session_start();

    // Apaga os dados da sessão e encerra ela
    $_SESSION = [];
    session_destroy();

    header("Location:../login.php"); exit;

?>
