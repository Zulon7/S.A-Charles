<?php
    /**
     * Card de um projeto. Usado em paginas/projetos.php
     * O card inteiro é um link para a página do projeto.
     * @var array $projeto precisa ter: id, nome, descricao, data_criado,
     *                     nome_proposta, nome_turma, nome_aluno, total_arquivos
     * @var string $base vem do header.php
     */
?>
<a class="turma-card" href="<?= $base ?>/paginas/projeto.php?id=<?= $projeto['id'] ?>">
    <h3><?= htmlspecialchars($projeto['nome']) ?></h3>

    <p><i class="bi bi-journal-text"></i> <?= htmlspecialchars($projeto['nome_proposta']) ?></p>
    <p><i class="bi bi-people"></i> <?= htmlspecialchars($projeto['nome_turma']) ?></p>

    <!-- O professor precisa saber de qual aluno é o projeto -->
    <?php if ($_SESSION['nivel'] == 'professor'): ?>
        <p><i class="bi bi-person"></i> <?= htmlspecialchars($projeto['nome_aluno']) ?></p>
    <?php endif ?>

    <p><i class="bi bi-paperclip"></i> <?= $projeto['total_arquivos'] ?> arquivo(s)</p>
    <p><i class="bi bi-calendar"></i> Criado em <?= date('d/m/Y', strtotime($projeto['data_criado'])) ?></p>

    <?php if ($projeto['descricao']): ?>
        <p class="projeto-descricao"><?= htmlspecialchars($projeto['descricao']) ?></p>
    <?php endif ?>
</a>
