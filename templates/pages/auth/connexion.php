<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<div class="contenue-page ">

  <div class="cart-form">

    <div class="w-md pb-10 mb-6">
      <?php if(isset($erreur)): ?>
        <p class="erreur"><?= $erreur ?></p>
      <?php endif; ?>
    </div>
    <form class="flex flex-col justify-center items-center gap-6 " action="/connexion" method="post">
      <?php /** @var string $csrfToken */ ?>
      <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">

      

      <div class="flex flex-col ">
        <label class="font-label" for="email">Email</label>
        <input class="grand-input" type="email" name="email" required>
      </div>
      <div class="flex flex-col m-4">
        <label class="font-label" for="email">Mot de passe</label>
        <input class="grand-input" type="password" name="mot_de_passe" required>
      </div>
      <a class="text-blue-600" href="/motDePasseOublie">Mot de passe oublié ?</a>
      <div>
        <button class="btn-form m-4" type="submit">Connexion</button>
      </div>
      <p>Vous n'avez pas de compte ? <a class="text-blue-600" href="/inscription">Cilquez ici !</a></p>
      
      </div>
    </form>
    
  </div>
  
  <?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>
