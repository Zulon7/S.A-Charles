<?php
    /**
     * Card de uma proposta. Usado em paginas/propostas.php e paginas/turma.php
     * O card inteiro é um link para a página da proposta.
     * @var array $proposta precisa ter: id, nome, descricao, data_entrega, total_projetos, id_meu_projeto
     *                      e opcionalmente nome_turma (para mostrar a turma)
     * @var string $base vem do header.php
     */
?>
<a class="turma-card" href="<?= $base ?>/paginas/proposta.php?id=<?= $proposta['id'] ?>">
    <h3><?= htmlspecialchars($proposta['nome']) ?></h3>

    <?php if (isset($proposta['nome_turma'])): ?>
        <p><i class="bi bi-people"></i> <?= htmlspecialchars($proposta['nome_turma']) ?></p>
    <?php endif ?>

    <p>
        <i class="bi bi-calendar"></i>
        <?php if ($proposta['data_entrega']): ?>
            Entrega em <?= date('d/m/Y', strtotime($proposta['data_entrega'])) ?>
        <?php else: ?>
            Sem data de entrega
        <?php endif ?>
    </p>

    <!-- Professor vê quantos projetos recebeu, aluno vê se já enviou o dele -->
    <?php if ($_SESSION['nivel'] == 'professor'): ?>
        <p><i class="bi bi-inbox"></i> <?= $proposta['total_projetos'] ?> projeto(s) recebido(s)</p>
    <?php elseif ($proposta['id_meu_projeto']): ?>
        <p class="status-enviado"><i class="bi bi-check-circle"></i> Projeto enviado</p>
    <?php else: ?>
        <p class="status-pendente"><i class="bi bi-clock"></i> Pendente</p>
    <?php endif ?>

    <?php if ($proposta['descricao']): ?>
        <p class="projeto-descricao"><?= htmlspecialchars($proposta['descricao']) ?></p>
    <?php endif ?>
</a>
