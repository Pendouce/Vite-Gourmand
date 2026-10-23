
<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var array $prestations */?>
<?php /** @var array $prestationParType */?>
<?php /** @var array $typeDePresta */?>
<?php /** @var object $role */?>
<?php /** @var string $csrfToken */ ?>


<div class="contenue-page">
  <div id="modalContainer" class="fixed inset-0 z-50 flex justify-center items-center bg-black/50 p-4 hidden">
      <div id="modal" class="flex flex-col text-center justify-center items-center gap-6 bg-fond-carte w-full max-w-xl h-fit p-4 md:p-6 rounded-2xl ">
        <h2 class="font-h2 text-primary text-xl">Types de prestation</h2>
        <div id="listeTypes" class="flex flex-col justify-around gap-8" data-route="/modifierTypeDePresta" data-nom-id="id">
          <?php foreach($typeDePresta as $type): ?>
            <div class="divType flex justify-between items-center" data-id="<?= $type->getTypePrestaId() ?>">
              <p class="typeLibelle flex justify-center items-start"><?= $type->getLibelle()?></p>
              <div class="flex gap-8 items-end">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 btnModifType">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
  
                <div class="divBtnValideAnnule flex items-center gap-2 text-primary font-semibold hidden">
                  <button class="btnValideModif px-2" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 md:size-10">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                  </button>
                  <button class="btnAnnuleModif px-2" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 md:size-10">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
  
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 btnSupprime">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
  
                <div class="divSuppressionConfirm hidden">
                  <form action="/supprimerTypeDePresta" method="post">
                    <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
                    <input type="hidden" name="id" value="<?= $type->getTypePrestaId() ?>">
                    <button class="btn bg-texte text-fond-carte" type="submit">Supprimer</button>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
          <div>
            <form class="flex flex-wrap justify-center items-center gap-2 md:gap-4" action="/creerTypeDePresta" method="post">
              <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
              <input class="grand-input" type="text" name="libelle" placeholder="Ajouter un nouveau type de prestation">
              <button class="btn-form" type="submit">Ajouter</button>
            </form>
          </div>
        </div>
      </div>
    </div>

  <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">

  <div  class="blockRetour w-full py-2 mb-2">
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

  <?php if($role === ROLE_ADMIN || $role === ROLE_EMPLOYE): ?>

    <div class="flex justify-between items-center gap-3">

      <div>
        <button id="btnGererTypePresta" class="flex justify-center border border-primary/50 bg-primary/80 text-fond-carte rounded-2xl py-2 px-4 md:py-4 md:px-6 text-lg font-medium w-fit" type="button">Gerer les types</button>
      </div>
      

      <div class="flex justify-center border border-primary/50 bg-primary/80 text-fond-carte rounded-2xl py-2 px-4 md:py-4 md:px-6 text-lg font-medium w-fit">
        <a href="/creerPrestation">Nouvelle prestation</a>
      </div>
    </div>
  <?php endif; ?>

  <p class="text-center text-lg py-8">Completez votre evenement avec nos services professionels et notre location de materiel.</p>
 
    <?php foreach($prestationParType as $typeDePrestaLibelle => $prestations): ?>
      <section class="py-8">
        <h2 class="font-h2 uppercase underline underline-offset-4 text-3xl text-center text-primary font-medium lg:text-4xl py-8 md:py-10"><?= htmlspecialchars($typeDePrestaLibelle) ?></h2>
        <div class="grid grid-cols-2 gap-4 sm:gap-0">
          <?php foreach($prestations as $prestation): ?>
          <?php if(count($prestation->getContenuPresta()) > 1): ?>
            <div class="cart-presta-sm col-span-1 flex flex-col gap-4">
              <div class="blockRetour w-full mb-1"></div>
              <div class="flex flex-col gap-2">
                <img class="w-15 object-cover self-center" src="<?= htmlspecialchars($prestation->getImgPresta()) ?>" alt="">
                <h4 class="text-lg"><?= htmlspecialchars($prestation->getNomPresta()) ?></h4>
              </div>
              <div>
                <ul class="list-disc marker:text-xs">
                  <?php foreach($prestation->getContenuPresta() as $contenuePresta): ?>
                    <li class="font-light"><?= htmlspecialchars($contenuePresta) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
              <div class="flex flex-col items-center gap-4">
                <p class="text-primary">A partir de <?= htmlspecialchars($prestation->getPrixPresta()) ?>€</p>
                <a class="btn-detail py-1 w-fit" href="/detailPrestation?id=<?= $prestation->getPrestationId() ?>">Details</a>
              </div>

              <?php if($role === ROLE_ADMIN || $role === ROLE_EMPLOYE): ?>
              <div class="self-end">
                <label class="text-sm">Statut: </label>
                  <label class="flex gap-1">
                    <span class="toggleLabelText text-texte/60 text-xs"><?= $prestation->isPrestationActif() == 1 ? 'Actif' : 'Inactif' ?></span>
                  <input class="peer appearance-none togglePrestationInput" type="checkbox" name="prestation_actif" data-id="<?= $prestation->getPrestationId() ?>" <?= $prestation->isPrestationActif() == 1 ? 'checked' : '' ?>>
                  <span class="toggle"></span>
                </label>
              </div>
            <?php endif; ?>
            </div>
          <?php else: ?>
            <div class="cart-presta col-span-full gap-2 relative">
              <div class="blockRetour w-full mb-1"></div>
              <div class="flex flex-col gap-2">
                <img class="w-15 object-cover self-center bg-transparent" src="<?= htmlspecialchars($prestation->getImgPresta()) ?>" alt="">
                <h4 class="text-lg"><?= htmlspecialchars($prestation->getNomPresta()) ?></h4>
              </div>
              <div>
                <?php foreach($prestation->getContenuPresta() as $contenuePresta): ?>
                  <p class="font-light text-center"><?= htmlspecialchars($contenuePresta) ?></p>
                <?php endforeach; ?>
              </div>

              <div class="flex flex-col items-center gap-4">
                <p class="text-primary">A partir de <?= htmlspecialchars($prestation->getPrixPresta()) ?>€</p>
                <a class="btn-detail py-1 w-fit" href="/detailPrestation?id=<?= $prestation->getPrestationId() ?>">Details</a>
              </div>

              <?php if($role === ROLE_ADMIN || $role === ROLE_EMPLOYE): ?>
                <div class="absolute bottom-3 right-4">
                  <label class="text-sm">Statut: </label>
                    <label class="flex gap-1">
                      <span class="toggleLabelText text-texte/60 text-xs"><?= $prestation->isPrestationActif() == 1 ? 'Actif' : 'Inactif' ?></span>
                    <input class="peer appearance-none togglePrestationInput" type="checkbox" name="prestation_actif" data-id="<?= $prestation->getPrestationId() ?>" <?= $prestation->isPrestationActif() == 1 ? 'checked' : '' ?>>
                    <span class="toggle"></span>
                  </label>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
          </div>
      </section>
    <?php endforeach; ?>

</div>





<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>