<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

  <div id="modalContainer" class="fixed inset-0 flex justify-center items-center bg-black/50 hidden">
    <div id="modal" class="flex flex-col text-center justify-center items-center gap-6 bg-fond-carte w-xl h-1/3 p-6 rounded-2xl">
      <h2 class="font-h2 text-primary text-xl">Souhaitez vous vraiment supprimer ce compte ?</h2>
      <p>Une fois supprimé vous n'aurez plus accès a vos commandes passées,<br> nous restons a votres disposition pour toutes questions</p>
        <div class="flex justify-center gap-8">
          <div>
            <button id="btnAnnuler" class="btn bg-texte text-fond-carte" type="button">Annuler</button>
          </div>
          <div>
            <form action="/supprimerMonCmpte" method="post">
              <?php /** @var string $csrfToken */ ?>
              <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
              <button id="btnConfirmSuppression" class="btn" type="submit">Supprimer</button>
            </form>
          </div>
        </div>
    </div>
  </div>

<div class="contenue-page ">

  <div class="cart-form">

    <div class="w-lg pb-10 mb-6">
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
    <form class="grid grid-cols-2 gap-6 " action="/modifierInfos" method="post">
      <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
      <?php /** @var object $infoUtilisateur */ ?>
      <div class="flex flex-col col-span-1">
        <label class="font-label" for="nom">Nom</label>
        <input id="nom" class="grand-input" type="text" name="nom" value="<?= $infoUtilisateur->getNom() ?>" >
      </div>

      <div class="flex flex-col col-span-1">
        <label class="font-label" for="prenom">Prenom</label>
        <input id="prenom" class="grand-input" type="text" name="prenom" value="<?= $infoUtilisateur->getPrenom() ?>">
      </div>

      <div class="flex flex-col col-span-2">
        <label class="font-label" for="email">Email</label>
        <input id="email" class="grand-input" type="email" name="email" value="<?= $infoUtilisateur->getEmail() ?>">
      </div>

      <div class="flex flex-col col-span-1">
        <label class="font-label" for="telephone">Telephone</label>
        <input id="telephone" class="grand-input" type="tel" name="telephone" value="<?= $infoUtilisateur->getTelephone() ?>">
      </div>

      <div class="flex flex-col col-span-2">
        <label class="font-label" for="adresse">Adresse</label>
        <input id="adresse" class="grand-input" type="text" name="adresse" value="<?= $infoUtilisateur->getAdresse() ?>">
      </div>

      <div class="flex flex-col col-span-1">
        <label class="font-label" for="code_postal">Code Postal</label>
        <input id="code_postal" class="grand-input" type="text" name="code_postal" value="<?= $infoUtilisateur->getCodePostal() ?>">
      </div>

      <div class="flex flex-col col-span-1">
        <label class="font-label" for="ville">Ville</label>
        <input id="ville" class="grand-input" type="text" name="ville" value="<?= $infoUtilisateur->getVille() ?>">
      </div>

      <div class="flex justify-center col-span-1 ">
        <button class="btn-form" type="submit">Modifier</button>
      </div>

      <div class="flex justify-center col-span-1">
        <a class="btn-form " href="/modificationMotDePasse">Modifier le mot de passe</a>
      </div>

      <div class="flex justify-center col-span-2 pt-4">
        <button id="btnSupprimerCmpt" class="btn-form bg-texte " type="button">Supprimer mon compte</button>
      </div>
    </form>

  </div>
  
  <?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>
