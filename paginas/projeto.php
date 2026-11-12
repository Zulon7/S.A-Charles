<?php include(__DIR__ . '/../header.php'); ?>

<?php
    require_once(__DIR__ . '/../acoes/db_con_init.php');
    require_once(__DIR__ . '/../acoes/permissoes.php');

    // Id do projeto vem da URL: projeto.php?id=1
    $projeto = buscar_projeto_do_usuario($conexao, $_SESSION['id'], $_GET['id'] ?? null);

    // Projeto não existe, ou ainda é rascunho de outro aluno
    if (!$projeto) {
        echo '<h2 class="titulo">Projeto não encontrado</h2>';
        include(__DIR__ . '/../footer.php');
        exit;
    }

    // O projeto enviado é público, mas a proposta só abre para quem é da turma.
    // Para os outros o nome da proposta aparece sem link.
    $pode_ver_proposta = (bool) buscar_proposta_do_usuario($conexao, $_SESSION['id'], $projeto['id_proposta']);

    // Curtidas: o total e se o usuário logado já curtiu (usados em botao_curtir.php)
    $stmt = $conexao->prepare("SELECT COUNT(*) AS total_curtidas, COALESCE(SUM(id_user = ?), 0) AS eu_curti
                               FROM curtidas WHERE id_projeto = ?");
    $stmt->execute([$_SESSION['id'], $projeto['id']]);
    $projeto = array_merge($projeto, $stmt->fetch(PDO::FETCH_ASSOC));

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
    Proposta:
    <?php if ($pode_ver_proposta): ?>
        <a href="<?= $base ?>/paginas/proposta.php?id=<?= $projeto['id_proposta'] ?>"><?= htmlspecialchars($projeto['nome_proposta']) ?></a>
    <?php else: ?>
        <?= htmlspecialchars($projeto['nome_proposta']) ?>
    <?php endif ?>
    · Aluno: <?= htmlspecialchars($projeto['nome_aluno']) ?>
    · Criado em <?= date('d/m/Y H:i', strtotime($projeto['data_criado'])) ?>
    · <?php if ($projeto['data_entrega']): ?>
        Entrega até <?= date('d/m/Y', strtotime($projeto['data_entrega'])) ?>
    <?php else: ?>
        Sem data de entrega
    <?php endif ?>
</p>

<!-- O projeto começa como rascunho; depois de enviado ele fica público e aparece no feed -->
<?php if ($projeto['enviado']): ?>
    <p class="status-enviado"><i class="bi bi-check-circle"></i> Enviado em <?= date('d/m/Y H:i', strtotime($projeto['data_enviado'])) ?> · público no feed</p>
    <!-- Só projetos enviados podem ser curtidos -->
    <?php include(__DIR__ . '/../botao_curtir.php'); ?>
<?php else: ?>
    <p class="status-pendente"><i class="bi bi-clock"></i> Rascunho: só você vê. Ao enviar, o projeto fica público no feed.</p>
<?php endif ?>

<?php include(__DIR__ . '/../avisos.php'); ?>

<!-- Só o aluno dono pode enviar, editar ou apagar, e só até o prazo de entrega -->
<?php if ($projeto['pode_alterar']): ?>
    <div class="acoes-projeto">
        <!-- O mesmo formulário envia ou cancela o envio, conforme o campo "acao" -->
        <form action="<?= $base ?>/acoes/enviar_projeto.php?id=<?= $projeto['id'] ?>" method="POST">
            <?php if ($projeto['enviado']): ?>
                <input type="hidden" name="acao" value="cancelar">
                <button type="submit" class="botao botao-secundario"><i class="bi bi-arrow-counterclockwise"></i> Cancelar envio</button>
            <?php else: ?>
                <input type="hidden" name="acao" value="enviar">
                <button type="submit" class="botao botao-enviar"><i class="bi bi-send"></i> Enviar projeto</button>
            <?php endif ?>
        </form>

        <a class="botao" href="<?= $base ?>/paginas/editar_projeto.php?id=<?= $projeto['id'] ?>"><i class="bi bi-pencil"></i> Editar projeto</a>

        <form action="<?= $base ?>/acoes/apagar_projeto.php?id=<?= $projeto['id'] ?>" method="POST"
              onsubmit="return confirm('Apagar este projeto e todos os arquivos dele? Isso não pode ser desfeito.');">
            <button type="submit" class="botao botao-perigo"><i class="bi bi-trash"></i> Apagar projeto</button>
        </form>
    </div>
<?php elseif ($projeto['e_autor']): ?>
    <p class="prazo-encerrado">
        <i class="bi bi-lock"></i> O prazo de entrega já passou. O projeto não pode mais ser alterado<?= $projeto['enviado'] ? '.' : ' nem enviado.' ?>
    </p>
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
