<?php $render('header'); ?>


<ul class="videos__container" alt="videos alura">
    
    <?php $render('message_session'); ?>

    <?php foreach ($listaVideos as $video): ?>
        <?php if (str_starts_with($video->url, 'http')): ?>
            <li class="videos__item">
                <?php if ($video->getFileName()): ?>
                    <a href="<?= $video->url; ?>">
                        <img src="<?= $asset('img/uploads/' . $video->getFileName()); ?>" alt="Imagem do vídeo" style="width: 100%;">
                    </a>
                <?php else: ?>
                    <iframe width="100%" height="72%" src="<?= $video->url; ?>"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                <?php endif; ?>
                <div class="descricao-video">
                    <img src="<?= $asset('img/logo.png'); ?>" alt="logo canal alura">
                    <h3><?= $video->title; ?></h3>
                    <div class="acoes-video">
                        <a href="formulario?id=<?= $video->id; ?>">Editar</a>
                        <!--caso queira passar por parametro -->
                        <a href="formulario/<?= $video->id; ?>">Editar</a>
                        <a href="excluir-capa?id=<?= $video->id; ?>">Excluir Capa</a>
                        <a href="excluir-video?id=<?= $video->id; ?>">Excluir Video</a>
                    </div>
                </div>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>

<?php $render('footer'); ?>