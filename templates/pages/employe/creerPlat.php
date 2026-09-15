<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var array $plats */?>
<?php /** @var array $platsParType */?>
<?php /** @var string $csrfToken */ ?>
<?php /** @var array $typeDePlat */ ?>
<?php /** @var array $allergenes */ ?>


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
    <a class="flex gap-2 items-center border border-primary/50 bg-primary/70 text-fond-carte rounded-2xl p-4 text-lg font-medium w-fit" href="/plats">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
        <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
      </svg>
      <p>Plats</p>
    </a>
  </div>
  <div class="cart-form">
    <form class="grid grid-cols-2 gap-4" action="/creerPlat" method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">
      <div class="col-span-2 flex flex-col">
        <label for="titre">Titre</label>
        <input class="grand-input w-2/3" type="text" name="titre">
      </div>

      <div class="col-span-2">
        <label id="imgPlat" for="image_plat">Image du plat</label>
  
        <label class="grand-input w-2/3 h-60 text-texte/50 flex flex-col items-center justify-center relative" for="image_plat" id="igmLabel">
          <input class="sr-only lg:not-sr-only" type="file" name="image_plat" id="image_plat">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-13">
            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
          </svg>
        </label>
      </div>

      <div class="col-span-2 flex flex-col">
        <label for="description_plat">Description</label>
        <textarea name="description_plat" class="grand-input w-xs md:w-2/3" rows="6"></textarea>
      </div>
      <div class="flex flex-col">
        <label for="prix_personne">Prix</label>
        <input class="grand-input" type="text" name="prix_personne">
      </div>
      <div class="flex flex-col">
        <label for="stock_plat">Stock</label>
        <input class="grand-input" type="number" name="stock_plat">
      </div>
      <div class="col-span-1 flex flex-col">
        <label for="type_id">Type de plat</label>
        <select class="grand-input py-2" name="type_id">
          <?php foreach($typeDePlat as $type): ?>
            <option value="<?= $type->getTypeId() ?>"><?= $type->getLibelle() ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-span-1 flex justify-between items-center gap-8">
          <label class="pl-8">
            Actif
            <input class="peer appearance-none" type="checkbox" name="plat_actif" value="1">
            <span class="toggle m-4"></span>
          </label>
      </div>
      <div class="col-span-2 flex flex-col">
        <label>Allergenes</label>
        <div class="flex flex-wrap gap-3 pt-4">
          <?php foreach($allergenes as $allergene): ?>
            <label class="cart-check has-checked:bg-primary/50" for="allergene-<?= $allergene->getAllergeneId() ?>"><?= $allergene->getLibelle() ?>
              <input class=" appearance-none" type="checkbox" name="allergene[]" id="allergene-<?= $allergene->getAllergeneId() ?>" value="<?= $allergene->getAllergeneId() ?>">
            </label>
          <?php endforeach; ?>
        </div>
      </div>
      <button class="btn-form" type="submit">Creer</button>
  </form>
  </div>
</div>


<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>