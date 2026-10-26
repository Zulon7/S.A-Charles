<?php
    // Mostra as mensagens de erro/sucesso salvas na sessão pelas ações (acoes/*.php)
    // e apaga elas, para não aparecerem de novo
?>
<?php if (isset($_SESSION['erro'])): ?>
    <div class="login-aviso">
        <span><?= $_SESSION['erro'] ?></span>
        <button onclick="close_element('login-aviso');"><i class="bi bi-x"></i></button>
    </div>
    <?php unset($_SESSION['erro']) ?>
<?php endif ?>

<?php if (isset($_SESSION['sucesso'])): ?>
    <div class="login-aviso aviso-sucesso">
        <span><?= $_SESSION['sucesso'] ?></span>
        <button onclick="close_element('aviso-sucesso');"><i class="bi bi-x"></i></button>
    </div>
    <?php unset($_SESSION['sucesso']) ?>
<?php endif ?>
