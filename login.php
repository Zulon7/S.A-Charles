
<?php include('header.php'); ?>


<div class = "login-wrapper">
    <form action="acoes/fazer_login.php" method = "POST" class = "login-form">
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
    <a href="cadastro.php">Não tem login? Faça cadastro</a>
</div>

<?php include("footer.php");