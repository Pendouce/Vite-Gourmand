<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var object $plat */?>
<?php /** @var array $platsParType */?>
<?php /** @var string $csrfToken */ ?>


<div class="contenue-page">
  <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">

  <div  class="blockRetour w-full pb-10 mb-6">
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
  <div class="border border-primary/50 bg-primary/70 text-fond-carte rounded-2xl p-4 mb-4 text-lg font-medium w-fit">
    <a class="flex gap-2 items-center" href="/plats">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
        <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
      </svg>
      <p>Plats</p>
    </a>
  </div>

  <div class="flex flex-col justify-center items-center gap-8">
    <h2 class="font-font-h2 text-primary text-2xl font-medium lg:text-3xl text-center"><?= htmlspecialchars($plat->getTitre()) ?></h2>
    <img
      src="<?= htmlspecialchars($plat->getImagePlat()) ?>"
      alt="<?= htmlspecialchars($plat->getTitre()) ?>"
      class="w-40 h-40 md:w-90 md:h-70 object-cover">
      <h3 class="text-xl md:text-2xl"><?= htmlspecialchars($plat->getLibelle()) ?></h3>
    <p class="w-full text-center text-lg md:text-xl"><?= htmlspecialchars($plat->getDescriptionPlat()) ?></p>
    <p class="font-semibold text-lg md:text-xl"><?= htmlspecialchars($plat->getPrixPersonne()) ?> €</p>
    <p class="md:text-xl">Stock : <?= htmlspecialchars($plat->getStockPlat()) ?></p>

    <div class="flex flex-wrap gap-3 justify-center">
      <?php foreach ($plat->getAllergenes() as $allergene): ?>
        <div class="bg-primary/70 py-1 px-2 rounded-lg md:text-xl">
            <p><?= htmlspecialchars($allergene->getLibelle()) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="flex justify-between items-center gap-8">
      <label class="md:text-xl">Statut: </label>
      <label class="pl-8">
        <span class="toggleLabelText"><?= $plat->isPlatActif() == 1 ? 'Actif' : 'Inactif' ?></span>
        <input class="peer appearance-none togglePlatInput" type="checkbox" name="plat_actif" data-id="<?= $plat->getPlatId() ?>" <?= $plat->isPlatActif() == 1 ? 'checked' : '' ?>>
        <span class="toggle"></span>
      </label>
    </div>
    <div class="p-6">
      <a class="btn-form" href="/modifierPlat?id=<?= $plat->getPlatId() ?>">Modifier</a>
    </div>
</div>





<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>