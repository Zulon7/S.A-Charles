<?php include(__DIR__ . '/../header.php'); ?>

<?php
    require_once(__DIR__ . '/../acoes/db_con_init.php');

    // Busca os projetos de todas as turmas que o usuário participa ou administra
    // Projetos sem data de entrega ficam no final da lista
    $sql = "SELECT p.id, p.nome, p.descricao, p.data_entrega, t.id AS id_turma, t.nome AS nome_turma
            FROM projetos p
            INNER JOIN turmas t ON t.id = p.id_turma
            WHERE t.id IN (SELECT id_turma FROM user_participa WHERE id_user = ?)
               OR t.id IN (SELECT id_turma FROM user_modera   WHERE id_user = ?)
            ORDER BY p.data_entrega IS NULL, p.data_entrega";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$_SESSION['id'], $_SESSION['id']]);
    $projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="titulo">Seus projetos</h2>

<?php if (empty($projetos)): ?>
    <p class="turmas-vazio">Nenhum projeto nas suas turmas ainda.</p>
<?php else: ?>
    <div class="turmas-lista">
        <?php foreach ($projetos as $projeto): ?>
            <?php include(__DIR__ . '/../projeto_card.php'); ?>
        <?php endforeach ?>
    </div>
<?php endif ?>

<?php include(__DIR__ . '/../footer.php'); ?>
