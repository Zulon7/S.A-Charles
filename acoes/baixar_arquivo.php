<?php

    session_start();

    if (!isset($_SESSION['id'])) {
        header("Location:../login.php"); exit;
    }

    require_once("db_con_init.php");
    require_once("permissoes.php");

    // Busca o arquivo pelo id da URL (baixar_arquivo.php?id=1)
    $sql = "SELECT id_proposta, id_projeto, nome_original, nome_salvo FROM arquivos WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$_GET['id'] ?? null]);
    $arquivo = $stmt->fetch(PDO::FETCH_ASSOC);

    // Anexo de proposta: qualquer membro da turma pode baixar
    // Arquivo de projeto: só o aluno do projeto e os professores da turma
    $tem_acesso = false;
    if ($arquivo && $arquivo['id_proposta']) {
        $tem_acesso = (bool) buscar_proposta_do_usuario($conexao, $_SESSION['id'], $arquivo['id_proposta']);
    } elseif ($arquivo && $arquivo['id_projeto']) {
        $tem_acesso = (bool) buscar_projeto_do_usuario($conexao, $_SESSION['id'], $arquivo['id_projeto']);
    }

    if (!$tem_acesso) {
        http_response_code(404);
        echo 'Arquivo não encontrado.';
        exit;
    }

    $caminho = __DIR__ . '/../uploads/' . $arquivo['nome_salvo'];

    if (!file_exists($caminho)) {
        http_response_code(404);
        echo 'Arquivo não encontrado.';
        exit;
    }

    // Envia o arquivo para o navegador baixar, com o nome original
    header('Content-Type: application/octet-stream');
    header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($arquivo['nome_original']));
    header('Content-Length: ' . filesize($caminho));
    readfile($caminho);
    exit;

?>
