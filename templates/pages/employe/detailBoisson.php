<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="asset/css/style.css">
  <title>Boisson</title>
</head>
<body>
<h1>Detail Boisson</h1>

    <div class="max-w-3xl mx-auto p-6">

    <?php
/** @var Boisson $boisson */
?>
    
  <h1 class="text-3xl font-bold mb-2"><?= htmlspecialchars($boisson->getNomBoisson()) ?></h1>
  
  <?php if($boisson->getphotoBoisson()): ?>
    <?php //var_dump($boisson->getphotoBoisson()) ?>
    <img src="<?= htmlspecialchars($boisson->getphotoBoisson()) ?>" 
         alt="<?= htmlspecialchars($boisson->getNomBoisson()) ?>" 
         class="w-70 h-64 object-cover rounded-lg mb-6">
  <?php endif; ?>

  <div class="flex gap-4 mb-6 text-sm text-gray-700">
    <p>Prix : <?= htmlspecialchars($boisson->getPrixBoisson()) ?> </p>
    <p> Alcool : <?= htmlspecialchars($boisson->isAlcool()) ?> </p>
    <span>Stock dispo : <?= htmlspecialchars($boisson->getStockBoisson()) ?></span>
    <span>Boisson actif : <?= htmlspecialchars($boisson->isBoissonActif()) ? 'Oui' : 'Non' ?></span>
  </div>


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
</body>
</html>