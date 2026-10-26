<?php

    session_start();

    if (!isset($_SESSION['id'])) {
        header("Location:../login.php"); exit;
    }

    require_once("db_con_init.php");
    require_once("permissoes.php");

    // O id da proposta vem na URL: criar_projeto.php?proposta=1
    $proposta = buscar_proposta_do_usuario($conexao, $_SESSION['id'], $_GET['proposta'] ?? null);

    // Só alunos da turma podem enviar projetos
    if (!$proposta || !$proposta['e_aluno']) {
        header("Location:../paginas/propostas.php"); exit;
    }

    // Em caso de erro, volta para o formulário
    $voltar = "Location:../paginas/novo_projeto.php?proposta=" . $proposta['id'];

    // Cada aluno só pode ter um projeto por proposta
    $stmt = $conexao->prepare("SELECT id FROM projetos WHERE id_proposta = ? AND id_aluno = ?");
    $stmt->execute([$proposta['id'], $_SESSION['id']]);
    $existente = $stmt->fetchColumn();

    if ($existente) {
        header("Location:../paginas/projeto.php?id=" . $existente); exit;
    }

    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    if ($nome == '') {
        $_SESSION['erro'] = 'Dê um nome para o seu projeto.';
        header($voltar); exit;
    }

    $sql = "INSERT INTO projetos (id_proposta, id_aluno, nome, descricao) VALUES (?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([
        $proposta['id'],
        $_SESSION['id'],
        mb_substr($nome, 0, 150),
        $descricao == '' ? null : $descricao
    ]);

    // Vai para a página do novo projeto, onde o aluno envia os arquivos
    $_SESSION['sucesso'] = 'Projeto criado! Agora envie os arquivos abaixo.';
    header("Location:../paginas/projeto.php?id=" . $conexao->lastInsertId()); exit;

?>
