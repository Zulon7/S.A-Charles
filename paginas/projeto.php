<?php include(__DIR__ . '/../header.php'); ?>

<?php
    require_once(__DIR__ . '/../acoes/db_con_init.php');
    require_once(__DIR__ . '/../acoes/permissoes.php');

    // Id do projeto vem da URL: projeto.php?id=1
    $projeto = buscar_projeto_do_usuario($conexao, $_SESSION['id'], $_GET['id'] ?? null);

    // Projeto não existe, ou o usuário não é o aluno dele nem professor da turma
    if (!$projeto) {
        echo '<h2 class="titulo">Projeto não encontrado</h2>';
        include(__DIR__ . '/../footer.php');
        exit;
    }

    // Arquivos do projeto, mais novos primeiro, com o nome de quem enviou
    $sql = "SELECT a.id, a.nome_original, a.tamanho, a.data_envio, u.nome AS nome_user
            FROM arquivos a
            INNER JOIN users u ON u.id = a.id_user
            WHERE a.id_projeto = ?
            ORDER BY a.data_envio DESC";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$projeto['id']]);
    $arquivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<h2 class="titulo"><?= htmlspecialchars($projeto['nome']) ?></h2>
<p class="turma-info">
    Proposta: <a href="<?= $base ?>/paginas/proposta.php?id=<?= $projeto['id_proposta'] ?>"><?= htmlspecialchars($projeto['nome_proposta']) ?></a>
    · Aluno: <?= htmlspecialchars($projeto['nome_aluno']) ?>
    · Criado em <?= date('d/m/Y H:i', strtotime($projeto['data_criado'])) ?>
    · <?php if ($projeto['data_entrega']): ?>
        Entrega até <?= date('d/m/Y', strtotime($projeto['data_entrega'])) ?>
    <?php else: ?>
        Sem data de entrega
    <?php endif ?>
</p>

<?php include(__DIR__ . '/../avisos.php'); ?>

<!-- Só o aluno dono pode editar ou apagar, e só até o prazo de entrega -->
<?php if ($projeto['pode_alterar']): ?>
    <div class="acoes-projeto">
        <a class="botao" href="<?= $base ?>/paginas/editar_projeto.php?id=<?= $projeto['id'] ?>"><i class="bi bi-pencil"></i> Editar projeto</a>

        <form action="<?= $base ?>/acoes/apagar_projeto.php?id=<?= $projeto['id'] ?>" method="POST"
              onsubmit="return confirm('Apagar este projeto e todos os arquivos dele? Isso não pode ser desfeito.');">
            <button type="submit" class="botao botao-perigo"><i class="bi bi-trash"></i> Apagar projeto</button>
        </form>
    </div>
<?php elseif ($projeto['e_autor']): ?>
    <p class="prazo-encerrado"><i class="bi bi-lock"></i> O prazo de entrega já passou. O projeto não pode mais ser alterado.</p>
<?php endif ?>

<section class="turma-secao">
    <h3>Descrição</h3>
    <?php if ($projeto['descricao']): ?>
        <p><?= nl2br(htmlspecialchars($projeto['descricao'])) ?></p>
    <?php else: ?>
        <p class="turmas-vazio">Este projeto não tem descrição.</p>
    <?php endif ?>
</section>

<section class="turma-secao">
    <h3>Arquivos (<?= count($arquivos) ?>)</h3>
    <?php
        // Só o aluno dono do projeto envia e apaga arquivos, e só até o prazo
        $pode_enviar = $projeto['pode_alterar'];
        $pode_apagar = $projeto['pode_alterar'];
        $url_envio = "$base/acoes/enviar_arquivo.php?projeto=" . $projeto['id'];
        include(__DIR__ . '/../arquivos_lista.php');
    ?>
</section>

<?php include(__DIR__ . '/../footer.php'); ?>
