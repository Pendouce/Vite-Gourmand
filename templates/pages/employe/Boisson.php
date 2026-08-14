<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="asset/css/style.css">
  <title>Boisson</title>
</head>
<body>
<h1></h1>
<?php /** @var BoissonController $boissons */ ?>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php foreach ($boissons as $boisson): ?>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">

    <div>
        <h2><?= $boisson->getNomBoisson() ?></h2>

        <img src="<?= $boisson->getPhotoboisson() ?>" alt="<?= $boisson->getNomBoisson() ?>" style="width: 300px; height: auto; object-fit: cover;">

        <p>Prix :
          <?= htmlspecialchars($boisson->getPrixBoisson()) ?>
        <p>Alcool :
            <?php if ($boisson->isAlcool()): ?>
                <?= "Avec alcool"?>
            <?php else :?>
              <?= "Sans alcool"?>
            <?php endif; ?>
        </p>

        <p>Stock dispo : <?= $boisson->getStockBoisson() ?></p>

        <p>Actif : <?= $boisson->isboissonActif() ? 'Oui' : 'Non' ?></p>

        <p>Id :<?= $boisson->getboissonId() ?></p>
    </div>
  </div>
  </div>
<?php endforeach; ?>

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
</body>
</html>