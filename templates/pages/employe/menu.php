<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var array $menus */?>
<?php /** @var int $role */?>
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

  <?php if($role === ROLE_ADMIN || $role === ROLE_EMPLOYE): ?>
    <div class="flex justify-end items-center">
      <div class="flex justify-center border border-primary/50 bg-primary/80 text-fond-carte rounded-2xl py-2 px-4 md:py-4 md:px-6 text-lg font-medium w-fit">
        <a href="/creerMenu">Creer un menu</a>
      </div>
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
