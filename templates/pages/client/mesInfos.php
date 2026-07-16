<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h1>Mes infos</h1>

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


  <p>Nom : <?= $infoUtilisateur->getNom() ?></p>
  <p>Prénom : <?= $infoUtilisateur->getPrenom() ?></p>
  <p>Email : <?= $infoUtilisateur->getEmail() ?></p>
  <p>Téléphone : <?= $infoUtilisateur->getTelephone() ?></p>
  <p>Ville : <?= $infoUtilisateur->getVille() ?></p>
  <p>Code postal : <?= $infoUtilisateur->getCodePostal() ?></p>
  <p>Adresse : <?= $infoUtilisateur->getAdresse() ?></p>

</body>
</html>