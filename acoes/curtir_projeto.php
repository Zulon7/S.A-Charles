<?php

    // Curte um projeto, ou tira a curtida se o usuário já tinha curtido

    session_start();

    if (!isset($_SESSION['id'])) {
        header("Location:../login.php"); exit;
    }

    // Só por POST (pelo botão do formulário), nunca por um link
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location:../paginas/feed.php"); exit;
    }

    require_once("db_con_init.php");
    require_once("permissoes.php");

    // O id do projeto vem na URL: curtir_projeto.php?id=1
    $projeto = buscar_projeto_do_usuario($conexao, $_SESSION['id'], $_GET['id'] ?? null);

    // Só dá para curtir projetos enviados (públicos). Rascunho não tem curtida.
    if (!$projeto || !$projeto['enviado']) {
        header("Location:../paginas/feed.php"); exit;
    }

    // Tenta tirar a curtida. Se não apagou nenhuma linha, é porque
    // o usuário ainda não tinha curtido: então cria a curtida.
    $stmt = $conexao->prepare("DELETE FROM curtidas WHERE id_user = ? AND id_projeto = ?");
    $stmt->execute([$_SESSION['id'], $projeto['id']]);

    if ($stmt->rowCount() == 0) {
        // INSERT IGNORE: se o botão for clicado duas vezes ao mesmo tempo,
        // a chave primária (id_user, id_projeto) impede a curtida repetida
        $stmt = $conexao->prepare("INSERT IGNORE INTO curtidas (id_user, id_projeto) VALUES (?, ?)");
        $stmt->execute([$_SESSION['id'], $projeto['id']]);
    }

    // Volta para onde o usuário estava: o feed (com os mesmos filtros) ou a página do projeto
    if (($_POST['voltar'] ?? '') == 'feed') {
        // Monta a URL de novo só com os filtros conhecidos, em vez de confiar em uma URL pronta
        $filtros = http_build_query([
            'busca' => $_POST['busca'] ?? '',
            'turma' => $_POST['turma'] ?? '',
            'ordem' => $_POST['ordem'] ?? ''
        ]);
        // O #projeto-1 faz o navegador rolar de volta até o post curtido
        header("Location:../paginas/feed.php?" . $filtros . "#projeto-" . $projeto['id']); exit;
    }

    header("Location:../paginas/projeto.php?id=" . $projeto['id']); exit;

?>
