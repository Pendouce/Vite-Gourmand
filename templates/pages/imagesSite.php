<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="asset/css/style.css">
  <title>Document</title>
</head>
<body>
  <?php if (empty($images)): ?>
  <p>Aucune image enregistrée.</p>
<?php else: ?>
  <div class="grid-images">
    <?php foreach ($images as $image): ?>
      <div class="carte-image">
        <img src="<?= htmlspecialchars($image->getChemin()) ?>" alt="<?= htmlspecialchars($image->getNomImg()) ?>">
        <p><?= htmlspecialchars($image->getNomImg()) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
</body>
</html>