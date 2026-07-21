<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/asset/css/style.css">

  <title>Document</title>
</head>
<body>
  <h1>Plats</h1>

  <?php 
    /** @var PlatController $plats */
  ?>

<?php foreach ($plats as $plat): ?>
  <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <img
      src="<?= htmlspecialchars($plat->getImagePlat()) ?>"
      alt="<?= htmlspecialchars($plat->getTitre()) ?>"
      class="w-70 h-48 object-cover"
    >
    <div class="p-4">
      <h3 class="text-lg font-bold"><?= htmlspecialchars($plat->getLibelle()) ?></h3>
      <h3 class="text-lg font-semibold"><?= htmlspecialchars($plat->getTitre()) ?></h3>
      <p class="text-gray-600 text-sm"><?= htmlspecialchars($plat->getDescriptionPlat()) ?></p>
      <p class="text-gray-800 font-medium mt-2"><?= htmlspecialchars($plat->getPrixPersonne()) ?> €</p>
      <p class="text-gray-800 font-medium mt-2">Id :<?= htmlspecialchars($plat->getPlatId()) ?></p>
      <?php foreach ($plat->getAllergenes() as $allergene): ?>
  <p class="text-gray-800 font-medium mt-2"><?= htmlspecialchars($allergene->getLibelle()) ?></p>
<?php endforeach; ?>

      <p class="text-sm mt-1">
        Stock : <?= htmlspecialchars($plat->getStockPlat()) ?>
        <?php if (!$plat->isPlatActif()): ?>
          <span class="text-red-500 font-semibold">(Indisponible)</span>
        <?php endif; ?>
      </p>
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