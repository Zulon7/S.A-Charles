<?php
    require("db_con_init.php");

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], 'PASSWORD_DEFAULT');

    $sql = "
    INSERT INTO users (id, nome, email, senha, data_criado)
    VALUES (default, ?, ?, ?, default)
    ";

    $stm = $conexao -> prepare($sql);
    $stm -> execute([$nome, $email, $senha]);
    
?>