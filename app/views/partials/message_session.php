<div style="width: 100%; margin: 0 auto; text-align: center;">
    <?php if ($hasFlash('message')): ?>
        <?php $flash = $getFlash('message'); ?>
        <div class="flash-message flash-<?= $flash['type'] ?>">
            <?= $flash['message'] ?>
        </div>
    <?php endif; ?>
</div>