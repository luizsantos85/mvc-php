<?php $render('header'); ?>

<main class="container">

    <?php $render('message_session'); ?>

    <form class="container__formulario" action="<?= $url($action); ?>" method="post">
        <h2 class="formulario__titulo"><?= $title ?? 'Efetuar login'; ?></h3>
            <div class="formulario__campo">
                <label class="campo__etiqueta" for="email">E-mail</label>
                <input name="email" class="campo__escrita"
                    placeholder="Digite seu E-mail" id='email' type="email" />
            </div>

            <div class="formulario__campo">
                <label class="campo__etiqueta" for="senha">Senha</label>
                <input type="password" name="senha" class="campo__escrita" placeholder="Digite sua senha" id='senha' />
            </div>

            <a href="<?= $url($urlA); ?>" style="color: blue; "><?= $textA ?? 'Não possui conta?'; ?></a>

            <input class="formulario__botao" type="submit" value="<?= $btnText ?? 'Entrar'; ?>" />
    </form>

</main>

<?= $render('footer'); ?>