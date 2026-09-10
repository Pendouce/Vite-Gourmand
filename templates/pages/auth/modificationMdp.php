<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<div class="contenue-page ">

<div class="border border-primary/50 bg-primary/70 text-fond-carte rounded-2xl p-4 text-lg font-medium w-fit">

  <a class="flex gap-2 items-center" href="/mesInfos">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
      <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
    </svg>
    <p>Mes infos</p>
  </a>
</div>

  <div class="cart-form">
    <div class="w-lg pb-10 mb-6">
      <?php if(isset($_SESSION['erreur'])): ?>
        <p class="erreur"><?= $_SESSION['erreur'] ?></p>
        <?php unset($_SESSION['erreur']); ?>
      <?php endif; ?>
    </div>

    <div class="w-lg pb-10 mb-6">
      <?php if(isset($erreur)): ?>
        <p class="erreur"><?= $erreur ?></p>
      <?php endif; ?>
    </div>
    <form class="grid grid-cols-2 gap-6 " action="/modificationMotDePasse" method="post">
      <?php /** @var string $csrfToken */ ?>
      <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
      <?php /** @var object $infoUtilisateur */ ?>

      <div class="flex flex-col col-span-2">
        <label class="font-label" for="ancienMdp">Mot de passe</label>
        <input id="ancienMdp" class="grand-input" type="text" name="ancienMdp" required>
      </div>

      <div class="flex flex-col col-span-2">
        <label class="font-label" for="mot_de_passe">Nouveau mot de passe</label>
        <input id="mot_de_passe" class="grand-input" type="text" name="mot_de_passe" required>
      </div>

      <div class="flex flex-col col-span-2">
        <label class="font-label" for="mdpConfirm">Confirmation mot de passe</label>
        <input id="mdpConfirm" class="grand-input" type="text" name="mdpConfirm" required>
      </div>

      <div class="flex justify-center col-span-2 mt-6">
        <button class="btn-form" type="submit">Modifier</button>
      </div>
    </form>

  </div>
  
  <?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>
