<?php if(!isset($_SESSION)) session_start()?>
<aside class = 'sidebar'>
    <div class = 'sidebar-content'>
        <?php if($_SESSION['nivel'] == 'aluno'): ?>
            
            <a href="grupos.php">Grupos</a>
            <a href="projetos.php">Projetos</a>
            <a href="feed.php">Feed</a>
            <a href="conta.php">Conta</a>
        
        <?php endif ?>
    </div>
 </aside>