<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=, initial-scale=1.0">
  <link rel="stylesheet" href="/asset/css/style.css">
  <title>Document</title>
</head>
<body>
  <h1>Detail Plat</h1>

  <div>
      <?php 
    /** @var PlatController $plat */
  ?>
    <h1><?= htmlspecialchars($plat->getTitre()) ?></h1>

     <img
      src="<?= htmlspecialchars($plat->getImagePlat()) ?>"
      alt="<?= htmlspecialchars($plat->getTitre()) ?>"
      class="w-70 h-48 object-cover"
    >

    <p><?= htmlspecialchars($plat->getDescriptionPlat()) ?></p>

    <p><?= htmlspecialchars($plat->getPrixPersonne()) ?> €</p>

    <p>Stock : <?= htmlspecialchars($plat->getStockPlat()) ?></p>

    <div>
        <h2>Allergènes :</h2>
        <?php foreach ($plat->getAllergenes() as $allergene): ?>
            <p><?= htmlspecialchars($allergene->getLibelle()) ?></p>
        <?php endforeach; ?>
    </div>

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

</body>
</html>