
<?php include('header_login.php'); ?>

<?php include('content.php'); ?>

<div class = "login-wrapper">
    <?php if (isset($_SESSION["erro"])): ?>
        <div class = "login-aviso" >
            <span><?php echo $_SESSION["erro"]?></span>
            <button onclick = "close_element('login-aviso');">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <?php unset($_SESSION['erro'])?>
    <?php endif ?>
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
    <a href="cadastro.php" style = "text-decoration: none">Não tem login? Faça cadastro</a>
</div>

<?php include("footer_login.php");