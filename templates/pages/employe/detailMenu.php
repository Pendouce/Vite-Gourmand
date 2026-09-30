<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var object $plat */?>
<?php /** @var object $menu */?>
<?php /** @var string $role */ ?>
<?php /** @var string $csrfToken */ ?>


<div class="contenue-page">
  <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">

  <div class="blockRetour w-full">
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
  
  <div class="border border-primary/50 bg-primary/70 text-fond-carte rounded-2xl p-4 text-lg font-medium w-fit">
    <a class="flex gap-2 items-center" href="/menu">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
        <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
      </svg>
      Menus
    </a>
  </div>



  <!-- Titre -->
    <div class="flex justify-center items-center gap-2 font-font-h2 p-4 mb-8">
      <h2 class=" text-primary text-2xl font-medium md:text-4xl text-center"><?= htmlspecialchars($menu->getTitre()) ?></h2>
      <h2 class="font-medium text-2xl md:text-3xl"><?= htmlspecialchars($menu->getPrixPersonne()) ?>€</h2>
      <h2 class="self-center">/personne</h2>
    </div>

    <!-- Desktop -->

  <div class="hidden lg:grid grid-rows-3 grid-cols-3 gap-6 pb-6">
      <!-- Img + infos -->
      <div class="row-span-3 col-span-1 flex flex-col gap-4">
        <div class="relative aspect-square">
          <img id="imgPlat" class="absolute inset-0 w-full h-full object-cover" src="<?= htmlspecialchars($menu->getImageMenu()) ?>" alt="">
        </div>
        <div class="row-span-1 border border-primary p-4 flex flex-col justify-center items-center gap-2 rounded-md mt-auto">
          <div class="flex flex-wrap gap-2">
            <p>Evenements: </p>
            <?php foreach($menu->getEvenement() as $evenement): ?>
              <p><?= htmlspecialchars($evenement->getLibelle()) ?></p>
            <?php endforeach; ?>
          </div>
          
          <div class="flex flex-wrap gap-2">
            <p>Theme: </p>
            <?php foreach($menu->getTheme() as $theme): ?>
              <p><?= htmlspecialchars($theme->getLibelle()) ?></p>
            <?php endforeach; ?>
          </div>

          <div class="flex flex-wrap gap-2">
            <p>Regime: </p>
            <?php foreach($menu->getRegime() as $regime): ?>
              <p><?= htmlspecialchars($regime->getLibelle()) ?></p>
            <?php endforeach; ?>
          </div>
          
          <div class="flex flex-wrap gap-2">
              <p>📆 <?= htmlspecialchars($menu->getConditions()) ?></p>
          </div>

          <div class="flex flex-wrap">
              <p>👥 Minimum <?= htmlspecialchars($menu->getNombrePersonneMin()) ?> personnes</p>
          </div>

          <div class="flex flex-wrap">
              <p><?= htmlspecialchars($menu->getStockDispo()) ?> commandes restantes</p>
          </div>
        </div>
      </div>
      <!-- Plat -->
     <div class="row-span-3 col-span-2 grid grid-rows-3 gap-6">
      <?php foreach($menu->getPlat() as $plat): ?>
        <section class="divPlat row-span-1 flex flex-col border border-primary p-4 bg-fond-carte/50 gap-4" data-img="<?= htmlspecialchars($plat->getImagePlat()) ?>">
          <h3 class="text-xl text-primary md:text-2xl"><?= htmlspecialchars($plat->getLibelle()) ?></h3>
          <h4 class="text-md md:text-lg font-medium"><?= htmlspecialchars($plat->getTitre()) ?></h4>
          <p><?= htmlspecialchars($plat->getDescriptionPlat()) ?></p>
        </section>
      <?php endforeach; ?>
     </div>

     <!-- Allergene -->
      <div class="col-span-3 col-start-2 flex flex-wrap gap-2">
        <p>⚠️ Allergenes : </p>
        <?php foreach($menu->getAllergene() as $allergene): ?>
          <p class="border border-primary rounded-sm bg-primary/30 px-2"><?= htmlspecialchars($allergene->getLibelle()) ?></p>
          <?php endforeach; ?>
      </div>

  </div>

  <!-- Mobile -->

  <div class="lg:hidden grid grid-cols-3 gap-6 pb-6">
    <!-- Plat / Images -->
     <div class="col-span-3 grid grid-cols-3 auto-rows-fr  gap-4">
      <?php foreach($menu->getPlat() as $plat): ?>
        <div class="col-span-1 row-span-1 relative">
          <img class="absolute inset-0 w-full h-full object-cover" src="<?= htmlspecialchars($plat->getImagePlat()) ?>" alt="">
        </div>
        <section class="col-span-2 flex flex-col border border-primary p-4 bg-fond-carte/50 gap-2">
          <h3 class="text-xl text-primary"><?= htmlspecialchars($plat->getLibelle()) ?></h3>
          <h4 class="font-medium"><?= htmlspecialchars($plat->getTitre()) ?></h4>
          <p><?= htmlspecialchars($plat->getDescriptionPlat()) ?></p>
        </section>
      <?php endforeach; ?>
    </div>

     <!-- Infos -->
        <div class=" col-span-3 border border-primary p-4 flex flex-col justify-center items-center gap-2 rounded-md">
            <div class="flex flex-wrap gap-2">
              <p>Evenements: </p>
              <?php foreach($menu->getEvenement() as $evenement): ?>
                <p><?= htmlspecialchars($evenement->getLibelle()) ?></p>
              <?php endforeach; ?>
            </div>
            
            <div class="flex flex-wrap gap-2">
              <p>Theme: </p>
              <?php foreach($menu->getTheme() as $theme): ?>
                <p><?= htmlspecialchars($theme->getLibelle()) ?></p>
              <?php endforeach; ?>
            </div>

            <div class="flex flex-wrap gap-2">
              <p>Regime: </p>
              <?php foreach($menu->getRegime() as $regime): ?>
                <p><?= htmlspecialchars($regime->getLibelle()) ?></p>
              <?php endforeach; ?>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <p>📆 <?= htmlspecialchars($menu->getConditions()) ?></p>
            </div>

            <div class="flex flex-wrap">
                <p>👥 Minimum <?= htmlspecialchars($menu->getNombrePersonneMin()) ?> personnes</p>
            </div>

            <div class="flex flex-wrap">
                <p><?= htmlspecialchars($menu->getStockDispo()) ?> commandes restantes</p>
            </div>
          </div>

      <!-- Allergene -->
        <div class="col-span-3 flex flex-wrap gap-2">
          <p>⚠️ Allergenes : </p>
          <?php foreach($menu->getAllergene() as $allergene): ?>
            <p class="border border-primary rounded-sm bg-primary/30 px-2"><?= htmlspecialchars($allergene->getLibelle()) ?></p>
            <?php endforeach; ?>
        </div>
  </div>

  <div class="flex justify-center items-center p-6">
    <div class="btn-form-dark">
      <a href="/commandeMenu">Commander</a>
    </div>
  </div>

  
  <?php if($role === ROLE_ADMIN || $role === ROLE_EMPLOYE): ?>
    <div class="flex items-center gap-8 p-6">
      <label>Statut: </label>
        <label>
          <span class="toggleLabelText"><?= $menu->isMenuActif() == 1 ? 'Actif' : 'Inactif' ?></span>
        <input class="peer appearance-none toggleMenuInput" type="checkbox" name="menu_actif" data-id="<?= $menu->getMenuId() ?>" <?= $menu->isMenuActif() == 1 ? 'checked' : '' ?>>
        <span class="toggle"></span>
      </label>
    </div>

  <div class="flex justify-center p-4 gap-8">
    <div>
      <a class="btn-form" href="/modifierMenu?id=<?= $menu->getMenuId() ?>">Modifier</a>
    </div>
    <div>
      <form action="/supprimerMenu" method="post">
        <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
        <input type="hidden" name="id" value="<?= $menu->getMenuId() ?>">
        <button class="btn-form-dark" type="submit">Supprimer</button>
      </form>
    </div>
  </div>
  <?php endif; ?>

</div>





<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>