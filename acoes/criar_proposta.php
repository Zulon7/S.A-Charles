<?php

    session_start();

    if (!isset($_SESSION['id'])) {
        header("Location:../login.php"); exit;
    }

    require_once("db_con_init.php");
    require_once("permissoes.php");

    // O id da turma vem na URL: criar_proposta.php?turma=1
    $id_turma = $_GET['turma'] ?? null;

    // Só professores da turma podem criar propostas nela
    if (!e_professor_da_turma($conexao, $_SESSION['id'], $id_turma)) {
        header("Location:../paginas/turmas.php"); exit;
    }

    $voltar = "Location:../paginas/turma.php?id=" . (int) $id_turma;

    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $data_entrega = $_POST['data_entrega'] ?? '';

    if ($nome == '') {
        $_SESSION['erro'] = 'Dê um nome para a proposta.';
        header($voltar); exit;
    }

    // A data é opcional, mas se vier precisa ser válida (formato AAAA-MM-DD do <input type="date">)
    if ($data_entrega != '' && !DateTime::createFromFormat('Y-m-d', $data_entrega)) {
        $_SESSION['erro'] = 'Data de entrega inválida.';
        header($voltar); exit;
    }

    $sql = "INSERT INTO propostas (id_turma, id_professor, nome, descricao, data_entrega)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([
        $id_turma,
        $_SESSION['id'],
        mb_substr($nome, 0, 150),
        $descricao == '' ? null : $descricao,
        $data_entrega == '' ? null : $data_entrega
    ]);

    // Vai para a página da nova proposta, onde o professor pode anexar arquivos
    $_SESSION['sucesso'] = 'Proposta criada! Você pode anexar arquivos abaixo.';
    header("Location:../paginas/proposta.php?id=" . $conexao->lastInsertId()); exit;

?>
