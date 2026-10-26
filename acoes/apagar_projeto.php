<?php

    session_start();

    if (!isset($_SESSION['id'])) {
        header("Location:../login.php"); exit;
    }

    // Apagar só por POST (pelo botão do formulário), nunca por um link
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location:../paginas/projetos.php"); exit;
    }

    require_once("db_con_init.php");
    require_once("permissoes.php");

    // O id do projeto vem na URL: apagar_projeto.php?id=1
    $projeto = buscar_projeto_do_usuario($conexao, $_SESSION['id'], $_GET['id'] ?? null);

    // Só o aluno dono do projeto pode apagar
    if (!$projeto || !$projeto['e_autor']) {
        header("Location:../paginas/projetos.php"); exit;
    }

    // Depois do prazo de entrega não dá para apagar o projeto
    if (!$projeto['pode_alterar']) {
        $_SESSION['erro'] = 'O prazo de entrega já passou. O projeto não pode mais ser apagado.';
        header("Location:../paginas/projeto.php?id=" . $projeto['id']); exit;
    }

    // Primeiro apaga os arquivos do projeto da pasta uploads/
    $stmt = $conexao->prepare("SELECT nome_salvo FROM arquivos WHERE id_projeto = ?");
    $stmt->execute([$projeto['id']]);
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $nome_salvo) {
        $caminho = __DIR__ . '/../uploads/' . $nome_salvo;
        if (file_exists($caminho)) {
            unlink($caminho);
        }
    }

    // Depois os registros dos arquivos e o próprio projeto
    $stmt = $conexao->prepare("DELETE FROM arquivos WHERE id_projeto = ?");
    $stmt->execute([$projeto['id']]);

    $stmt = $conexao->prepare("DELETE FROM projetos WHERE id = ?");
    $stmt->execute([$projeto['id']]);

    // Volta para a proposta, onde o aluno pode criar um projeto novo
    $_SESSION['sucesso'] = 'Projeto apagado.';
    header("Location:../paginas/proposta.php?id=" . $projeto['id_proposta']); exit;

?>
