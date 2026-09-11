<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

  <div id="modalContainer" class="fixed inset-0 flex justify-center items-center bg-black/50 hidden">
    <div id="modal" class="flex flex-col text-center justify-center items-center gap-6 bg-fond-carte w-xl h-1/3 p-6 rounded-2xl">
      <h2 class="font-h2 text-primary text-xl">Souhaitez vous vraiment supprimer le compte de <span id="nomEmploye"></span> ?</h2>
      <p>Une fois supprimé cet employe n'aura plus d'accès</p>
      <div class="flex justify-center gap-8">
        <div>
          <button id="btnAnnuler" class="btn bg-texte text-fond-carte" type="button">Annuler</button>
        </div>
        <div>
          <form action="/supprimerCompteEmploye" method="post">
            <?php /** @var string $csrfToken */ ?>
            <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
            <input type="hidden" name="id" id="idEmploye">
            <button id="btnConfirmSuppression" class="btn" type="submit">Supprimer</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="contenue-page pb-6">
    <div class="w-full pb-10 mb-6">
        <?php if (isset($_SESSION['erreur'])): ?>
      <p class="erreur"><?= $_SESSION['erreur'] ?></p>
      <?php unset($_SESSION['erreur']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['succes'])): ?>
      <p class="succes">
          <?= $_SESSION['succes'] ?>
      </p>
      <?php unset($_SESSION['succes']); ?>
    <?php endif; ?>
  </div>

  <div class="flex justify-center items-end border border-primary/50 bg-primary/70 text-fond-carte rounded-2xl py-2 px-4 text-lg font-medium ml-auto w-fit">
    <a href="/inscriptionEmploye">Inscription Employe</a>
  </div>
  <?php /** @var object $listeEmploye */ ?>
  <?php /** @var string $csrfToken */ ?>

  <?php foreach($listeEmploye as $employe): ?>
    <div class="flex flex-row justify-between cart-form text-center">
      <div class="cols-span-2">
        <p><?= $employe->getNom() ?></p>
        <p><?= $employe->getPrenom() ?></p>
      </div>

      <div class="flex flex-row items-center justify-center gap-8">
        <div class="text-primary border rounded-4xl py-2">
          <a class="px-8" href="/detailEmploye?id=<?= $employe->getUserId() ?>">Detail</a>
        </div>

        <div>
            <button type="button" class="btnSupprimerCmptEmploye btn-form bg-texte/90"
             data-nom="<?= $employe->getPrenom() ?>" data-id="<?= $employe->getUserId() ?>"
             >Supprimer</button>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  
  <?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>
