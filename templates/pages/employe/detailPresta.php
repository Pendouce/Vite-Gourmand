<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var object $prestation */?>
<?php /** @var string $csrfToken */ ?>
<?php /** @var string $role */ ?>


<div class="contenue-page">
  <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">

  <div class="blockRetour w-full mb-6">
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
  <div class="border border-primary/50 bg-primary/70 text-fond-carte rounded-2xl p-3 mb-4 text-lg font-medium w-fit">
    <a class="flex gap-2 items-center" href="/prestations">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
        <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
      </svg>
      <p>Prestations</p>
    </a>
  </div>

  <div class="flex flex-col justify-center items-center gap-8">
    <h2 class="font-font-h2 text-primary text-2xl font-medium lg:text-3xl text-center"><?= htmlspecialchars($prestation->getNomPresta()) ?></h2>
    <img
      src="<?= htmlspecialchars($prestation->getImgPresta()) ?>"
      alt="<?= htmlspecialchars($prestation->getNomPresta()) ?>"
      class="w-80 h-60 object-cover">
      <p class="font-semibold text-lg md:text-xl"><?= htmlspecialchars($prestation->getPrixPresta()) ?> €</p>

      <div class="grid grid-cols-1 md:grid-cols-3 w-full max-w-4xl gap-6">
        <div class="col-span-1 order-2 md:order-1 md:col-span-1">
          <div class="self-start p-4 flex flex-col gap-4">
            <h3 class="text-lg font-medium"><?= htmlspecialchars($prestation->getLibelle()) ?></h3>
            <div class="border border-primary rounded-sm p-4 flex justify-center items-center">
              <?php if(count($prestation->getContenuPresta()) > 1): ?>
                <ul class="list-disc marker:text-xs">
                  <?php foreach($prestation->getContenuPresta() as $contenuePresta): ?>
                    <li class="font-light"><?= htmlspecialchars($contenuePresta) ?></li>
                  <?php endforeach; ?>
                </ul>
              <?php else: ?>
                <?php foreach($prestation->getContenuPresta() as $contenuePresta): ?>
                  <p class="font-light text-center"><?= htmlspecialchars($contenuePresta) ?></p>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
          <?php if($prestation->isNecessiteRetour()): ?>
      <div class="flex justify-center">
        <p class="bg-primary/70 rounded-md p-2 text-md text-fond-carte text-center w-35">Necessite retour</p>
      </div>
      <?php endif; ?>
        </div>
        <div class="col-span-1 order-1 md:order-2 md:col-span-2 md:m-6">
          <div class="bg-fond-carte rounded-sm border border-primary/50 px-6 py-4">
          <p class="whitespace-pre-line"><?= htmlspecialchars($prestation->getDescriptionPresta()) ?></p>
          </div>
        </div>
      </div>

      

    <?php if($role === ROLE_ADMIN || $role === ROLE_EMPLOYE): ?>
        <div class="flex justify-between items-center gap-8">
        <label class="md:text-lg">Statut: </label>
        <label class="">
            <span class="toggleLabelText text-texte/60"><?= $prestation->isPrestationActif() == 1 ? 'Actif' : 'Inactif' ?></span>
            <input class="peer appearance-none togglePrestationInput" type="checkbox" name="prestation_actif" data-id="<?= $prestation->getPrestationId() ?>" <?= $prestation->isPrestationActif() == 1 ? 'checked' : '' ?>>
            <span class="toggle"></span>
        </label>
        </div>
        <div class="flex p-4 gap-8 ">

        <div>
            <a class="btn-form" href="/modifierPrestation?id=<?= $prestation->getPrestationId() ?>">Modifier</a>
        </div>
        <div>
            <form action="/supprimerPrestation" method="post">
            <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
            <input type="hidden" name="id" value="<?= $prestation->getPrestationId() ?>">
            <button class="btn-form bg-texte" type="submit">Supprimer</button>
            </form>
        </div>
        </div>
    <?php endif; ?>
</div>





<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>