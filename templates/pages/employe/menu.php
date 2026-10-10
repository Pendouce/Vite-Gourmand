<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var array $menus */?>
<?php /** @var array $evenements */?>
<?php /** @var array $themes */?>
<?php /** @var array $regimes */?>
<?php /** @var int $role */?>
<?php /** @var float $prixMin */?>
<?php /** @var float $prixMax */?>
<?php /** @var string $csrfToken */?>

<div class="contenue-page">
  <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">
  <?php require_once(APP_ROOT.'/templates/layouts/liensCarte.php');?>

  <div class="w-full mb-6">
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

  <!-- Filtre -->
   <div id="divFiltre" class="side-bar-filter hidden">
    <div class="flex justify-between items-center">
      <div>
        <button id="btnReinitialiser" class="bg-primary text-fond-carte text-sm p-2 rounded-2xl hover:bg-texte/90" type="button">Reinitialiser</button> 
      </div>
      <h3 class="text-center p-6 font-medium text-xl lg:text-2xl">Filtres</h3>
      <button id="btnFermerFiltre" type="button" class="py-2 self-start hover:text-primary">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 lg:size-8">
          <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
        </svg>
      </button>
    </div>

    <form id="formMenuFiltre" action="/menuFiltre" method="get">
      <fieldset>
        <legend class="text-lg lg:text-xl pb-6">Type d’evenement</legend>
        <div class=" grid grid-cols-3 auto-rows-auto gap-4 pl-4 self-center-center">
          <?php foreach($evenements as $evenement): ?>
            <div class="col-span-1 self-center justify-self-start">
              <label class="flex items-center gap-2">
              <input class="evenements size-4 accent-primary" type="checkbox" name="evenement_id[]" value="<?= $evenement->getEvenementId() ?>">
              <?= $evenement->getLibelle() ?>
              </label>
            </div>
          <?php endforeach; ?>
        </div>
      </fieldset>

      <div class="flex flex-col self-start w-full my-6">
        <label class="text-lg lg:text-xl py-6">Prix maximum</label>
            

            <!-- <div class="w-full relative mx-auto flex items-center"> -->
              <div class="pl-4 w-full relative mx-auto flex items-center justify-center py-2 my-6">
              <div id="rangeValueDiv" class=" absolute  -top-11 left-1/2 -translate-x-1/2 text-primary text-sm py-2 ">
                <span id="rangeValue"><?= $prixMin ?></span>
              </div>
              <input class="range-slider absolute" id="sliderPrixInput" type="range" min="<?= $prixMin ?>" max="<?= ceil($prixMax )?>" name="prix_personne" value="<?= $prixMin ?>">
            </div> 

      </div>

      <fieldset>
        <legend class="text-lg lg:text-xl pb-6">Themes</legend>
        <div class=" grid grid-cols-2 auto-rows-auto gap-4 pl-4 self-center-center">
          <?php foreach($themes as $theme): ?>
            <div class="col-span-1">
              <label class="flex items-center gap-2">
                <input class="themes size-4 accent-primary" type="checkbox" name="theme_id[]" value="<?= $theme->getThemeId() ?>">
                <?= $theme->getLibelle() ?></label>
            </div>
          <?php endforeach; ?>
        </div>
      </fieldset>

      <fieldset>
        <legend class="text-lg lg:text-xl pb-6">Regimes</legend>
          <div class="flex flex-wrap gap-6 pl-4">
            <?php foreach($regimes as $regime): ?>
              <label class="cart-check px-4 has-checked:bg-primary/50 " for="regime-<?= $regime->getRegimeId() ?>"><?= htmlspecialchars($regime->getLibelle()) ?>
                <input class="regimes justify-self-center appearance-none" type="checkbox" name="regime_id[]" id="regime-<?= $regime->getRegimeId() ?>" value="<?= $regime->getRegimeId() ?>">
              </label>
            <?php endforeach; ?>
          </div>
      </fieldset>

      <div class="w-1/2 flex flex-col self-start text-center">
        <label class="text-lg lg:text-xl py-6">Nombre de personne minimum</label>
        <div class="flex items-center pl-4">
          <button id="btnMoin" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 lg:size-8 text-primary">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
          </button>
          <input id="nbPersonneMin" name="nombre_personne_min" class="grand-input w-20 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" min="1" step="1" type="number" name="nombre_personne_min">
          <button id="btnPlus" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 lg:size-8 text-primary">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
          </button>
        </div>
      </div>
    </form>

   </div>

   <!-- Btn filtre / creation menu -->
  <div class="flex justify-between items-center">
    <div class="flex flex-col items-center">
      <div id="divNbFiltreSelectione" class="self-end hidden">
        <div class="bg-texte rounded-full w-4 h-4 flex items-center justify-center">
          <span id="nbFiltreSelectione" class="text-xs text-fond-carte"></span>
        </div>
      </div>
      <button id="btnFiltre" class="hover:text-primary" type="button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8 lg:size-10">
          <path d="M18.75 12.75h1.5a.75.75 0 0 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5ZM12 6a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 12 6ZM12 18a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 12 18ZM3.75 6.75h1.5a.75.75 0 1 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5ZM5.25 18.75h-1.5a.75.75 0 0 1 0-1.5h1.5a.75.75 0 0 1 0 1.5ZM3 12a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 3 12ZM9 3.75a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5ZM12.75 12a2.25 2.25 0 1 1 4.5 0 2.25 2.25 0 0 1-4.5 0ZM9 15.75a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" />
        </svg>
      </button>
    </div>

    <?php if($role === ROLE_ADMIN || $role === ROLE_EMPLOYE): ?>
      <div class="flex justify-end items-center">
        <div class="flex justify-center border border-primary/50 bg-primary/80 text-fond-carte rounded-2xl py-2 px-4 md:py-4 md:px-6 text-lg font-medium w-fit">
          <a href="/creerMenu">Creer un menu</a>
        </div>
      </div>
    <?php endif; ?>

  </div>

  <!-- Menus -->

  <div id="menuListe" class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <?php foreach($menus as $menu): ?>
  <div class="cart-menu">
    <div class="grid grid-cols-4">
      <!-- Texte -->
      <div class="grid col-span-3 text-center gap-6 px-4 py-6 sm:px-8 sm:pt-6 sm:pb-10">
        <?php if($role === ROLE_ADMIN || $role === ROLE_EMPLOYE): ?>
        <div class="blockRetour m-auto flex justify-center w-full"></div>
        <?php endif; ?>
        <div class="grid gap-2">
          <h2 class="font-h2 text-2xl md:text-3xl text-primary"><?= $menu->getTitre() ?></h2>
          <div class="flex gap-2 justify-center items-center">
            <p class="font-medium text-xl md:text-2xl"><?= $menu->getPrixPersonne()?>€</p>
            <p>/personne</p>
          </div>
        </div>

        <!-- Separation -->
        <div class="flex items-center justify-center gap-2">
          <div class="h-px flex-1 bg-primary"></div>
          <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-primary">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
          </svg>
          <div class="h-px flex-1 bg-primary"></div>
        </div>

        <!-- Plats -->

        <div class="flex flex-col gap-8 md:gap-10">
          <?php foreach($menu->getPlat() as $plat): ?>
            <div class="flex flex-col gap-6">
              <p><?= $plat->getTitre() ?></p>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Separation -->

        <div class="flex items-center justify-center gap-2">
           <div class="h-px flex-1 bg-primary"></div>
          <p></p>
          <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-primary">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
          </svg>
          <div class="h-px flex-1 bg-primary"></div>
        </div>

        <div class="flex items-center w-full justify-between">
          <div class="flex flex-col gap-6">
            <div class="flex justify-center gap-2">
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                  <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                </svg>
              </div>
              <p class="text-sm text-texte/70">Minimum <?= $menu->getNombrePersonneMin() ?> personnes</p>
            </div>
            <div class="flex flex-wrap gap-2">
              <?php foreach($menu->getTheme() as $theme): ?>
                <p class="border border-primary rounded-sm px-2 w-fit"><?= $theme->getLibelle() ?></p>
              <?php endforeach; ?>
            </div>

          <?php if($role === ROLE_ADMIN || $role === ROLE_EMPLOYE): ?>
          <div class="flex items-center gap-8">
            <label>Statut: </label>
              <label>
                <span class="toggleLabelText"><?= $menu->isMenuActif() == 1 ? 'Actif' : 'Inactif' ?></span>
              <input class="peer appearance-none toggleMenuInput" type="checkbox" name="menu_actif" data-id="<?= $menu->getMenuId() ?>" <?= $menu->isMenuActif() == 1 ? 'checked' : '' ?>>
              <span class="toggle"></span>
            </label>
          </div>
          <?php endif; ?>

          </div>
          <a class="btn-detail mt-4 self-end" href="/detailMenu?id=<?= $menu->getMenuId() ?>">Details</a>
        </div>
        <div class="flex items-center justify-center">
          <a class="btn-form-dark flex justify-center items-center w-40" href="/commandeMenu">Commander</a>
        </div>
      </div>
      <!-- Images -->
        <div class="grid col-span-1 rounded-r-3xl overflow-hidden">
          <?php if (count($menu->getPlat()) < 3): ?>
            <?php for($i = 0; $i < 3; $i++): ?>
              <img class="w-full h-64 shrink-0 object-cover" src="<?= $menu->getImageMenu() ?>" alt="">
            <?php endfor; ?>
          <?php else: ?>
          
            <?php foreach($menu->getPlat() as $plat): ?>
              <img class="w-full h-64 shrink-0 object-cover" src="<?= $plat->getImagePlat() ?>" alt="">
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
    </div>
  </div>
  <?php endforeach; ?>
  </div>
</div>


<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>
