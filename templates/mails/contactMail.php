<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php /** @var string $prenom */ ?>
  <?php /** @var string $nom */ ?>
  <?php /** @var string $email */ ?>
  <?php /** @var string $description */ ?>

  <h1>Nouveau message de <?= ucfirst($prenom) ?> <?= ucfirst($nom )?></h1>
  <div>

    <p>
      <strong>Email :</strong> <?= $email ?>
    </p>

    <p>
      <strong>Message :</strong><br>
      <?= nl2br($description) ?>
    </p>

    <p>
      À bientôt,<br>
      L'équipe Vite et Gourmand
    </p>
  </div>
</body>
</html>
