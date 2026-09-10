<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<div class="contenue-page ">

  <div class="border border-primary/50 bg-primary/70 text-fond-carte rounded-2xl p-4 text-lg font-medium w-fit">

  <a class="flex gap-2 items-center" href="/gestionEmployes">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
      <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
    </svg>
    <p>Gestion employes</p>
  </a>
</div>

  <div class="cart-form">

    <div class="w-lg pb-10 mb-6">
      <?php if(isset($erreur)): ?>
        <p class="erreur"><?= $erreur ?></p>
      <?php endif; ?>
    </div>

    <form class="grid grid-cols-2 md:p-4 gap-6 " action="/inscriptionEmploye" method="post">
      <?php /** @var string $csrfToken */ ?>
      <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">

      <div class="flex flex-col col-span-1">
        <label class="font-label" for="nom">Nom</label>
        <input id="nom" class="grand-input" type="text" name="nom" required>
      </div>

      <div class="flex flex-col col-span-1">
        <label class="font-label" for="prenom">Prenom</label>
        <input id="prenom" class="grand-input" type="text" name="prenom" required>
      </div>

      <div class="flex flex-col col-span-2">
        <label class="font-label" for="email">Email</label>
        <input id="email" class="grand-input" type="email" name="email" required>
      </div>

      <div class="flex flex-col col-span-1">
        <label class="font-label" for="telephone">Telephone</label>
        <input id="telephone" class="grand-input" type="tel" name="telephone">
      </div>

      <div class="flex flex-col col-span-2">
        <label class="font-label" for="adresse">Adresse</label>
        <input id="adresse" class="grand-input" type="text" name="adresse">
      </div>

      <div class="flex flex-col col-span-1">
        <label class="font-label" for="code_postal">Code Postal</label>
        <input id="code_postal" class="grand-input" type="text" name="code_postal">
      </div>

      <div class="flex flex-col col-span-1">
        <label class="font-label" for="ville">Ville</label>
        <input id="ville" class="grand-input" type="text" name="ville">
      </div>

      <div class="flex justify-center col-span-2">
        <button class="btn-form m-4" type="submit">Inscription</button>
      </div>
    </form>

  </div>
  
  <?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>
