<?php
    /**
     * Botão de curtir com o total de curtidas. Usado em paginas/feed.php e paginas/projeto.php
     * @var array  $projeto precisa ter: id, total_curtidas, eu_curti
     * @var string $base    vem do header.php
     * @var array  $filtros_feed (opcional) filtros do feed (busca, turma, ordem).
     *                      Se existir, depois de curtir o usuário volta para o feed com os mesmos filtros;
     *                      se não, volta para a página do projeto.
     */
    $filtros_feed = $filtros_feed ?? null;
?>
<form class="form-curtir" action="<?= $base ?>/acoes/curtir_projeto.php?id=<?= $projeto['id'] ?>" method="POST">
    <?php if ($filtros_feed !== null): ?>
        <input type="hidden" name="voltar" value="feed">
        <?php foreach ($filtros_feed as $nome => $valor): ?>
            <input type="hidden" name="<?= $nome ?>" value="<?= htmlspecialchars($valor) ?>">
        <?php endforeach ?>
    <?php endif ?>

    <!-- Coração cheio e vermelho quando o usuário já curtiu -->
    <?php if ($projeto['eu_curti']): ?>
        <button type="submit" class="curtido" title="Tirar a curtida"><i class="bi bi-heart-fill"></i> <?= $projeto['total_curtidas'] ?></button>
    <?php else: ?>
        <button type="submit" title="Curtir"><i class="bi bi-heart"></i> <?= $projeto['total_curtidas'] ?></button>
    <?php endif ?>
</form>
