<?php
    /**
     * Card de um projeto. Usado em paginas/projetos.php e paginas/turma.php
     * O card inteiro é um link para a página do projeto.
     * @var array $projeto precisa ter: id, nome, descricao, data_entrega
     *                     e opcionalmente nome_turma (para mostrar a turma)
     * @var string $base vem do header.php
     */
?>
<a class="turma-card" href="<?= $base ?>/paginas/projeto.php?id=<?= $projeto['id'] ?>">
    <h3><?= htmlspecialchars($projeto['nome']) ?></h3>

    <?php if (isset($projeto['nome_turma'])): ?>
        <p><i class="bi bi-people"></i> <?= htmlspecialchars($projeto['nome_turma']) ?></p>
    <?php endif ?>

    <p>
        <i class="bi bi-calendar"></i>
        <?php if ($projeto['data_entrega']): ?>
            Entrega em <?= date('d/m/Y', strtotime($projeto['data_entrega'])) ?>
        <?php else: ?>
            Sem data de entrega
        <?php endif ?>
    </p>

    <?php if ($projeto['descricao']): ?>
        <p class="projeto-descricao"><?= htmlspecialchars($projeto['descricao']) ?></p>
    <?php endif ?>
</a>
