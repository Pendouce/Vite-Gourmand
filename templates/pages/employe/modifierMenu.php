<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var object $menu */?>
<?php /** @var array $evenements */?>
<?php /** @var array $platsDuMenu */?>
<?php /** @var array $evenementsDuMenu */?>
<?php /** @var array $regimesDuMenu */?>
<?php /** @var array $themesDuMenu */?>
<?php /** @var array $themes */?>
<?php /** @var array $regimes */?>
<?php /** @var array $plats */?>
<?php /** @var array $typeDePlats */?>
<?php /** @var array $platsParType */?>
<?php /** @var string $csrfToken */ ?>



<div class="contenue-page">
  <div class="w-full pb-10 mb-6">
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
  <div>
    <a class="flex gap-2 items-center border border-primary/50 bg-primary/70 text-fond-carte rounded-2xl p-4 text-lg font-medium w-fit" href="/detailMenu?id=<?= $menu->getMenuId() ?>">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
        <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
      </svg>
      <p>Detail menu</p>
    </a>
  </div>
  <div class="cart-form">
    <form class="grid grid-cols-2 gap-4" action="/modifierMenu?id=<?= $menu->getMenuId() ?>" method="post">
      <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">
      <input type="hidden" name="id" value="<?= $menu->getMenuId() ?>">
      <div class="col-span-2 flex flex-col">
        <label for="titre">Titre</label>
        <input class="grand-input w-2/3" type="text" name="titre" value="<?= htmlspecialchars($menu->getTitre()) ?>">
      </div>
      
      <div class="grid grid-cols-2 md:grid-cols-3 col-span-2">
        <?php foreach($typeDePlats as $type): ?>
          <div class="flex flex-col">
              <label class="text-texte/80" for="plat[]"><?= $type->getLibelle() ?></label>
              <select class="grand-input w-max-xs py-2" name="plat[]" id="plat[]">
                <option value=""></option>
                <?php foreach($platsParType[$type->getLibelle()] ?? [] as $plat): ?>
                    <option value="<?= $plat->getPlatId() ?>" <?= in_array($plat->getPlatId(), $platsDuMenu) ? 'selected' : '' ?>>
                      <?= $plat->getTitre() ?>
                    </option>
                <?php endforeach; ?>
              </select>
          </div>
          <?php endforeach; ?>
      </div>

      <div class="col-span-2 flex flex-col">
        <label for="conditions">Conditions</label>
        <textarea name="conditions" class="grand-input w-xs md:w-2/3" rows="6"><?= htmlspecialchars($menu->getConditions()) ?></textarea>
      </div>
      <div class="flex flex-col">
        <label for="prix_personne">Prix</label>
        <input class="grand-input" type="number" min="1" step="any" name="prix_personne" value="<?= htmlspecialchars($menu->getPrixPersonne()) ?>">
      </div>
      <div class="flex flex-col">
        <label for="nombre_personne_min">Minimum de personne</label>
        <input class="grand-input" type="number" min="1" name="nombre_personne_min" value="<?= htmlspecialchars($menu->getNombrePersonneMin()) ?>">
      </div>

      <div class="col-span-2 flex justify-between gap-4 p-4">
        <fieldset class="p-2">
          <legend class="pb-5">Thèmes</legend>
          <div class="flex flex-wrap gap-4">
            <?php foreach($themes as $theme): ?>
              <label class="flex items-center gap-2">
                <input class="size-5 accent-primary" type="checkbox" name="theme[]" value="<?= $theme->getThemeId() ?>"
                  <?= in_array($theme->getThemeId(), $themesDuMenu) ? 'checked' : '' ?>>
                <?= $theme->getLibelle() ?>
              </label>
            <?php endforeach; ?>
          </div>
        </fieldset>

        <fieldset class="p-2">
          <legend class="pb-5">Regimes</legend>
          <div class="flex flex-wrap gap-4">
            <?php foreach($regimes as $regime): ?>
              <label class="flex items-center gap-2">
                <input class="size-5 accent-primary" type="checkbox" name="regime[]" value="<?= $regime->getRegimeId() ?>"
                  <?= in_array($regime->getRegimeId(), $regimesDuMenu) ? 'checked' : '' ?>>
                <?= $regime->getLibelle() ?>
              </label>
            <?php endforeach; ?>
          </div>
        </fieldset>
      </div>

      <div class="col-span-2 flex flex-col">
        <label>Evenements</label>
        <div class="flex flex-wrap gap-3 pt-4">
          <?php foreach($evenements as $evenement): ?>
            <label class="cart-check has-checked:bg-primary/50" for="evenement-<?= $evenement->getEvenementId() ?>"><?= htmlspecialchars($evenement->getLibelle()) ?>
            <input class=" appearance-none" type="checkbox" name="evenement[]" id="evenement-<?= $evenement->getEvenementId() ?>" value="<?= $evenement->getEvenementId() ?>"
            <?= in_array($evenement->getEvenementId(), $evenementsDuMenu) ? 'checked' : ''?>>
            </label>
          <?php endforeach; ?>
          
        </div>
      </div>

      <div class="col-span-2 flex flex-col justify-end items-center justify-self-end">
        <label class="md:text-xl">Statut: </label>
        <label class="pl-8">
          <span class="toggleLabelText"><?= $menu->isMenuActif() == 1 ? 'Actif' : 'Inactif' ?></span>
          <input class="peer appearance-none toggleMenuInput" type="checkbox" name="menu_actif" value="<?= $menu->isMenuActif() ?>" data-id="<?= $menu->getMenuId() ?>" <?= $menu->isMenuActif() == 1 ? 'checked' : '' ?>>
          <span class="toggle"></span>
        </label>
      </div>
      <div class="col-span-2 flex items-center justify-center p-4">
          <button class="btn-form" type="submit">Modifier</button>
      </div>
  </form>
  </div>
</div>


<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>