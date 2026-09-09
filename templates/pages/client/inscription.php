<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<div class="contenue-page ">

  <div class="cart-form">

    <div class="w-lg pb-10 mb-6">
      <?php if(isset($erreur)): ?>
        <p class="erreur"><?= $erreur ?></p>
      <?php endif; ?>
    </div>
    <form class="grid grid-cols-2 gap-6 " action="/inscription" method="post">
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

      <div class="flex flex-col col-span-2">
        <label class="font-label" for="email">Mot de passe</label>
        <input id="mot_de_passe" class="grand-input" type="password" name="mot_de_passe" required>
      </div>

      

      <div class="flex flex-col col-span-2">
        <label class="font-label" for="mdpConfirm">Confirmation mot de passe</label>
        <input id="mdpConfirm" class="grand-input" type="password" name="mdpConfirm" required>
      </div>

      <div class="flex justify-center col-span-2">
        <button class="btn-form m-4" type="submit">Inscription</button>
      </div>

      <div class=" flex justify-center col-span-2">
        <p>Vous avez déja un compte ? <a class="text-blue-600" href="/connexion">Cilquez ici !</a></p>
      </div>
    </form>

  </div>
  
  <?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>
