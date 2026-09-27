<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var object $plat */?>
<?php /** @var object $menu */?>
<?php /** @var string $role */ ?>
<?php /** @var string $csrfToken */ ?>


<div class="contenue-page">
  <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">

  <div class="blockRetour w-full pb-10 mb-6">
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
    <a class="flex gap-2 items-center" href="/menu">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
        <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
      </svg>
      <p>Menus</p>
    </a>
  </div>

  <div class="grid grid-cols-3 gap-6">
    <!-- Titre -->
    <div class="col-span-3 flex justify-center items-center gap-4 font-font-h2 p-8 m-4">
      <h2 class=" text-primary text-2xl font-medium md:text-4xl text-center"><?= htmlspecialchars($menu->getTitre()) ?></h2>
      <h2 class="font-medium text-2xl md:text-3xl"><?= htmlspecialchars($menu->getPrixPersonne()) ?>€</h2>
      <h2 class="self-center">/personne</h2>
    </div>
    
    <!-- Img + infos -->
     <div class="col-span-1 grid grid-rows-3 gap-4">
      <div class="row-span-2">
        <img class="object-cover" src="<?= $menu->getImageMenu() ?>" alt="">
      </div>
      <div class="row-span-1 border border-primary rounded-md"></div>
     </div>
    <!-- Plat -->
     <div class="col-span-2 grid grid-rows-3 gap-6">
      <?php foreach($menu->getPlat() as $plat): ?>
        <section class="row-span-1 flex flex-col border border-primary p-4 bg-fond-carte/50 gap-4">
          <h3 class="text-xl text-primary md:text-2xl"><?= $plat->getLibelle() ?></h3>
          <h4><?= $plat->getTitre() ?></h4>
          <p><?= $plat->getDescriptionPlat() ?></p>
        </section>
      <?php endforeach; ?>
     </div>
    
    <?php if($role === ROLE_ADMIN || $role === ROLE_EMPLOYE): ?>
      <div class="col-span-2 flex items-center gap-8 p-6">
        <label>Statut: </label>
          <label>
            <span class="toggleLabelText"><?= $menu->isMenuActif() == 1 ? 'Actif' : 'Inactif' ?></span>
          <input class="peer appearance-none toggleMenuInput" type="checkbox" name="menu_actif" data-id="<?= $menu->getMenuId() ?>" <?= $menu->isMenuActif() == 1 ? 'checked' : '' ?>>
          <span class="toggle"></span>
        </label>
      </div>
  
    <div class="col-span-3 flex justify-center p-4 gap-8 ">
      <div>
        <a class="btn-form" href="/modifierPlat?id=<?= $menu->getMenuId() ?>">Modifier</a>
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

</div>





<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>