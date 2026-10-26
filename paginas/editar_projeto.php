<?php include(__DIR__ . '/../header.php'); ?>

<?php
    require_once(__DIR__ . '/../acoes/db_con_init.php');
    require_once(__DIR__ . '/../acoes/permissoes.php');

    // Id do projeto vem da URL: editar_projeto.php?id=1
    $projeto = buscar_projeto_do_usuario($conexao, $_SESSION['id'], $_GET['id'] ?? null);

    // Só o aluno dono do projeto pode editar
    if (!$projeto || !$projeto['e_autor']) {
        echo '<h2 class="titulo">Projeto não encontrado</h2>';
        include(__DIR__ . '/../footer.php');
        exit;
    }

    // Depois do prazo de entrega não dá mais para editar
    if (!$projeto['pode_alterar']) {
        echo '<h2 class="titulo">Prazo encerrado</h2>';
        echo '<p class="prazo-encerrado"><i class="bi bi-lock"></i> O prazo de entrega já passou. O projeto não pode mais ser alterado.</p>';
        include(__DIR__ . '/../footer.php');
        exit;
    }
?>

<h2 class="titulo">Editar projeto</h2>
<p class="turma-info">
    Proposta: <a href="<?= $base ?>/paginas/proposta.php?id=<?= $projeto['id_proposta'] ?>"><?= htmlspecialchars($projeto['nome_proposta']) ?></a>
</p>

<?php include(__DIR__ . '/../avisos.php'); ?>

<!-- Os campos já vêm preenchidos com os dados atuais do projeto -->
<form class="form-simples" action="<?= $base ?>/acoes/editar_projeto.php?id=<?= $projeto['id'] ?>" method="POST">
    <label for="nome">Nome do projeto</label>
    <input type="text" id="nome" name="nome" maxlength="150" required value="<?= htmlspecialchars($projeto['nome']) ?>">

    <label for="descricao">Descrição (opcional)</label>
    <textarea id="descricao" name="descricao" rows="5"><?= htmlspecialchars($projeto['descricao'] ?? '') ?></textarea>

    <button type="submit"><i class="bi bi-check-lg"></i> Salvar</button>
    <a href="<?= $base ?>/paginas/projeto.php?id=<?= $projeto['id'] ?>">Cancelar</a>
</form>

<?php include(__DIR__ . '/../footer.php'); ?>
