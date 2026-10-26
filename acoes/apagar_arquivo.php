<?php

    session_start();

    if (!isset($_SESSION['id'])) {
        header("Location:../login.php"); exit;
    }

    // Apagar só por POST (pelo botão do formulário), nunca por um link,
    // para não apagar nada sem querer ao abrir uma URL
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location:../paginas/projetos.php"); exit;
    }

    require_once("db_con_init.php");
    require_once("permissoes.php");

    // O id do arquivo vem na URL: apagar_arquivo.php?id=1
    $stmt = $conexao->prepare("SELECT id, id_projeto, nome_salvo FROM arquivos WHERE id = ?");
    $stmt->execute([$_GET['id'] ?? null]);
    $arquivo = $stmt->fetch(PDO::FETCH_ASSOC);

    // Por enquanto só arquivos de projeto podem ser apagados (não os anexos da proposta)
    $projeto = $arquivo && $arquivo['id_projeto']
        ? buscar_projeto_do_usuario($conexao, $_SESSION['id'], $arquivo['id_projeto'])
        : false;

    // Só o aluno dono do projeto pode apagar
    if (!$projeto || !$projeto['e_autor']) {
        header("Location:../paginas/projetos.php"); exit;
    }

    $voltar = "Location:../paginas/projeto.php?id=" . $projeto['id'];

    // Depois do prazo de entrega não dá para apagar arquivos
    if (!$projeto['pode_alterar']) {
        $_SESSION['erro'] = 'O prazo de entrega já passou. Não é possível apagar arquivos.';
        header($voltar); exit;
    }

    // Apaga o arquivo da pasta uploads/ e depois o registro no banco
    $caminho = __DIR__ . '/../uploads/' . $arquivo['nome_salvo'];
    if (file_exists($caminho)) {
        unlink($caminho);
    }

    $stmt = $conexao->prepare("DELETE FROM arquivos WHERE id = ?");
    $stmt->execute([$arquivo['id']]);

    $_SESSION['sucesso'] = 'Arquivo apagado.';
    header($voltar); exit;

?>
