<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<div class="contenue-page ">

  <div class="cart-form">

    <div class="w-lg pb-10 mb-6">
      <?php if(isset($erreur)): ?>
        <p class="erreur"><?= $erreur ?></p>
      <?php endif; ?>
    </div>

    <form class="grid grid-cols-2 md:p-4 gap-6 " action="/motDePasseOublie" method="post">
      <?php /** @var string $csrfToken */ ?>
      <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">

      <div class="flex flex-col col-span-2">
        <label class="font-label" for="email">Email</label>
        <input id="email" class="grand-input" type="email" name="email" required>
      </div>

      <div class="flex justify-center col-span-2">
        <button class="btn-form m-4" type="submit">Reinitialiser</button>
      </div>
    </form>

  </div>
  
  <?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>


  TQKRk(AOMjdCcHbv