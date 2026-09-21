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
    <form class="grid grid-cols-2 gap-4" action="/creerBoisson" method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">
      <div class="col-span-2 flex flex-col">
        <label for="nom_boisson">Titre</label>
        <input class="grand-input w-2/3" type="text" name="nom_boisson">
      </div>

      <div class="col-span-2">
        <label id="imgBoisson" for="photo_boisson">Image de la boisson</label>
  
        <label class="grand-input w-2/3 h-60 text-texte/50 flex flex-col items-center justify-center relative" for="photo_boisson" id="igmLabel">
          <input class="sr-only lg:not-sr-only" type="file" name="photo_boisson" id="photo_boisson">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-13">
            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
          </svg>
        </label>
      </div>

      <div class="col-span-2 flex flex-col">
        <label for="description_boisson">Description</label>
        <textarea name="description_boisson" class="grand-input w-xs md:w-2/3" rows="6"></textarea>
      </div>
      <div class="flex flex-col">
        <label for="prix_boisson">Prix</label>
        <input class="grand-input" type="text" name="prix_boisson">
      </div>
      <div class="flex flex-col">
        <label for="stock_boisson">Stock</label>
        <input class="grand-input" type="number" name="stock_boisson">
      </div>

      <!-- <div class="col-span-1 ">
        <label class="" for="alcool">Avec alcool
          <input type="radio" name="alcool" value="1">
        </label>
        <label for="alcool">Sans alcool
          <input type="radio" name="alcool" value="0">
        </label>
      </div> -->

      <fieldset class="flex gap-4">
        <legend class="pb-5">Type de boisson:</legend>
        <label class="has-checked:bg-primary has-checked:text-fond-carte px-4 py-2 rounded-2xl border border-primary">
          <input class="sr-only" type="radio" name="alcool" value="0" required>
          Sans alcool
        </label>
        <label class="has-checked:bg-primary has-checked:text-fond-carte px-4 py-2 rounded-2xl border border-primary">
          <input class="sr-only" type="radio" name="alcool" value="1" required>
          Avec alcool
        </label>
      </fieldset>


      <div class="col-span-1 flex flex-col justify-end-safe items-center pr-4 gap-4">
          <label>Statut: </label>
          <label class=" flex flex-row-reverse gap-2">
            <span class="toggleLabelText"></span>
            <input class="peer appearance-none toggleLabelText" type="checkbox" name="boisson_actif" value="1">
            <span class="toggle"></span>
            <span class="hidden peer-checked:inline">Actif</span>
            <span class="peer-checked:hidden">Inactif</span>
          </label>
      </div>
      <div class="col-span-2 flex items-center justify-center p-4">
        <button class="btn-form" type="submit">Creer</button>
      </div>
  </form>
  </div>
</div>


<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>