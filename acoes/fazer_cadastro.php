<?php
    /*
    $conexao = new mysqli("127.0.0.1", "root", "", "samedia");

    if ($conexao->connect_errno) {
        die("Erro: " . $conexao->connect_error);
    }
    */
    require_once("db_con_init.php");

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    $sql = "SELECT `id`, `nome`, `email` FROM `users` WHERE ((`nome` = ?) OR (`email` = ?))  AND (`ativo` = 1) LIMIT 1";
    $stmt = $conexao -> prepare($sql);// :email <- for later
    $stmt -> execute([$nome, $email]);


    $resultado = $stmt -> fetch(PDO::FETCH_ASSOC);


    if($resultado){

        $fileira =  $resultado[0];

    } else {

        $fileira['email'] = $email;
        $fileira['email'] .= "diff";
        $fileira['nome'] = $nome;
        $fileira['nome'] .= "diff";

    }

    if (($fileira['email'] != $email) and ($fileira['nome'] != $nome) and filter_var($email, FILTER_VALIDATE_EMAIL)){
        $sql = "
        INSERT INTO users (id, nome, email, senha, data_criado)
        VALUES (default, '$nome', '$email', '$senha', default)
        ";
        echo $sql;
        $conexao -> query($sql);
    } else {
        echo "Usuário já existente ou email inválido";
    }

  
?>