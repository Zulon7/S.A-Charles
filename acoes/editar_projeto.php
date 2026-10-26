<?php

    session_start();

    if (!isset($_SESSION['id'])) {
        header("Location:../login.php"); exit;
    }

    require_once("db_con_init.php");
    require_once("permissoes.php");

    // O id do projeto vem na URL: editar_projeto.php?id=1
    $projeto = buscar_projeto_do_usuario($conexao, $_SESSION['id'], $_GET['id'] ?? null);

    // Só o aluno dono do projeto pode editar
    if (!$projeto || !$projeto['e_autor']) {
        header("Location:../paginas/projetos.php"); exit;
    }

    // Depois do prazo de entrega o projeto não pode mais ser alterado
    if (!$projeto['pode_alterar']) {
        $_SESSION['erro'] = 'O prazo de entrega já passou. O projeto não pode mais ser alterado.';
        header("Location:../paginas/projeto.php?id=" . $projeto['id']); exit;
    }

    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    if ($nome == '') {
        $_SESSION['erro'] = 'Dê um nome para o seu projeto.';
        header("Location:../paginas/editar_projeto.php?id=" . $projeto['id']); exit;
    }

    $sql = "UPDATE projetos SET nome = ?, descricao = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([
        mb_substr($nome, 0, 150),
        $descricao == '' ? null : $descricao,
        $projeto['id']
    ]);

    $_SESSION['sucesso'] = 'Projeto atualizado!';
    header("Location:../paginas/projeto.php?id=" . $projeto['id']); exit;

?>
