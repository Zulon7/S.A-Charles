
<?php include('header.php'); ?>

<div class = "login-wrapper">
    <form action="acoes/fazer_cadastro.php" method = "POST" class = "login-form">
        <div class = 'field'>
            <label for="nome">Email</label>
            <input type="text" id = "email" name = "email">
        </div>

        <div class = 'field'>
            <label for="nome">Nome</label>
            <input type="text" id = "nome" name = "nome">
        </div>
        
        <div class = 'field'>
            <label for="nome">Senha</label>
            <input type="text" id = "senha" name = "senha">
        </div>

        <input class = "submit" type="submit" value="Cadastrar">
    </form>
    <a href="login.php">Já tem uma conta? Faça login</a>
</div>

<?php include("footer.php");