<?php

    // Verifica se houve POST e se o usuário ou a senha é(são) vazio(s)
    if (!empty($_POST) AND (empty($_POST["usuario"]) OR empty($_POST["senha"]))) {
        header("Location: index.php"); exit;
    }


    $conexao = new mysqli("127.0.0.1", "root", "", "samedia");

    if ($conexao->connect_errno) {
        die("Erro: " . $conexao->connect_error);
    }

    $usuario = $conexao -> real_escape_string($_POST["usuario"]);
    $senha = $conexao -> real_escape_string($_POST["senha"]);

    // Validação do usuário/senha digitados
    $sql = "SELECT `id`, `nome`, `nivel`, `senha` FROM `users` WHERE (`nome` = ".$usuario .")  AND (`ativo` = 1) LIMIT 1";
    $resultado = $conexao -> query($sql);
    $fileira =  $resultado -> fetch_assoc();

    if (password_verify($senha ,$fileira['senha']))  {
        // Mensagem de erro quando os dados são inválidos e/ou o usuário não foi encontrado
        echo "Login inválido!"; exit;
    } else {
        // Salva os dados encontados na variável $fileira
        session_start();
        $_SESSION['id'] = $fileira['id'];
        $_session['nivel'] = $fileira['nivel'];
        $_SESSION['senha'] = $fileira['senha'];
    }

    

    $conexao -> close();

  ?>