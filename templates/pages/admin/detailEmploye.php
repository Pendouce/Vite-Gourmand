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
      <?php /** @var string $csrfToken */ ?>
      <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
      <?php /** @var object $infoEmploye */ ?>
      <div class="flex flex-col col-span-1">
        <label class="font-label" for="nom">Nom</label>
        <input id="nom" class="grand-input" type="text" name="nom" value="<?= $infoEmploye->getNom() ?>" readonly>
      </div>

      <div class="flex flex-col col-span-1">
        <label class="font-label" for="prenom">Prenom</label>
        <input id="prenom" class="grand-input" type="text" name="prenom" value="<?= $infoEmploye->getPrenom() ?>" readonly>
      </div>

      <div class="flex flex-col col-span-2">
        <label class="font-label" for="email">Email</label>
        <input id="email" class="grand-input" type="email" name="email" value="<?= $infoEmploye->getEmail() ?>" readonly>
      </div>

      <div class="flex flex-col col-span-1">
        <label class="font-label" for="telephone">Telephone</label>
        <input id="telephone" class="grand-input" type="tel" name="telephone" value="<?= $infoEmploye->getTelephone() ?>" readonly>
      </div>

      <div class="flex flex-col col-span-2">
        <label class="font-label" for="adresse">Adresse</label>
        <input id="adresse" class="grand-input" type="text" name="adresse" value="<?= $infoEmploye->getAdresse() ?>" readonly>
      </div>

      <div class="flex flex-col col-span-1">
        <label class="font-label" for="code_postal">Code Postal</label>
        <input id="code_postal" class="grand-input" type="text" name="code_postal" value="<?= $infoEmploye->getCodePostal() ?>" readonly>
      </div>

      <div class="flex flex-col col-span-1">
        <label class="font-label" for="ville">Ville</label>
        <input id="ville" class="grand-input" type="text" name="ville" value="<?= $infoEmploye->getVille() ?>" readonly>
      </div>

      <div class="flex justify-center col-span-2 py-6">
          <form action="/supprimerCompteEmploye" method="post" class="inline">
            <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
            <input type="hidden" name="id" value="<?= $infoEmploye->getUserId() ?>">
            <button type="submit" class="btn-form bg-texte/90">Supprimer</button>
          </form>
        </div>
      </div>

  </div>
  
  <?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>
