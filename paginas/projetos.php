<?php include(__DIR__ . '/../header.php'); ?>

<?php
    require_once(__DIR__ . '/../acoes/db_con_init.php');

    // Aluno: os projetos que ele criou
    // Professor: os projetos enviados para propostas das turmas que ele administra
    // (a mesma consulta serve para os dois, com o OR)
    $sql = "SELECT pj.id, pj.nome, pj.descricao, pj.data_criado,
                   pr.nome AS nome_proposta, t.nome AS nome_turma, u.nome AS nome_aluno,
                   (SELECT COUNT(*) FROM arquivos a WHERE a.id_projeto = pj.id) AS total_arquivos
            FROM projetos pj
            INNER JOIN propostas pr ON pr.id = pj.id_proposta
            INNER JOIN turmas t ON t.id = pr.id_turma
            INNER JOIN users u ON u.id = pj.id_aluno
            WHERE pj.id_aluno = ?
               OR pr.id_turma IN (SELECT id_turma FROM user_modera WHERE id_user = ?)
            ORDER BY pj.data_criado DESC";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$_SESSION['id'], $_SESSION['id']]);
    $projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $e_professor = ($_SESSION['nivel'] == 'professor');
?>

<h2 class="titulo"><?= $e_professor ? 'Projetos dos seus alunos' : 'Seus projetos' ?></h2>

<?php if (empty($projetos)): ?>
    <?php if ($e_professor): ?>
        <p class="turmas-vazio">Nenhum aluno enviou projeto ainda.</p>
    <?php else: ?>
        <p class="turmas-vazio">
            Você ainda não criou nenhum projeto.
            Veja as <a href="<?= $base ?>/paginas/propostas.php">propostas</a> para começar.
        </p>
    <?php endif ?>
<?php else: ?>
    <div class="turmas-lista">
        <?php foreach ($projetos as $projeto): ?>
            <?php include(__DIR__ . '/../projeto_card.php'); ?>
        <?php endforeach ?>
    </div>
<?php endif ?>

<?php include(__DIR__ . '/../footer.php'); ?>
