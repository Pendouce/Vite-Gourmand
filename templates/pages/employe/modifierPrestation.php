<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var object $prestation */?>
<?php /** @var array $typeDePresta */?>
<?php /** @var array $prestationParType */?>
<?php /** @var object $role */?>
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
    <a class="flex gap-2 items-center border border-primary/50 bg-primary/70 text-fond-carte rounded-2xl p-4 text-lg font-medium w-fit" href="/prestations">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
        <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
      </svg>
      <p>Prestation</p>
    </a>
  </div>
  <div class="cart-form">
    <form class="grid grid-cols-2 gap-4" action="/modifierPrestation" method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">
      <input type="hidden" name="id" value="<?= $prestation->getPrestationId() ?>">
      <div class="col-span-2 flex flex-col">
        <label for="titre">Titre</label>
        <input class="grand-input w-2/3" type="text" name="nom_presta" value="<?= htmlspecialchars($prestation->getNomPresta()) ?>">
      </div>

      <div class="col-span-2">
        <label id="imgPlat" for="img_presta">Image prestation</label>
  
        <label class="grand-input w-2/3 h-60 text-texte/50 flex flex-col items-center justify-center relative" for="img_presta" id="igmLabel">
          <input class="sr-only lg:not-sr-only" type="file" name="img_presta" id="img_presta">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-13">
            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
          </svg>
          <img class="imgHtml absolute rounded-2xl inset-0 w-full h-full object-cover opacity-90" src="<?= htmlspecialchars($prestation->getImgPresta())?>" alt="Image <?= htmlspecialchars($prestation->getNomPresta()) ?>">
        </label>
      </div>

      <div class="col-span-1 flex flex-col">
        <label for="type_presta_id">Type de prestation</label>
        <select class="grand-input py-2" name="type_presta_id">
          <?php foreach($typeDePresta as $type): ?>
            <option value="<?= $type->getTypePrestaId() ?>"<?= $type->getTypePrestaId() == $prestation->getTypePrestaId() ? 'selected' : '' ?>>
            <?= htmlspecialchars($type->getLibelle()) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-span-2 flex ">
        <div class="flex flex-col justify-center md:w-full">
          <label for="prix_presta">Contenue prestation</label>
          <div id="divInputContenuePresta">
            <?php foreach($prestation->getContenuPresta() as $index => $contenu): ?>
              <div class="flex">

                <textarea name="contenu_presta[]" class="inputContenuePresta grand-input w-3/4 md:w-2/3" rows="1"><?= htmlspecialchars($contenu) ?></textarea>
                
                <?php if($index > 0): ?>
                  <button type="button" class="btnSupprimerContenu self-center text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                  </button>
                  <?php endif; ?>
                </div>
                  <?php endforeach; ?>
          </div>
        </div>

        <div class="w-20 p-4 items-start">
          <button id="btnAjouter" class="border border-primary rounded-2xl p-4" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 md:size-10 text-primary">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
          </button>
        </div>
      </div>

      <div class="col-span-2 flex flex-col">
        <label for="description_presta">Description</label>
        <textarea name="description_presta" class="grand-input w-xs md:w-2/3" rows="6"><?= htmlspecialchars($prestation->getDescriptionPresta()) ?></textarea>
      </div>

      <div class="flex flex-col">
        <label for="prix_presta">Prix</label>
        <input class="grand-input" type="number" min="1" step="any" name="prix_presta" value="<?= htmlspecialchars($prestation->getPrixPresta()) ?>">
      </div>

      <fieldset class="flex gap-2">
        <legend class="">Necessite retour</legend>
        <div class="flex gap-4 pl-6">
        <label class="has-checked:bg-primary/60 has-checked:text-fond-carte px-4 py-2 rounded-2xl border border-primary/50 self-center">
          <input class="sr-only" type="radio" name="necessite_retour" value="1" <?= $prestation->isNecessiteRetour() ? 'checked' : '' ?>>
          Oui
        </label>
        <label class="has-checked:bg-primary/50 has-checked:text-fond-carte px-4 py-2 rounded-2xl border border-primary/50 self-center">
          <input class="sr-only" type="radio" name="necessite_retour" value="0" <?= !$prestation->isNecessiteRetour() ? 'checked' : '' ?>>
          Non
        </label>
        </div>
      </fieldset>
      
      <div class="col-span-1 col-start-2 flex flex-col justify-between items-center ">
          <label>Statut: </label>
          <label class=" flex flex-row-reverse gap-2">
            <span class="toggleLabelText"></span>
            <input class="peer appearance-none toggleLabelText" type="checkbox" name="prestation_actif" value="<?= $prestation->isPrestationActif() ?>" data-id="<?= $prestation->getPrestationId() ?>" <?= $prestation->isPrestationActif() == 1 ? 'checked' : '' ?>>
            <span class="toggle"></span>
            <span class="hidden peer-checked:inline">Actif</span>
            <span class="peer-checked:hidden">Inactif</span>
          </label>
      </div>
      <div class="col-span-2 flex items-center justify-center p-4">
        <button class="btn-form" type="submit">Modifier</button>
      </div>
  </form>
  </div>
</div>


<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>