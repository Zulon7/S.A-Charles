<?php include(__DIR__ . '/../header.php'); ?>

<?php
    require_once(__DIR__ . '/../acoes/db_con_init.php');

    // Filtros, que vêm da URL: feed.php?busca=site&turma=1&ordem=recentes
    $busca = trim($_GET['busca'] ?? '');
    $id_turma = $_GET['turma'] ?? '';
    // Por padrão os projetos mais curtidos ficam no topo
    $ordem = ($_GET['ordem'] ?? '') == 'recentes' ? 'recentes' : 'curtidas';

    // Guardados para o botão de curtir voltar para o feed com os mesmos filtros
    $filtros_feed = ['busca' => $busca, 'turma' => $id_turma, 'ordem' => $ordem];

    // Todas as turmas, para o <select> do filtro
    $turmas = $conexao->query("SELECT id, nome FROM turmas ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

    // O feed é público, como o de uma rede social: mostra os projetos enviados
    // por todos os alunos, de qualquer turma. Rascunhos (não enviados) não aparecem.
    // Cada filtro preenchido acrescenta uma condição no WHERE e seus valores em $parametros.
    $condicoes = ["pj.data_enviado IS NOT NULL"];
    $parametros = [$_SESSION['id']]; // o primeiro ? da consulta é o do eu_curti

    if ($busca != '') {
        // Procura no nome do projeto ou no nome do aluno
        $condicoes[] = "(pj.nome LIKE ? OR u.nome LIKE ?)";
        $parametros[] = "%$busca%";
        $parametros[] = "%$busca%";
    }

    if ($id_turma != '') {
        $condicoes[] = "t.id = ?";
        $parametros[] = $id_turma;
    }

    // Mais curtidos primeiro (no empate, o mais novo) ou só do mais novo para o mais antigo
    if ($ordem == 'recentes') {
        $ordenar = "pj.data_enviado DESC";
    } else {
        $ordenar = "total_curtidas DESC, pj.data_enviado DESC";
    }

    // total_arquivos: quantos arquivos o projeto tem
    // total_curtidas: quantas curtidas o projeto recebeu
    // eu_curti: 1 se o usuário logado já curtiu, 0 se não
    $sql = "SELECT pj.id, pj.nome, pj.descricao, pj.data_enviado,
                   u.nome AS nome_aluno, pr.nome AS nome_proposta, t.nome AS nome_turma,
                   (SELECT COUNT(*) FROM arquivos a WHERE a.id_projeto = pj.id) AS total_arquivos,
                   (SELECT COUNT(*) FROM curtidas c WHERE c.id_projeto = pj.id) AS total_curtidas,
                   (SELECT COUNT(*) FROM curtidas c WHERE c.id_projeto = pj.id AND c.id_user = ?) AS eu_curti
            FROM projetos pj
            INNER JOIN propostas pr ON pr.id = pj.id_proposta
            INNER JOIN turmas t ON t.id = pr.id_turma
            INNER JOIN users u ON u.id = pj.id_aluno
            WHERE " . implode(" AND ", $condicoes) . "
            ORDER BY $ordenar
            LIMIT 30";
    $stmt = $conexao->prepare($sql);
    $stmt->execute($parametros);
    $projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tem_filtro = ($busca != '' || $id_turma != '');
?>

<h2 class="titulo">Feed</h2>
<p class="turma-info">Os projetos enviados por todos os alunos</p>

<!-- method="GET": os filtros vão na URL, então dá para voltar ou compartilhar o link -->
<form class="feed-filtros" action="<?= $base ?>/paginas/feed.php" method="GET">
    <input type="text" name="busca" placeholder="Buscar projeto ou aluno" value="<?= htmlspecialchars($busca) ?>">

    <select name="turma">
        <option value="">Todas as turmas</option>
        <?php foreach ($turmas as $turma): ?>
            <option value="<?= $turma['id'] ?>" <?= $turma['id'] == $id_turma ? 'selected' : '' ?>><?= htmlspecialchars($turma['nome']) ?></option>
        <?php endforeach ?>
    </select>

    <select name="ordem">
        <option value="curtidas" <?= $ordem == 'curtidas' ? 'selected' : '' ?>>Mais curtidos</option>
        <option value="recentes" <?= $ordem == 'recentes' ? 'selected' : '' ?>>Mais recentes</option>
    </select>

    <button type="submit"><i class="bi bi-funnel"></i> Filtrar</button>

    <?php if ($tem_filtro): ?>
        <a href="<?= $base ?>/paginas/feed.php?ordem=<?= $ordem ?>">Limpar filtros</a>
    <?php endif ?>
</form>

<?php if (empty($projetos)): ?>
    <?php if ($tem_filtro): ?>
        <p class="turmas-vazio">Nenhum projeto encontrado com esses filtros.</p>
    <?php else: ?>
        <p class="turmas-vazio">Nenhum projeto foi enviado ainda.</p>
    <?php endif ?>
<?php else: ?>
    <div class="feed-lista">
        <?php foreach ($projetos as $projeto): ?>
            <!-- O id é usado para voltar até este post depois de curtir (#projeto-1) -->
            <div class="turma-card" id="projeto-<?= $projeto['id'] ?>">
                <p>
                    <i class="bi bi-person-circle"></i>
                    <strong><?= htmlspecialchars($projeto['nome_aluno']) ?></strong>
                    enviou um projeto · <?= date('d/m/Y H:i', strtotime($projeto['data_enviado'])) ?>
                </p>

                <h3><a href="<?= $base ?>/paginas/projeto.php?id=<?= $projeto['id'] ?>"><?= htmlspecialchars($projeto['nome']) ?></a></h3>

                <?php if ($projeto['descricao']): ?>
                    <p class="projeto-descricao"><?= nl2br(htmlspecialchars($projeto['descricao'])) ?></p>
                <?php endif ?>

                <p class="feed-rodape">
                    <span><i class="bi bi-journal-text"></i> <?= htmlspecialchars($projeto['nome_proposta']) ?></span>
                    <span><i class="bi bi-people"></i> <?= htmlspecialchars($projeto['nome_turma']) ?></span>
                    <span><i class="bi bi-paperclip"></i> <?= $projeto['total_arquivos'] ?> arquivo(s)</span>
                </p>

                <?php include(__DIR__ . '/../botao_curtir.php'); ?>
            </div>
        <?php endforeach ?>
    </div>
<?php endif ?>

<?php include(__DIR__ . '/../footer.php'); ?>
