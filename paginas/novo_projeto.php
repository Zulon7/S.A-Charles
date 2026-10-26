<?php include(__DIR__ . '/../header.php'); ?>

<?php
    require_once(__DIR__ . '/../acoes/db_con_init.php');
    require_once(__DIR__ . '/../acoes/permissoes.php');

    // Id da proposta vem da URL: novo_projeto.php?proposta=1
    $proposta = buscar_proposta_do_usuario($conexao, $_SESSION['id'], $_GET['proposta'] ?? null);

    // Só alunos da turma podem criar projetos
    if (!$proposta || !$proposta['e_aluno']) {
        echo '<h2 class="titulo">Proposta não encontrada</h2>';
        include(__DIR__ . '/../footer.php');
        exit;
    }

    // Se o aluno já tem projeto nesta proposta, mostra um link para ele em vez do formulário
    $stmt = $conexao->prepare("SELECT id, nome FROM projetos WHERE id_proposta = ? AND id_aluno = ?");
    $stmt->execute([$proposta['id'], $_SESSION['id']]);
    $meu_projeto = $stmt->fetch(PDO::FETCH_ASSOC);
?>


<h2 class="titulo">Novo projeto</h2>
<p class="turma-info">
    Proposta: <a href="<?= $base ?>/paginas/proposta.php?id=<?= $proposta['id'] ?>"><?= htmlspecialchars($proposta['nome']) ?></a>
    · Turma: <?= htmlspecialchars($proposta['nome_turma']) ?>
    · <?php if ($proposta['data_entrega']): ?>
        Entrega em <?= date('d/m/Y', strtotime($proposta['data_entrega'])) ?>
    <?php else: ?>
        Sem data de entrega
    <?php endif ?>
</p>

<?php include(__DIR__ . '/../avisos.php'); ?>

<?php if ($meu_projeto): ?>
    <p>
        Você já tem um projeto nesta proposta:
        <a href="<?= $base ?>/paginas/projeto.php?id=<?= $meu_projeto['id'] ?>"><?= htmlspecialchars($meu_projeto['nome']) ?></a>
    </p>
<?php else: ?>
    <form class="form-simples" action="<?= $base ?>/acoes/criar_projeto.php?proposta=<?= $proposta['id'] ?>" method="POST">
        <label for="nome">Nome do projeto</label>
        <input type="text" id="nome" name="nome" maxlength="150" required>

        <label for="descricao">Descrição (opcional)</label>
        <textarea id="descricao" name="descricao" rows="5"></textarea>

        <button type="submit"><i class="bi bi-plus-lg"></i> Criar projeto</button>
        <small>Depois de criar, você envia os arquivos na página do projeto.</small>
    </form>
<?php endif ?>

<?php include(__DIR__ . '/../footer.php'); ?>
