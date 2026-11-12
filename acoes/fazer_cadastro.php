<?php
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        header('Location:../cadastro.php'); exit;
    }

    require_once("db_con_init.php");
    session_start();


    $nome = trim($_POST["nome"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $senha_digitada = $_POST["senha"] ?? '';

    // Nome e senha não podem ficar vazios (o nome cabe em 150 caracteres no banco)
    if ($nome == '' || $senha_digitada == '' || mb_strlen($nome) > 150 || mb_strlen($email) > 150) {
        $_SESSION['erro'] = "Preencha nome, email e senha";
        header("Location:../cadastro.php"); exit;
    }

    $senha = password_hash($senha_digitada, PASSWORD_DEFAULT);

    // Procura também entre os inativos: nome e email são UNIQUE no banco,
    // então repetir o de um usuário inativo daria erro no INSERT
    $sql = "SELECT `id`, `nome`, `email` FROM `users` WHERE (`nome` = ?) OR (`email` = ?) LIMIT 1";
    $stmt = $conexao -> prepare($sql);
    $stmt -> execute([$nome, $email]);


    $resultado = $stmt -> fetch(PDO::FETCH_ASSOC);


    

    if (empty($resultado) and filter_var($email, FILTER_VALIDATE_EMAIL)){
        $sql = "
        INSERT INTO users (id, nome, email, senha, data_criado, nivel)
        VALUES (default, ?, ?, ?, default, 'aluno')
        ";
        $stmt = $conexao -> prepare($sql);
        $stmt -> execute([$nome, $email, $senha]);
        header("Location:../index.php"); exit;
    } else {
        $_SESSION['erro'] = "Usuário já existente ou email inválido";
        header("Location:../cadastro.php"); exit;
    }

  
?>