<?php

    // Envia o projeto (ele fica público e aparece no feed),
    // ou cancela o envio (volta a ser rascunho, que só o aluno vê)

    session_start();

    if (!isset($_SESSION['id'])) {
        header("Location:../login.php"); exit;
    }

    // Só por POST (pelo botão do formulário), nunca por um link
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location:../paginas/projetos.php"); exit;
    }

    require_once("db_con_init.php");
    require_once("permissoes.php");

    // O id do projeto vem na URL: enviar_projeto.php?id=1
    $projeto = buscar_projeto_do_usuario($conexao, $_SESSION['id'], $_GET['id'] ?? null);

    // Só o aluno dono do projeto pode enviar ou cancelar o envio
    if (!$projeto || !$projeto['e_autor']) {
        header("Location:../paginas/projetos.php"); exit;
    }

    $voltar = "Location:../paginas/projeto.php?id=" . $projeto['id'];

    // Depois do prazo de entrega não dá para enviar nem cancelar o envio
    if (!$projeto['pode_alterar']) {
        $_SESSION['erro'] = 'O prazo de entrega já passou. O projeto não pode mais ser alterado.';
        header($voltar); exit;
    }

    // O botão manda acao=enviar ou acao=cancelar
    if (($_POST['acao'] ?? '') == 'cancelar') {
        $stmt = $conexao->prepare("UPDATE projetos SET data_enviado = NULL WHERE id = ?");
        $stmt->execute([$projeto['id']]);
        $_SESSION['sucesso'] = 'Envio cancelado. O projeto voltou a ser rascunho.';
    } else {
        // Guarda a data do envio (horário de Brasília, definido em db_con_init.php)
        $stmt = $conexao->prepare("UPDATE projetos SET data_enviado = ? WHERE id = ?");
        $stmt->execute([date('Y-m-d H:i:s'), $projeto['id']]);
        $_SESSION['sucesso'] = 'Projeto enviado! Agora ele está público no feed.';
    }

    header($voltar); exit;

?>
