<?php include(__DIR__ . '/../header.php'); ?>

<?php
    require_once(__DIR__ . '/../acoes/db_con_init.php');

    // Busca as propostas de todas as turmas que o usuário participa ou administra.
    // total_projetos: quantos projetos a proposta recebeu (para o professor)
    // id_meu_projeto: o projeto que o usuário enviou, se enviou (para o aluno)
    // Propostas sem data de entrega ficam no final da lista
    $sql = "SELECT pr.id, pr.nome, pr.descricao, pr.data_entrega, t.nome AS nome_turma,
                   (SELECT COUNT(*) FROM projetos pj WHERE pj.id_proposta = pr.id) AS total_projetos,
                   (SELECT pj.id FROM projetos pj WHERE pj.id_proposta = pr.id AND pj.id_aluno = ?) AS id_meu_projeto
            FROM propostas pr
            INNER JOIN turmas t ON t.id = pr.id_turma
            WHERE t.id IN (SELECT id_turma FROM user_participa WHERE id_user = ?)
               OR t.id IN (SELECT id_turma FROM user_modera   WHERE id_user = ?)
            ORDER BY pr.data_entrega IS NULL, pr.data_entrega";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$_SESSION['id'], $_SESSION['id'], $_SESSION['id']]);
    $propostas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="titulo">Propostas</h2>

<?php if (empty($propostas)): ?>
    <p class="turmas-vazio">Nenhuma proposta nas suas turmas ainda.</p>
<?php else: ?>
    <div class="turmas-lista">
        <?php foreach ($propostas as $proposta): ?>
            <?php include(__DIR__ . '/../proposta_card.php'); ?>
        <?php endforeach ?>
    </div>
<?php endif ?>

<?php include(__DIR__ . '/../footer.php'); ?>
