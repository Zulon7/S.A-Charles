<?php include(__DIR__ . '/../header.php'); ?>

<?php
    require_once(__DIR__ . '/../acoes/db_con_init.php');

    // Professor vê as turmas que administra, aluno vê as turmas que participa
    if ($_SESSION['nivel'] == 'professor') {
        $tabela = 'user_modera';
        $titulo = 'Turmas que você administra';
    } else {
        $tabela = 'user_participa';
        $titulo = 'Suas turmas';
    }

    // Busca as turmas do usuário e quantos alunos cada uma tem
    $sql = "SELECT t.id, t.nome, t.data_criado,
                   (SELECT COUNT(*) FROM user_participa p WHERE p.id_turma = t.id) AS total_alunos
            FROM turmas t
            INNER JOIN $tabela u ON u.id_turma = t.id
            WHERE u.id_user = ?
            ORDER BY t.nome";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$_SESSION['id']]);
    $turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="turmas-titulo"><?= $titulo ?></h2>

<?php if (empty($turmas)): ?>
    <p class="turmas-vazio">Você ainda não está em nenhuma turma.</p>
<?php else: ?>
    <div class="turmas-lista">
        <?php foreach ($turmas as $turma): ?>
            <div class="turma-card">
                <h3><?= htmlspecialchars($turma['nome']) ?></h3>
                <p><i class="bi bi-people"></i> <?= $turma['total_alunos'] ?> aluno(s)</p>
                <p><i class="bi bi-calendar"></i> Criada em <?= date('d/m/Y', strtotime($turma['data_criado'])) ?></p>
            </div>
        <?php endforeach ?>
    </div>
<?php endif ?>

<?php include(__DIR__ . '/../footer.php'); ?>
