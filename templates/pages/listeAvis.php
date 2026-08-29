<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Liste avis</title>
</head>
<body>
  <!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Avis</title>
</head>
<body>

  <h1>Avis</h1>

  <?php if (empty($avis)): ?>
    <p>Aucun avis pour le moment.</p>
  <?php else: ?>
    <ul>
      <?php foreach ($avis as $unAvis): ?>
        <li>
          <p>Note : <?= (int)$unAvis->getNote() ?>/5</p>
          <p><?= htmlspecialchars($unAvis->getCommentaire()) ?></p>
          <p><?= $unAvis->getDatePublication()->format('d/m/Y') ?></p>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

</body>
</html>

</body>
</html>