<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var array $plats */?>
<?php /** @var array $platsParType */?>
<?php /** @var array $typeDePlat */?>
<?php /** @var string $csrfToken */ ?>


<div class="contenue-page">
  <div id="modalContainer" class="fixed inset-0 flex justify-center items-center bg-black/50 hidden">
      <div id="modal" class="flex flex-col text-center justify-center items-center gap-6 bg-fond-carte w-xl h-fit p-6 rounded-2xl ">
        <h2 class="font-h2 text-primary text-xl">Types de plats</h2>
        <div class="flex flex-col justify-around gap-8">
          <?php foreach($typeDePlat as $type): ?>
            <div class="divTypeDePlat flex justify-between items-center" data-id="<?= $type->getTypeId() ?>">
              <p class="typeLibelle flex justify-center items-start"><?= $type->getLibelle()?></p>
              <div class="flex gap-8 items-end">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 btnModifTypeDePlat">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
  
                <div class="divBtnValideAnnule text-primary font-semibold hidden">
                  <button class="btnValideModifPlat" class="px-4" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 md:size-10">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                  </button>
                  <button class="btnAnnuleModifPlat" class="px-2" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 md:size-10">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
  
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 btnSupprime">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
  
                <div class="divSuppressionConfirm hidden">
                  <form action="/supprimerTypeDePlat" method="post">
                    <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
                    <input type="hidden" name="type_id" value="<?= $type->getTypeId() ?>">
                    <button class="btn bg-texte text-fond-carte" type="submit">Supprimer</button>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
          <div>
            <form class="flex justify-center items-center gap-4" action="/creeTypeDePlats" method="post">
              <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
              <input class="grand-input" type="text" name="libelle" placeholder="Ajouter un nouveau type de plat">
              <button class="btn-form" type="submit">Ajouter</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">
<?php require_once(APP_ROOT.'/templates/layouts/liensCarte.php');?>

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

  <div class="flex justify-between items-center">
    
    <div class="">
      <button id="btnGererTypePlat" class="flex justify-center border border-primary/50 bg-primary/80 text-fond-carte rounded-2xl py-2 px-4 md:py-4 md:px-6 text-lg font-medium w-fit" type="button">Gerer type de plat</button>
    </div>

    <div class="flex justify-center border border-primary/50 bg-primary/80 text-fond-carte rounded-2xl py-2 px-4 md:py-4 md:px-6 text-lg font-medium  w-fit">
      <a href="/creerPlat">Creer un plat</a>
    </div>
  </div>
  <?php foreach ($platsParType as $typeLibelle => $plats): ?>
  <section>
    <h2 class="font-h2 uppercase underline underline-offset-4 text-3xl text-center text-primary font-medium lg:text-4xl py-8 md:py-10"><?= $typeLibelle ?></h2>
    <div class="md:grid md:grid-cols-2 lg:grid lg:grid-cols-3">
      <?php foreach ($plats as $plat): ?>
        <div class="cart-plat flex justify-center items-center gap-4 col-span-1">
          <div class="blockRetour w-full mb-6"></div>
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
              <label>Statut: </label>
                <label class="pl-8">
                  <span class="toggleLabelText"><?= $plat->isPlatActif() == 1 ? 'Actif' : 'Inactif' ?></span>
                <input class="peer appearance-none togglePlatInput" type="checkbox" name="plat_actif" data-id="<?= $plat->getPlatId() ?>" <?= $plat->isPlatActif() == 1 ? 'checked' : '' ?>>
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