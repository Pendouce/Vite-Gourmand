<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="asset/css/style.css">
  <title>Document</title>
</head>
<body>
  <h1>Notre équipe</h1>

<?php if (empty($membres)): ?>
  <p>Aucun membre enregistré.</p>
<?php else: ?>
  <div class="grid-equipe">
    <?php foreach ($membres as $membre): ?>
      <div class="carte-membre">
        <?php if ($membre->getPhoto()): ?>
          <img src="/<?= htmlspecialchars($membre->getPhoto()) ?>" alt="<?= htmlspecialchars($membre->getPrenom()) ?>">
        <?php endif; ?>
        <h3><?= htmlspecialchars($membre->getPrenom()) ?> <?= htmlspecialchars($membre->getNom()) ?></h3>
        <p class="poste"><?= htmlspecialchars($membre->getPoste()) ?></p>
        <?php if ($membre->getDescription()): ?>
          <p class="description"><?= htmlspecialchars($membre->getDescription()) ?></p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

</body>
</html>