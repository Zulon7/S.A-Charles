<?php
    if (!isset($_POST)) {
        header('Location:../index.php'); exit;
    }

    require_once("db_con_init.php");
    session_start();


    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    $sql = "SELECT `id`, `nome`, `email` FROM `users` WHERE ((`nome` = ?) OR (`email` = ?))  AND (`ativo` = 1) LIMIT 1";
    $stmt = $conexao -> prepare($sql);
    $stmt -> execute([$nome, $email]);


    $resultado = $stmt -> fetch(PDO::FETCH_ASSOC);


    

    if (empty($resultado) and filter_var($email, FILTER_VALIDATE_EMAIL)){
        $sql = "
        INSERT INTO users (id, nome, email, senha, data_criado)
        VALUES (default, ?, ?, ?, default)
        ";
        $stmt = $conexao -> prepare($sql);
        $stmt -> execute([$nome, $email, $senha]);
        header("Location:../index.php"); exit;
    } else {
        $_SESSION['erro'] = "Usuário já existente ou email inválido";
        header("Location:../cadastro.php"); exit;
    }

  
?>