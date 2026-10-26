<?php
    // Lista de arquivos com o formulário de envio. Usado em paginas/proposta.php e paginas/projeto.php
    // A página que inclui este arquivo define as variáveis abaixo antes do include.

    /** @var array  $arquivos     arquivos para listar (id, nome_original, tamanho, data_envio, nome_user) */
    /** @var bool   $pode_enviar  se mostra o formulário de envio */
    /** @var string $url_envio    para onde o formulário envia (acoes/enviar_arquivo.php?...) */
    /** @var string $base         vem do header.php */

    // (opcional) se mostra o botão de apagar em cada arquivo
    $pode_apagar = $pode_apagar ?? false;

    // Mostra o tamanho do arquivo em bytes, KB ou MB
    function formatar_tamanho($bytes) {
        if ($bytes < 1024) {
            return $bytes . ' bytes';
        }
        if ($bytes < 1024 * 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }
        return round($bytes / (1024 * 1024), 1) . ' MB';
    }
?>

<?php if ($pode_enviar): ?>
    <!-- enctype="multipart/form-data" é obrigatório para enviar arquivos -->
    <form class="upload-form" action="<?= $url_envio ?>" method="POST" enctype="multipart/form-data">
        <input type="file" name="arquivo" required>
        <button type="submit"><i class="bi bi-upload"></i> Enviar</button>
        <small>Máximo 10 MB</small>
    </form>
<?php endif ?>

<?php if (empty($arquivos)): ?>
    <p class="turmas-vazio">Nenhum arquivo enviado ainda.</p>
<?php else: ?>
    <ul class="membros-lista">
        <?php foreach ($arquivos as $arquivo): ?>
            <li>
                <i class="bi bi-file-earmark"></i>
                <a href="<?= $base ?>/acoes/baixar_arquivo.php?id=<?= $arquivo['id'] ?>"><?= htmlspecialchars($arquivo['nome_original']) ?></a>
                <small>
                    <?= formatar_tamanho($arquivo['tamanho']) ?>
                    · enviado por <?= htmlspecialchars($arquivo['nome_user']) ?>
                    em <?= date('d/m/Y H:i', strtotime($arquivo['data_envio'])) ?>
                </small>

                <?php if ($pode_apagar): ?>
                    <!-- Formulário POST com confirmação, para não apagar sem querer -->
                    <form class="form-apagar" action="<?= $base ?>/acoes/apagar_arquivo.php?id=<?= $arquivo['id'] ?>" method="POST"
                          onsubmit="return confirm('Apagar o arquivo <?= htmlspecialchars(addslashes($arquivo['nome_original'])) ?>?');">
                        <button type="submit" title="Apagar arquivo"><i class="bi bi-trash"></i></button>
                    </form>
                <?php endif ?>
            </li>
        <?php endforeach ?>
    </ul>
<?php endif ?>
