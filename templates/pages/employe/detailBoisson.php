<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var object $boisson */?>
<?php /** @var string $csrfToken */ ?>


<div class="contenue-page">
  <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">

  <div class="blockRetour w-full  mb-6">
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
    <a class="flex gap-2 items-center" href="/boisson">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
        <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
      </svg>
      Boisson
    </a>
  </div>

  <div class="conteneurDetailBoisson flex flex-col justify-center items-center gap-8">
    <h2 class="font-font-h2 text-primary text-2xl font-medium lg:text-3xl text-center"><?= htmlspecialchars($boisson->getNomBoisson()) ?></h2>
    <img
      src="<?= htmlspecialchars($boisson->getPhotoBoisson()) ?>"
      alt="<?= htmlspecialchars($boisson->getNomBoisson()) ?>"
      class="w-40 h-40 md:w-90 md:h-70 object-cover">
    <p class="w-full text-center text-lg md:text-xl"><?= htmlspecialchars($boisson->getDescriptionBoisson()) ?></p>
    <p class="font-semibold text-lg md:text-xl"><?= htmlspecialchars($boisson->getPrixBoisson()) ?> €</p>
    <div class="flex items-center justify-center gap-6">
      <div class="flex justify-center items-center gap-2">
        <p class="md:text-xl">Stock :</p>
        <p id="nbStock" class="font-medium md:text-xl"> <?= htmlspecialchars($boisson->getStockBoisson()) ?></p>
      </div>
      <div class="flex gap-4 font-semibold">
        <button id="btnModifStock" class="text-primary font-semibold" type="button">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 md:size-10">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
          </svg>
        </button>
        <div id="divBtnValideAnnule" class="text-primary font-semibold hidden">
          <button id="btnValideModifStock" class="px-4" type="button" data-id="<?= $boisson->getboissonId() ?>">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 md:size-10">
              <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
          </button>
          <button id="btnAnnuleModifStock" class="px-2" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 md:size-10">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <div class="flex flex-wrap gap-3 justify-center">
      <?php if($boisson->isAlcool() == 1): ?>
        <p class="bg-primary/70 py-1 px-2 rounded-lg md:text-xl">Avec alcool</p>
      <?php else: ?>
        <p class="bg-primary/70 py-1 px-2 rounded-lg md:text-xl">Sans alcool</p>
      <?php endif; ?>
    </div>
    <div class="flex justify-between items-center gap-8">
      <label class="md:text-xl">Statut: </label>
      <label class="pl-8">
        <span class="toggleLabelText"><?= $boisson->isboissonActif() == 1 ? 'Actif' : 'Inactif' ?></span>
        <input class="peer appearance-none toggleBoissonInput" type="checkbox" name="boisson_actif" data-id="<?= $boisson->getboissonId() ?>" <?= $boisson->isboissonActif() == 1 ? 'checked' : '' ?>>
        <span class="toggle"></span>
      </label>
    </div>
    <div class="flex p-4 gap-8 ">

      <div>
        <a class="btn-form" href="/modifierBoisson?id=<?= $boisson->getboissonId() ?>">Modifier</a>
      </div>
      <div>
        <form action="/supprimerBoisson" method="post">
          <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
          <input type="hidden" name="id" value="<?= $boisson->getboissonId() ?>">
          <button class="btn-form bg-texte" type="submit">Supprimer</button>
        </form>
      </div>
    </div>
</div>





<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>