<?php /** @var array $carteRole @var string $csrfToken */ ?>

<div class="w-full flex justify-around pb-8">
    <?php foreach($carteRole as $carte): ?>
      <div class="p-4">
        <a class="btn-form-dark <?= $carte['classeActive'] ?>" href="<?= $carte['url'] ?>"><?= $carte['label'] ?></a>
      </div>
    <?php endforeach; ?>
</div>

 