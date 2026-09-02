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
        
    } else {
        echo "Usuário já existente ou email inválido";
    }

  
?>