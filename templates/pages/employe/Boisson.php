
<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var array $boissons */?>
<?php /** @var string $csrfToken */ ?>


<div class="contenue-page">
<?php require_once(APP_ROOT.'/templates/layouts/liensCarte.php');?>

  <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">

  <div  class="w-full pb-10 mb-6">
    <?php if (isset($_SESSION['succes'])): ?>
      <p class="succes">
          <?= $_SESSION['succes'] ?>
      </p>
      <?php unset($_SESSION['succes']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['erreur'])): ?>
      <p class="erreur"><?= $_SESSION['erreur'] ?></p>
      <?php unset($_SESSION['erreur']); ?>
    <?php endif; ?>
    </div>

  <div class="flex justify-end items-center">
    <div class="flex justify-end border border-primary/50 bg-primary/80 text-fond-carte rounded-2xl py-2 px-4 md:py-4 md:px-6 text-lg font-medium  w-fit">
      <a href="/creerBoisson">Nouvelle boisson</a>
    </div>
  </div>

    <div class="md:grid md:grid-cols-2 lg:grid lg:grid-cols-3">
      <?php foreach ($boissons as $boisson): ?>
        <div class="cart-plat flex justify-center items-center gap-4 col-span-1">
          <div class="blockRetour w-full mb-6"></div>
            <h3 class="text-xl font-medium lg:text-2xl text-center"><?= htmlspecialchars($boisson->getNomBoisson()) ?></h3>
            <img
              src="<?= htmlspecialchars($boisson->getPhotoBoisson()) ?>"
              alt="photo <?= htmlspecialchars($boisson->getNomBoisson()) ?>"
              class="w-40 h-40 object-cover">
            <p class="truncate w-full text-center"><?= htmlspecialchars($boisson->getDescriptionBoisson()) ?></p>
            <p class="font-semibold text-lg"><?= htmlspecialchars($boisson->getPrixBoisson()) ?> €</p>
              
            <p>Stock : <?= htmlspecialchars($boisson->getStockBoisson()) ?></p>
            <?php if($boisson->isAlcool() == 1): ?>
              <p class="text-center">Avec alcool</p>
            <?php else: ?>
              <p class="text-center">Sans alcool</p>
            <?php endif; ?>

            <div class="flex justify-between items-center gap-8">
              <a class="btn-detail mt-4" href="/detailBoisson?id=<?= $boisson->getBoissonId() ?>">Details</a>
              <label class="pl-6">Statut: </label>
                <label>
                  <span class="toggleLabelText"><?= $boisson->isBoissonActif() == 1 ? 'Actif' : 'Inactif' ?></span>
                <input class="peer appearance-none toggleBoissonInput" type="checkbox" name="boisson_actif" data-id="<?= $boisson->getBoissonId() ?>" <?= $boisson->isBoissonActif() == 1 ? 'checked' : '' ?>>
                <span class="toggle"></span>
              </label>
            </div>
        </div>
      <?php endforeach; ?>
    </div>

</div>





<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>