<?php include(__DIR__ . '/../header.php'); ?>

<?php
    require_once(__DIR__ . '/../acoes/db_con_init.php');
    require_once(__DIR__ . '/../acoes/permissoes.php');

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

    // Propostas da turma (sem data de entrega ficam no final)
    // total_projetos e id_meu_projeto são usados no card (ver proposta_card.php)
    $sql = "SELECT pr.id, pr.nome, pr.descricao, pr.data_entrega,
                   (SELECT COUNT(*) FROM projetos pj WHERE pj.id_proposta = pr.id) AS total_projetos,
                   (SELECT pj.id FROM projetos pj WHERE pj.id_proposta = pr.id AND pj.id_aluno = ?) AS id_meu_projeto
            FROM propostas pr
            WHERE pr.id_turma = ?
            ORDER BY pr.data_entrega IS NULL, pr.data_entrega";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$_SESSION['id'], $turma['id']]);
    $propostas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Só professores da turma podem criar propostas
    $e_professor = e_professor_da_turma($conexao, $_SESSION['id'], $turma['id']);
?>


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
    <h3>Propostas (<?= count($propostas) ?>)</h3>
    <?php if (empty($propostas)): ?>
        <p class="turmas-vazio">Nenhuma proposta nesta turma ainda.</p>
    <?php else: ?>
        <div class="turmas-lista">
            <?php foreach ($propostas as $proposta): ?>
                <?php include(__DIR__ . '/../proposta_card.php'); ?>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</section>

<?php if ($e_professor): ?>
    <section class="turma-secao">
        <h3>Nova proposta</h3>
        <?php include(__DIR__ . '/../avisos.php'); ?>
        <form class="form-simples" action="<?= $base ?>/acoes/criar_proposta.php?turma=<?= $turma['id'] ?>" method="POST">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" maxlength="150" required>

            <label for="descricao">Descrição (opcional)</label>
            <textarea id="descricao" name="descricao" rows="4"></textarea>

            <label for="data_entrega">Data de entrega (opcional)</label>
            <input type="date" id="data_entrega" name="data_entrega">

            <button type="submit"><i class="bi bi-plus-lg"></i> Criar proposta</button>
            <small>Depois de criar, você pode anexar arquivos na página da proposta.</small>
        </form>
    </section>
<?php endif ?>

<?php include(__DIR__ . '/../footer.php'); ?>
