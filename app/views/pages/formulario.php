<?php $render('header'); ?>

<main class="container">
    <?php if ($hasFlash('message')): ?>
        <?php $flash = $getFlash('message'); ?>
        <div class="flash-message flash-<?= $flash['type'] ?>">
            <?= $flash['message'] ?>
        </div>
    <?php endif; ?>

    <form class="container__formulario"
        action="<?= isset($video) && !empty($video->id) ? $url('editar-video') : $url('novo-video') ?>"
        method="post" enctype="multipart/form-data">
        <h2 class="formulario__titulo">Envie um vídeo!</h3>
            <div class="formulario__campo">
                <label class="campo__etiqueta" for="url">Link embed</label>
                <input name="url" class="campo__escrita"
                    placeholder="Por exemplo: https://www.youtube.com/embed/FAY1K2aUg5g"
                    id='url'
                    required
                    value="<?= $video?->url; ?>" />
            </div>

            <div class="formulario__campo">
                <label class="campo__etiqueta" for="title">Título do vídeo</label>
                <input name="title" class="campo__escrita" placeholder="Neste campo, dê o nome do vídeo"
                    id='title' value="<?= $video?->title; ?>" required />
                <input type="hidden" name="id_video" value="<?= $video?->id; ?>">
            </div>

            <div class="formulario__campo">
                <label class="campo__etiqueta" for="image">Imagem:</label>
                <input type="file" name="image" class="campo__escrita" id='image' accept="image/*" />

                <?php if ($video?->getFileName()): ?>
                    <input type="hidden" name="old_image" value="<?= $video->getFileName(); ?>">
                <?php endif; ?>
            </div>



            <input class="formulario__botao" type="submit" value="Enviar" />
    </form>
</main>

<?php $render('footer'); ?>