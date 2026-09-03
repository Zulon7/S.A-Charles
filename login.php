<?php

// Verifica se houve POST e se o usuário ou a senha é(são) vazio(s)
if (isset($_POST) and !empty($_POST)) {
    
    require("acoes/db_con_init.php");

    $nome = $_POST["nome"];
    $senha = $_POST["senha"];

    // Validação do usuário/senha digitados
    $sql = "SELECT `id`, `nome`, `nivel`, `senha` FROM `users` WHERE (`nome` = ?)  AND (`ativo` = 1) LIMIT 1";
    $stmt = $conexao -> prepare($sql);

    $stmt -> execute([$nome]);

    $fileira = $stmt -> fetch(PDO::FETCH_ASSOC);

    if (!empty($fileira) and password_verify($senha ,$fileira['senha']))  {

        session_start();
        $_SESSION['id'] = $fileira['id'];
        $_SESSION['nivel'] = $fileira['nivel'];

    } 

}
?>

<?php include('header.php'); ?>


<div class = "login-wrapper">
    <?php if (isset($_POST) and (empty($fileira) or !password_verify($senha ,$fileira['senha']))): ?>
        <div class = "login-aviso" >
            <span>Nome de usuário ou senha incorretos.</span>
            <button>
                <i class="bi bi-x"></i>
            </button>
        </div>
    <?php endif;?>
    <form action="" method = "POST" class = "login-form">
        <div class = 'field'>
            <label for="nome">Email ou usuário</label>
            <input type="text" id = "nome" name = "nome">
        </div>
        
        <div class = 'field'>
            <label for="nome">Senha</label>
            <input type="text" id = "senha" name = "senha">
        </div>

        <input class = "submit" type="submit" value="Fazer login">
    </form>
    <a href="cadastro.php" style = "text-decoration: none">Não tem login? Faça cadastro</a>
</div>

<?php include("footer.php");