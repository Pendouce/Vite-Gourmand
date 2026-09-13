<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var array $plats */?>
<?php /** @var array $platsParType */?>
<?php /** @var string $csrfToken */ ?>


<div class="contenue-page">
  <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">
<?php require_once(APP_ROOT.'/templates/layouts/liensCarte.php');?>

  <div  class="w-lg pb-10 mb-6">
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

  <div class="flex justify-center items-end border border-primary/50 bg-primary/80 text-fond-carte rounded-2xl py-2 px-4 md:py-4 md:px-6 text-lg font-medium ml-auto w-fit">
    <a href="/creerPlat">Creer un plat</a>
  </div>
  <?php foreach ($platsParType as $typeLibelle => $plats): ?>
  <section>
    <h2 class="font-h2 uppercase underline underline-offset-4 text-3xl text-center text-primary font-medium lg:text-4xl py-8 md:py-10"><?= $typeLibelle ?></h2>
    <div class="md:grid md:grid-cols-2 lg:grid lg:grid-cols-3">
      <?php foreach ($plats as $plat): ?>
        <div class="cart-plat flex justify-center items-center gap-4 col-span-1">
          <div id="blockRetour" class="w-full mb-6"></div>
            <h3 class="text-xl font-medium lg:text-2xl text-center"><?= htmlspecialchars($plat->getTitre()) ?></h3>
            <img
              src="<?= htmlspecialchars($plat->getImagePlat()) ?>"
              alt="<?= htmlspecialchars($plat->getTitre()) ?>"
              class="w-40 h-40 object-cover">
            <p class="truncate w-full text-center"><?= htmlspecialchars($plat->getDescriptionPlat()) ?></p>
            <p class="font-semibold text-lg"><?= htmlspecialchars($plat->getPrixPersonne()) ?> €</p>
            <p>Stock : <?= htmlspecialchars($plat->getStockPlat()) ?></p>

            <div class="flex flex-wrap gap-3 justify-center">
              <?php foreach ($plat->getAllergenes() as $allergene): ?>
                <div class="bg-primary/70 py-1 px-2 rounded-lg">
                    <p><?= htmlspecialchars($allergene->getLibelle()) ?></p>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="flex justify-between items-center gap-8">
              <a class="btn-detail mt-4" href="/detailPlat?id=<?= $plat->getPlatId() ?>">Details</a>
              <label class="pl-8">
                Actif
                <input class="peer appearance-none" type="checkbox" name="plat_actif" data-id="<?= $plat->getPlatId() ?>" <?= $plat->isPlatActif() == 1 ? 'checked' : '' ?>>
                <span class="toggle"></span>
              </label>
            </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

<?php endforeach; ?>

</div>





<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>