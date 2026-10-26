<?php

    session_start();

    if (!isset($_SESSION['id'])) {
        header("Location:../login.php"); exit;
    }

    require_once("db_con_init.php");
    require_once("permissoes.php");

    // Tamanho máximo de cada arquivo: 10 MB
    $tamanho_maximo = 10 * 1024 * 1024;

    // O destino vem na URL, assim ele não se perde mesmo quando o arquivo
    // é grande demais e o PHP descarta o $_POST:
    //   enviar_arquivo.php?proposta=1  -> anexo do professor na proposta
    //   enviar_arquivo.php?projeto=1   -> arquivo do aluno no projeto
    $id_proposta = null;
    $id_projeto = null;

    if (isset($_GET['proposta'])) {
        $proposta = buscar_proposta_do_usuario($conexao, $_SESSION['id'], $_GET['proposta']);

        // Só professores da turma anexam arquivos na proposta
        if (!$proposta || !$proposta['e_professor']) {
            header("Location:../paginas/propostas.php"); exit;
        }

        $id_proposta = $proposta['id'];
        $voltar = "Location:../paginas/proposta.php?id=" . $id_proposta;
    } else {
        $projeto = buscar_projeto_do_usuario($conexao, $_SESSION['id'], $_GET['projeto'] ?? null);

        // Só o aluno dono do projeto envia arquivos nele
        if (!$projeto || !$projeto['e_autor']) {
            header("Location:../paginas/propostas.php"); exit;
        }

        $id_projeto = $projeto['id'];
        $voltar = "Location:../paginas/projeto.php?id=" . $id_projeto;
    }

    if (!isset($_FILES['arquivo'])) {
        // Se o envio passar do post_max_size do php.ini (40 MB no XAMPP),
        // o PHP descarta tudo e $_FILES e $_POST chegam vazios
        if (empty($_POST) && ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
            $_SESSION['erro'] = 'Arquivo grande demais (máximo 10 MB).';
        } else {
            $_SESSION['erro'] = 'Escolha um arquivo para enviar.';
        }
        header($voltar); exit;
    }

    $arquivo = $_FILES['arquivo'];

    if ($arquivo['error'] == UPLOAD_ERR_NO_FILE) {
        $_SESSION['erro'] = 'Escolha um arquivo para enviar.';
        header($voltar); exit;
    }

    if ($arquivo['error'] == UPLOAD_ERR_INI_SIZE || $arquivo['size'] > $tamanho_maximo) {
        $_SESSION['erro'] = 'Arquivo grande demais (máximo 10 MB).';
        header($voltar); exit;
    }

    if ($arquivo['error'] != UPLOAD_ERR_OK) {
        $_SESSION['erro'] = 'Erro ao enviar o arquivo. Tente de novo.';
        header($voltar); exit;
    }

    // Salva com um nome aleatório, para não ter dois arquivos com o mesmo nome
    // e para ninguém conseguir adivinhar o nome de um arquivo
    $nome_original = mb_substr(basename($arquivo['name']), 0, 255);
    $nome_salvo = bin2hex(random_bytes(16));

    if (!move_uploaded_file($arquivo['tmp_name'], __DIR__ . '/../uploads/' . $nome_salvo)) {
        $_SESSION['erro'] = 'Não foi possível salvar o arquivo.';
        header($voltar); exit;
    }

    $sql = "INSERT INTO arquivos (id_proposta, id_projeto, id_user, nome_original, nome_salvo, tamanho)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$id_proposta, $id_projeto, $_SESSION['id'], $nome_original, $nome_salvo, $arquivo['size']]);

    $_SESSION['sucesso'] = 'Arquivo enviado!';
    header($voltar); exit;

?>
