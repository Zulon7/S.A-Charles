<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="recursos/style.css">

    <title>Gerenciador de S.A</title>

    <?php session_start(); ?>
    
    
</head>
<body>
    
        <div class="wrapper">
            <aside class = 'sidebar-esq'>
                <?php if(isset($_SESSION['nivel']) && $_SESSION['nivel'] == 'aluno'): ?>
                
                     <a href="grupos.php">Grupos</a>
                     <a href="projetos.php">Projetos</a>
                     <a href="feed.php">Feed</a>
                     <a href="conta.php">Conta</a>
                <?php endif ?>
                </aside>
            <div class="central">
                
                <header class="header">
                <img src="recursos\sesi-logo.png" height="100%">
                <h1>Gerenciador de S.A</h1>
    