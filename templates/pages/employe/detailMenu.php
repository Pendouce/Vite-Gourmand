<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/asset/css/style.css">

  <title>Document</title>
</head>
<body>
  <h1>Detail Menu</h1>

  <div class="max-w-3xl mx-auto p-6">

    <?php
/** @var Menu $menu */
?>
    
  <?php if($menu->getImageMenu()): ?>
    <?php var_dump($menu->getImageMenu()) ?>
    <img src="<?= htmlspecialchars($menu->getImageMenu()) ?>" 
         alt="<?= htmlspecialchars($menu->getTitre()) ?>" 
         class="w-70 h-64 object-cover rounded-lg mb-6">
  <?php endif; ?>

  <h1 class="text-3xl font-bold mb-2"><?= htmlspecialchars($menu->getTitre()) ?></h1>
  <p class="text-gray-600 mb-4"><?= nl2br(htmlspecialchars($menu->getConditions())) ?></p>

  <div class="flex gap-4 mb-6 text-sm text-gray-700">
    <span>Prix : <?= htmlspecialchars($menu->getPrixPersonne()) ?> € / personne</span>
    <span>Min. <?= htmlspecialchars($menu->getNombrePersonneMin()) ?> personnes</span>
    <span>Stock dispo : <?= htmlspecialchars($menu->getStockDispo()) ?></span>
  </div>

  <h2 class="text-xl font-semibold mt-6 mb-2">Plats inclus</h2>
  <ul class="list-disc list-inside mb-6">
    <?php foreach($menu->getPlat() as $plat): ?>
      <li><?= htmlspecialchars($plat->getTitre()) ?></li>
    <?php endforeach; ?>
  </ul>

  <h2 class="text-xl font-semibold mb-2">Allergènes</h2>
  <div class="flex flex-wrap gap-2 mb-6">

      <?php foreach($menu->getAllergene() as $allergene): ?>
        <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded">
          <?= htmlspecialchars($allergene->getLibelle()) ?>
        </span>
      <?php endforeach; ?>

  </div>

  <h2 class="text-xl font-semibold mb-2">Thèmes</h2>
  <div class="flex flex-wrap gap-2 mb-6">
    <?php foreach($menu->getTheme() as $theme): ?>
      <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">
        <?= htmlspecialchars($theme->getLibelle()) ?>
      </span>
    <?php endforeach; ?>
  </div>

</div>

</body>
</html>