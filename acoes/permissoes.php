<?php

    // O usuário é professor (administra) desta turma?
    function e_professor_da_turma($conexao, $id_user, $id_turma) {
        $stmt = $conexao->prepare("SELECT 1 FROM user_modera WHERE id_user = ? AND id_turma = ?");
        $stmt->execute([$id_user, $id_turma]);
        return (bool) $stmt->fetch();
    }

    // O usuário é aluno (participa) desta turma?
    function e_aluno_da_turma($conexao, $id_user, $id_turma) {
        $stmt = $conexao->prepare("SELECT 1 FROM user_participa WHERE id_user = ? AND id_turma = ?");
        $stmt->execute([$id_user, $id_turma]);
        return (bool) $stmt->fetch();
    }

    // Busca uma proposta, mas só se o usuário participa ou administra a turma dela.
    // Retorna os dados da proposta (com o nome da turma e do professor) ou false.
    // 'e_professor' diz se o usuário é professor da turma (pode anexar arquivos).
    function buscar_proposta_do_usuario($conexao, $id_user, $id_proposta) {
        $sql = "SELECT pr.id, pr.nome, pr.descricao, pr.data_criado, pr.data_entrega,
                       t.id AS id_turma, t.nome AS nome_turma, u.nome AS nome_professor
                FROM propostas pr
                INNER JOIN turmas t ON t.id = pr.id_turma
                INNER JOIN users u ON u.id = pr.id_professor
                WHERE pr.id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([$id_proposta]);
        $proposta = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$proposta) return false;

        $proposta['e_professor'] = e_professor_da_turma($conexao, $id_user, $proposta['id_turma']);
        $proposta['e_aluno'] = e_aluno_da_turma($conexao, $id_user, $proposta['id_turma']);

        if (!$proposta['e_professor'] && !$proposta['e_aluno']) return false;

        return $proposta;
    }

    // Busca um projeto, mas só se o usuário é o aluno que fez ele
    // ou professor da turma da proposta. Retorna os dados ou false.
    // 'e_autor' diz se o usuário é o aluno do projeto (pode enviar arquivos).
    function buscar_projeto_do_usuario($conexao, $id_user, $id_projeto) {
        $sql = "SELECT pj.id, pj.id_aluno, pj.nome, pj.descricao, pj.data_criado,
                       a.nome AS nome_aluno,
                       pr.id AS id_proposta, pr.nome AS nome_proposta, pr.id_turma
                FROM projetos pj
                INNER JOIN propostas pr ON pr.id = pj.id_proposta
                INNER JOIN users a ON a.id = pj.id_aluno
                WHERE pj.id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([$id_projeto]);
        $projeto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$projeto) return false;

        $projeto['e_autor'] = ($projeto['id_aluno'] == $id_user);

        if (!$projeto['e_autor'] && !e_professor_da_turma($conexao, $id_user, $projeto['id_turma'])) return false;

        return $projeto;
    }

?>
