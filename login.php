
<?php include('header.php'); ?>
<body>
    <div class = "login-conteudo">
        <form action="acoes/fazer_login.php" method = "POST" class = "login-form">
            <div class = 'field'>
                <label for="nome">Nome</label>
                <input type="text" id = "nome" name = "nome">
            </div>
            
            <div class = 'field'>
                <label for="nome">Senha</label>
                <input type="text" id = "senha" name = "senha">
            </div>

            <input type="submit" name="Fazer login">
        </form>
        <a href="cadastro.php">Não tem login? Faça cadastro</a>
    </div>
</body>
</html>