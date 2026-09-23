<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var object $boisson */?>
<?php /** @var string $csrfToken */ ?>

<div class="contenue-page">
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
  <div>
    <a class="flex gap-2 items-center border border-primary/50 bg-primary/70 text-fond-carte rounded-2xl p-4 text-lg font-medium w-fit" href="/detailBoisson?id=<?= $boisson->getBoissonId() ?>">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
        <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
      </svg>
      <p>Detail boisson</p>
    </a>
  </div>
  <div class="cart-form">
    <form class="grid grid-cols-2 gap-4" action="/modifierBoisson" method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">
      <input type="hidden" name="id" value="<?= $boisson->getBoissonId() ?>">
      <div class="col-span-2 flex flex-col">
        <label for="titre">Titre</label>
        <input class="grand-input w-2/3" type="text" name="titre" value="<?= htmlspecialchars($boisson->getNomBoisson()) ?>">
      </div>

      <div class="col-span-2">
        <label id="imgBoisson" for="image_boisson">Image de la boisson</label>
  
        <label class="grand-input w-2/3 h-60 text-texte/50 flex flex-col items-center justify-center relative overflow-hidden" for="image_boisson" id="igmLabel">
          <input class="sr-only lg:not-sr-only" type="file" name="image_boisson" id="image_boisson" >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-13">
            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
          </svg>
          <img class="imgHtml absolute inset-0 w-full h-full object-cover opacity-90" src="<?= htmlspecialchars($boisson->getPhotoBoisson())?>" alt="Image <?= htmlspecialchars($boisson->getNomBoisson()) ?>">
        </label>
      </div>

      <div class="col-span-2 flex flex-col">
        <label for="description_boisson">Description</label>
        <textarea name="description_boisson" class="grand-input w-xs md:w-2/3" rows="6"><?= htmlspecialchars($boisson->getDescriptionBoisson()) ?></textarea>
      </div>
      <div class="flex flex-col">
        <label for="prix_personne">Prix</label>
        <input class="grand-input" type="text" name="prix_personne" value="<?= htmlspecialchars($boisson->getPrixBoisson()) ?>">
      </div>
      <div class="flex flex-col">
        <label for="stock_boisson">Stock</label>
        <input class="grand-input" type="number" name="stock_boisson" value="<?= htmlspecialchars($boisson->getStockBoisson()) ?>">
      </div>
      <div class="col-span-1 flex flex-col">

      <fieldset class="flex gap-4">
        <legend class="pb-5">Type de boisson:</legend>
        <label class="has-checked:bg-primary hover:bg-primary/50 has-checked:text-fond-carte px-4 py-2 rounded-2xl border border-primary">
          <input class="sr-only" type="radio" name="alcool" value="0" required
          <?= (int)$boisson->isAlcool() == 0 ? 'checked' : ''?>>
          Sans alcool
        </label>
        <label class="has-checked:bg-primary hover:bg-primary/50 has-checked:text-fond-carte px-4 py-2 rounded-2xl border border-primary">
          <input class="sr-only" type="radio" name="alcool" value="1" required
          <?= (int)$boisson->isAlcool() == 1 ? 'checked' : ''?>>
          Avec alcool
        </label>
      </fieldset>

      </div>
      <div class="flex justify-between items-center gap-8">
        <label class="md:text-xl">Statut: </label>
        <label class="pl-8">
          <span class="toggleLabelText"><?= $boisson->isBoissonActif() == 1 ? 'Actif' : 'Inactif' ?></span>
          <input class="peer appearance-none toggleBoissonInput" type="checkbox" name="boisson_actif" value="<?= $boisson->isBoissonActif() ?>" data-id="<?= $boisson->getBoissonId() ?>" <?= $boisson->isBoissonActif() == 1 ? 'checked' : '' ?>>
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