<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/liensCarte.php');?>

<?php /** @var array $plats */?>
<?php /** @var array $platsParType */?>


<div class="contenue-page">
  <?php foreach ($platsParType as $typeLibelle => $plats): ?>
  <section>
    <h2 class="font-h2 uppercase underline underline-offset-4 text-3xl text-center text-primary font-medium lg:text-4xl py-8 md:py-10"><?= $typeLibelle ?></h2>
    <div class="md:grid md:grid-cols-2 lg:grid lg:grid-cols-3">
      <?php foreach ($plats as $plat): ?>
        <div class="cart-plat flex justify-center items-center gap-4 col-span-1">
            <h3 class="text-xl font-medium lg:text-2xl text-center"><?= htmlspecialchars($plat->getTitre()) ?></h3>
            <img
              src="<?= htmlspecialchars($plat->getImagePlat()) ?>"
              alt="<?= htmlspecialchars($plat->getTitre()) ?>"
              class="w-40 h-40 object-cover">
            <p class="truncate w-full"><?= htmlspecialchars($plat->getDescriptionPlat()) ?></p>
            <p class=""><?= htmlspecialchars($plat->getPrixPersonne()) ?> €</p>

            <div class="flex flex-wrap gap-2 justify-center">
              <?php foreach ($plat->getAllergenes() as $allergene): ?>
                <div class="bg-primary/70 p-1 rounded-lg">
                    <p><?= htmlspecialchars($allergene->getLibelle()) ?></p>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
      <?php endforeach; ?>
    </div>
  </section>

<?php endforeach; ?>

</div>





<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>