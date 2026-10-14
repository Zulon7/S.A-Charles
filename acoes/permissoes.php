<?php

    // Busca um projeto, mas só se o usuário participa ou administra a turma dele.
    // Retorna os dados do projeto (com o nome da turma) ou false se não tiver acesso.
    function buscar_projeto_do_usuario($conexao, $id_user, $id_projeto) {
        $sql = "SELECT p.id, p.nome, p.descricao, p.data_criado, p.data_entrega,
                       t.id AS id_turma, t.nome AS nome_turma
                FROM projetos p
                INNER JOIN turmas t ON t.id = p.id_turma
                WHERE p.id = ?
                  AND (t.id IN (SELECT id_turma FROM user_participa WHERE id_user = ?)
                    OR t.id IN (SELECT id_turma FROM user_modera   WHERE id_user = ?))";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([$id_projeto, $id_user, $id_user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

?>
