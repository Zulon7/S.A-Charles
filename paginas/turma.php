<?php include(__DIR__ . '/../header.php'); ?>

<?php
    require_once(__DIR__ . '/../acoes/db_con_init.php');

    // Id da turma vem da URL: turma.php?id=1
    $id_turma = $_GET['id'] ?? null;

    // Busca a turma, mas só se o usuário participa ou administra ela
    $sql = "SELECT t.id, t.nome, t.data_criado
            FROM turmas t
            WHERE t.id = ?
              AND (t.id IN (SELECT id_turma FROM user_participa WHERE id_user = ?)
                OR t.id IN (SELECT id_turma FROM user_modera   WHERE id_user = ?))";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$id_turma, $_SESSION['id'], $_SESSION['id']]);
    $turma = $stmt->fetch(PDO::FETCH_ASSOC);

    // Turma não existe ou o usuário não faz parte dela
    if (!$turma) {
        echo '<h2 class="titulo">Turma não encontrada</h2>';
        echo '<a class="voltar" href="' . $base . '/paginas/turmas.php"><i class="bi bi-arrow-left"></i> Voltar para turmas</a>';
        include(__DIR__ . '/../footer.php');
        exit;
    }

    // Professores da turma
    $sql = "SELECT u.nome, u.email
            FROM users u
            INNER JOIN user_modera m ON m.id_user = u.id
            WHERE m.id_turma = ?
            ORDER BY u.nome";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$turma['id']]);
    $professores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Alunos da turma
    $sql = "SELECT u.nome, u.email
            FROM users u
            INNER JOIN user_participa p ON p.id_user = u.id
            WHERE p.id_turma = ?
            ORDER BY u.nome";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$turma['id']]);
    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Projetos da turma (sem data de entrega ficam no final)
    $sql = "SELECT id, nome, descricao, data_entrega
            FROM projetos
            WHERE id_turma = ?
            ORDER BY data_entrega IS NULL, data_entrega";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$turma['id']]);
    $projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<a class="voltar" href="<?= $base ?>/paginas/turmas.php"><i class="bi bi-arrow-left"></i> Voltar para turmas</a>

<h2 class="titulo"><?= htmlspecialchars($turma['nome']) ?></h2>
<p class="turma-info">Criada em <?= date('d/m/Y', strtotime($turma['data_criado'])) ?></p>

<section class="turma-secao">
    <h3>Membros</h3>

    <h4>Professores (<?= count($professores) ?>)</h4>
    <?php if (empty($professores)): ?>
        <p class="turmas-vazio">Nenhum professor nesta turma.</p>
    <?php else: ?>
        <ul class="membros-lista">
            <?php foreach ($professores as $membro): ?>
                <li>
                    <i class="bi bi-person-badge"></i>
                    <span><?= htmlspecialchars($membro['nome']) ?></span>
                    <small><?= htmlspecialchars($membro['email']) ?></small>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>

    <h4>Alunos (<?= count($alunos) ?>)</h4>
    <?php if (empty($alunos)): ?>
        <p class="turmas-vazio">Nenhum aluno nesta turma ainda.</p>
    <?php else: ?>
        <ul class="membros-lista">
            <?php foreach ($alunos as $membro): ?>
                <li>
                    <i class="bi bi-person"></i>
                    <span><?= htmlspecialchars($membro['nome']) ?></span>
                    <small><?= htmlspecialchars($membro['email']) ?></small>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
</section>

<section class="turma-secao">
    <h3>Projetos (<?= count($projetos) ?>)</h3>
    <?php if (empty($projetos)): ?>
        <p class="turmas-vazio">Nenhum projeto nesta turma ainda.</p>
    <?php else: ?>
        <div class="turmas-lista">
            <?php foreach ($projetos as $projeto): ?>
                <?php include(__DIR__ . '/../projeto_card.php'); ?>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</section>

<?php include(__DIR__ . '/../footer.php'); ?>
