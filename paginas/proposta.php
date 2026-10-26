<?php include(__DIR__ . '/../header.php'); ?>

<?php
    require_once(__DIR__ . '/../acoes/db_con_init.php');
    require_once(__DIR__ . '/../acoes/permissoes.php');

    // Id da proposta vem da URL: proposta.php?id=1
    $proposta = buscar_proposta_do_usuario($conexao, $_SESSION['id'], $_GET['id'] ?? null);

    // Proposta não existe ou o usuário não faz parte da turma dela
    if (!$proposta) {
        echo '<h2 class="titulo">Proposta não encontrada</h2>';
        echo '<a class="voltar" href="' . $base . '/paginas/propostas.php"><i class="bi bi-arrow-left"></i> Voltar para propostas</a>';
        include(__DIR__ . '/../footer.php');
        exit;
    }

    // Arquivos que o professor anexou na proposta
    $sql = "SELECT a.id, a.nome_original, a.tamanho, a.data_envio, u.nome AS nome_user
            FROM arquivos a
            INNER JOIN users u ON u.id = a.id_user
            WHERE a.id_proposta = ?
            ORDER BY a.data_envio DESC";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$proposta['id']]);
    $arquivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($proposta['e_professor']) {
        // Professor: todos os projetos enviados para a proposta
        $sql = "SELECT pj.id, pj.nome, pj.data_criado, u.nome AS nome_aluno
                FROM projetos pj
                INNER JOIN users u ON u.id = pj.id_aluno
                WHERE pj.id_proposta = ?
                ORDER BY u.nome";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([$proposta['id']]);
        $projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Quantos alunos a turma tem, para mostrar "X de Y enviaram"
        $stmt = $conexao->prepare("SELECT COUNT(*) FROM user_participa WHERE id_turma = ?");
        $stmt->execute([$proposta['id_turma']]);
        $total_alunos = $stmt->fetchColumn();
    } else {
        // Aluno: só o projeto dele, se já enviou
        $sql = "SELECT id, nome, data_criado FROM projetos WHERE id_proposta = ? AND id_aluno = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([$proposta['id'], $_SESSION['id']]);
        $meu_projeto = $stmt->fetch(PDO::FETCH_ASSOC);
    }
?>

<a class="voltar" href="<?= $base ?>/paginas/propostas.php"><i class="bi bi-arrow-left"></i> Voltar para propostas</a>

<h2 class="titulo"><?= htmlspecialchars($proposta['nome']) ?></h2>
<p class="turma-info">
    Turma: <a href="<?= $base ?>/paginas/turma.php?id=<?= $proposta['id_turma'] ?>"><?= htmlspecialchars($proposta['nome_turma']) ?></a>
    · Professor: <?= htmlspecialchars($proposta['nome_professor']) ?>
    · Criada em <?= date('d/m/Y', strtotime($proposta['data_criado'])) ?>
    · <?php if ($proposta['data_entrega']): ?>
        Entrega em <?= date('d/m/Y', strtotime($proposta['data_entrega'])) ?>
    <?php else: ?>
        Sem data de entrega
    <?php endif ?>
</p>

<?php include(__DIR__ . '/../avisos.php'); ?>

<section class="turma-secao">
    <h3>Descrição</h3>
    <?php if ($proposta['descricao']): ?>
        <p><?= nl2br(htmlspecialchars($proposta['descricao'])) ?></p>
    <?php else: ?>
        <p class="turmas-vazio">Esta proposta não tem descrição.</p>
    <?php endif ?>
</section>

<section class="turma-secao">
    <h3>Arquivos da proposta (<?= count($arquivos) ?>)</h3>
    <?php
        // Só professores da turma anexam arquivos na proposta
        $pode_enviar = $proposta['e_professor'];
        $url_envio = "$base/acoes/enviar_arquivo.php?proposta=" . $proposta['id'];
        include(__DIR__ . '/../arquivos_lista.php');
    ?>
</section>

<?php if ($proposta['e_professor']): ?>

    <section class="turma-secao">
        <h3>Projetos enviados (<?= count($projetos) ?> de <?= $total_alunos ?> alunos)</h3>
        <?php if (empty($projetos)): ?>
            <p class="turmas-vazio">Nenhum aluno enviou projeto ainda.</p>
        <?php else: ?>
            <ul class="membros-lista">
                <?php foreach ($projetos as $projeto): ?>
                    <li>
                        <i class="bi bi-person"></i>
                        <span><?= htmlspecialchars($projeto['nome_aluno']) ?></span>
                        <a href="<?= $base ?>/paginas/projeto.php?id=<?= $projeto['id'] ?>"><?= htmlspecialchars($projeto['nome']) ?></a>
                        <small>enviado em <?= date('d/m/Y H:i', strtotime($projeto['data_criado'])) ?></small>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>
    </section>

<?php else: ?>

    <section class="turma-secao">
        <h3>Seu projeto</h3>
        <?php if ($meu_projeto): ?>
            <p>
                <i class="bi bi-check-circle status-enviado"></i>
                <a href="<?= $base ?>/paginas/projeto.php?id=<?= $meu_projeto['id'] ?>"><?= htmlspecialchars($meu_projeto['nome']) ?></a>
                <small class="turmas-vazio">criado em <?= date('d/m/Y H:i', strtotime($meu_projeto['data_criado'])) ?></small>
            </p>
        <?php else: ?>
            <p class="turmas-vazio">Você ainda não enviou um projeto para esta proposta.</p>
            <form class="form-simples" action="<?= $base ?>/acoes/criar_projeto.php?proposta=<?= $proposta['id'] ?>" method="POST">
                <label for="nome">Nome do projeto</label>
                <input type="text" id="nome" name="nome" maxlength="150" required>

                <label for="descricao">Descrição (opcional)</label>
                <textarea id="descricao" name="descricao" rows="3"></textarea>

                <button type="submit"><i class="bi bi-plus-lg"></i> Criar projeto</button>
            </form>
        <?php endif ?>
    </section>

<?php endif ?>

<?php include(__DIR__ . '/../footer.php'); ?>
