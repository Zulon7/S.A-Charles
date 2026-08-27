<?php
    $conexao = new mysqli("127.0.0.1", "root", "", "samedia");

    if ($conexao->connect_errno) {
        die("Erro: " . $conexao->connect_error);
    }

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    $sql = "SELECT `id`, `nome`, `email` FROM `users` WHERE (`nome` = ".$nome .") OR (`email` = ".$email .")  AND (`ativo` = 1) LIMIT 1";
    $resultado = $conexao -> query($sql);
    if(gettype($resultado) != "boolean"){
        $fileira =  $resultado -> fetch_assoc();
    } else {
        $fileira['email'] = $email;
        $fileira['email'] .= "diff";
        $fileira['nome'] = $nome;
        $fileira['nome'] .= "diff";
    }

    if (($fileira['email'] != $email) and ($fileira['nome'] != $nome)){
        $sql = "
        INSERT INTO users (id, nome, email, senha, data_criado)
        VALUES (default, '$nome', '$email', '$senha', default)
        ";
        echo $sql;
        $conexao -> query($sql);
    } else {
        echo "Usuário já existente";
    }

    $conexao->close();
?>