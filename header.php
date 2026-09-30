<?php
    session_start();

   
    // Assim os links funcionam de qualquer pasta (ex: paginas/)
    $base = str_replace('\\', '/', substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])));

    // Sem login, volta para a página de login
    if (!isset($_SESSION['id'])) {
        header("Location: $base/login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $base ?>/recursos/style.css">

    <title>Gerenciador de S.A</title>
</head>
<body>
    
        <div class="wrapper">
            <aside class = 'sidebar-esq'>
                <!-- Arrumar isso depois, não sei se professores e alunos vão ter o mesmo sidebar -->
                <?php if(isset($_SESSION['nivel']) && $_SESSION['nivel'] == 'aluno'): ?>
                    <a href="<?= $base ?>/paginas/turmas.php">Turmas</a>
                    <a href="<?= $base ?>/projetos.php">Projetos</a>
                    <a href="<?= $base ?>/feed.php">Feed</a>
                    <a href="<?= $base ?>/conta.php">Conta</a>
                    <a href="<?= $base ?>/acoes/fazer_logout.php">Sair</a>
                <?php endif ?>
            </aside>
            <div class="central">
                
                <header class="header">
                <img src="<?= $base ?>/recursos/sesi-logo.png" height="100%">
                <h1>Gerenciador de S.A</h1>
    