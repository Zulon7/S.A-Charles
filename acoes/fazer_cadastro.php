<?php
    $conexao = new mysqli("127.0.0.1", "root", "", "samedia");

    if ($conexao->connect_errno) {
        die("Erro: " . $conexao->connect_error);
    }

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"]);

    $sql = "
    INSERT INTO users (id, nome, email, senha, data_criado)
    VALUES (default, '$nome', '$email', '$senha', default)
    ";

    $conexao->close();
?>