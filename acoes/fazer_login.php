<?php

    // Verifica se houve POST e se o usuário ou a senha é(são) vazio(s)
    if (!isset($_POST)) {
        header('Location:../index.php'); exit;
    }

    require_once("db_con_init.php");
    session_start();

    $nome = $_POST["nome"];
    $senha = $_POST["senha"];

    // Validação do usuário/senha digitados
    $sql = "SELECT `id`, `nome`, `nivel`, `senha` FROM `users` WHERE ((`nome` = ?) OR (`email`) = ?)  AND (`ativo` = 1) LIMIT 1";
    $stmt = $conexao -> prepare($sql);

    $stmt -> execute([$nome, $nome]);

    $fileira = $stmt -> fetch(PDO::FETCH_ASSOC);

    if (empty($fileira) or !password_verify($senha ,$fileira['senha']))  {
        // Mensagem de erro quando os dados são inválidos e/ou o usuário não foi encontrado
   
        $_SESSION['erro'] = 'Login Inválido!';
        header("Location:../login.php"); exit;
    } else {
        // Salva os dados encontados na variável $fileira
        session_start();
        $_SESSION['id'] = $fileira['id'];
        $_SESSION['nivel'] = $fileira['nivel'];
        echo "deu certo";
    }


  ?>