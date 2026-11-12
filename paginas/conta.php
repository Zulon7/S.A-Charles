<?php include(__DIR__ . '/../header.php'); ?>

<?php
    require_once(__DIR__ . '/../acoes/db_con_init.php');

    // Dados do usuário logado (nunca buscar a senha, ela não é mostrada)
    $stmt = $conexao->prepare("SELECT nome, email, nivel, data_criado FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Professor: turmas que administra. Aluno: turmas que participa.
    $e_professor = ($usuario['nivel'] == 'professor');
    $tabela = $e_professor ? 'user_modera' : 'user_participa';

    $sql = "SELECT t.id, t.nome
            FROM turmas t
            INNER JOIN $tabela u ON u.id_turma = t.id
            WHERE u.id_user = ?
            ORDER BY t.nome";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$_SESSION['id']]);
    $turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="titulo">Sua conta</h2>

<section class="turma-secao">
    <h3>Informações</h3>
    <ul class="membros-lista conta-info">
        <li>
            <i class="bi bi-person"></i>
            <span>Usuário</span>
            <strong><?= htmlspecialchars($usuario['nome']) ?></strong>
        </li>
        <li>
            <i class="bi bi-envelope"></i>
            <span>Email</span>
            <strong><?= htmlspecialchars($usuario['email']) ?></strong>
        </li>
        <li>
            <i class="bi <?= $e_professor ? 'bi-person-badge' : 'bi-mortarboard' ?>"></i>
            <span>Tipo de conta</span>
            <strong><?= $e_professor ? 'Professor' : 'Aluno' ?></strong>
        </li>
        <li>
            <i class="bi bi-calendar"></i>
            <span>Conta criada em</span>
            <strong><?= date('d/m/Y', strtotime($usuario['data_criado'])) ?></strong>
        </li>
    </ul>
</section>

<section class="turma-secao">
    <h3><?= $e_professor ? 'Turmas que você administra' : 'Suas turmas' ?> (<?= count($turmas) ?>)</h3>
    <?php if (empty($turmas)): ?>
        <p class="turmas-vazio">Você ainda não está em nenhuma turma.</p>
    <?php else: ?>
        <ul class="membros-lista">
            <?php foreach ($turmas as $turma): ?>
                <li>
                    <i class="bi bi-people"></i>
                    <a href="<?= $base ?>/paginas/turma.php?id=<?= $turma['id'] ?>"><?= htmlspecialchars($turma['nome']) ?></a>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
</section>

<?php include(__DIR__ . '/../footer.php'); ?>
