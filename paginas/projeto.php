<?php include(__DIR__ . '/../header.php'); ?>

<?php
    require_once(__DIR__ . '/../acoes/db_con_init.php');
    require_once(__DIR__ . '/../acoes/permissoes.php');

    // Id do projeto vem da URL: projeto.php?id=1
    $projeto = buscar_projeto_do_usuario($conexao, $_SESSION['id'], $_GET['id'] ?? null);

    // Projeto não existe ou o usuário não faz parte da turma dele
    if (!$projeto) {
        echo '<h2 class="titulo">Projeto não encontrado</h2>';
        echo '<a class="voltar" href="' . $base . '/paginas/projetos.php"><i class="bi bi-arrow-left"></i> Voltar para projetos</a>';
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

    // Mostra o tamanho do arquivo em bytes, KB ou MB
    function formatar_tamanho($bytes) {
        if ($bytes < 1024) {
            return $bytes . ' bytes';
        }
        if ($bytes < 1024 * 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }
        return round($bytes / (1024 * 1024), 1) . ' MB';
    }
?>

<a class="voltar" href="<?= $base ?>/paginas/projetos.php"><i class="bi bi-arrow-left"></i> Voltar para projetos</a>

<h2 class="titulo"><?= htmlspecialchars($projeto['nome']) ?></h2>
<p class="turma-info">
    Turma: <a href="<?= $base ?>/paginas/turma.php?id=<?= $projeto['id_turma'] ?>"><?= htmlspecialchars($projeto['nome_turma']) ?></a>
    · Criado em <?= date('d/m/Y', strtotime($projeto['data_criado'])) ?>
    · <?php if ($projeto['data_entrega']): ?>
        Entrega em <?= date('d/m/Y', strtotime($projeto['data_entrega'])) ?>
    <?php else: ?>
        Sem data de entrega
    <?php endif ?>
</p>

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

    <?php if (isset($_SESSION['erro'])): ?>
        <div class="login-aviso">
            <span><?= $_SESSION['erro'] ?></span>
            <button onclick="close_element('login-aviso');"><i class="bi bi-x"></i></button>
        </div>
        <?php unset($_SESSION['erro']) ?>
    <?php endif ?>

    <?php if (isset($_SESSION['sucesso'])): ?>
        <div class="login-aviso aviso-sucesso">
            <span><?= $_SESSION['sucesso'] ?></span>
            <button onclick="close_element('aviso-sucesso');"><i class="bi bi-x"></i></button>
        </div>
        <?php unset($_SESSION['sucesso']) ?>
    <?php endif ?>

    <!-- enctype="multipart/form-data" é obrigatório para enviar arquivos -->
    <form class="upload-form" action="<?= $base ?>/acoes/enviar_arquivo.php?id=<?= $projeto['id'] ?>" method="POST" enctype="multipart/form-data">
        <input type="file" name="arquivo" required>
        <button type="submit"><i class="bi bi-upload"></i> Enviar</button>
        <small>Máximo 10 MB</small>
    </form>

    <?php if (empty($arquivos)): ?>
        <p class="turmas-vazio">Nenhum arquivo enviado ainda.</p>
    <?php else: ?>
        <ul class="membros-lista">
            <?php foreach ($arquivos as $arquivo): ?>
                <li>
                    <i class="bi bi-file-earmark"></i>
                    <a href="<?= $base ?>/acoes/baixar_arquivo.php?id=<?= $arquivo['id'] ?>"><?= htmlspecialchars($arquivo['nome_original']) ?></a>
                    <small>
                        <?= formatar_tamanho($arquivo['tamanho']) ?>
                        · enviado por <?= htmlspecialchars($arquivo['nome_user']) ?>
                        em <?= date('d/m/Y H:i', strtotime($arquivo['data_envio'])) ?>
                    </small>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
</section>

<?php include(__DIR__ . '/../footer.php'); ?>
