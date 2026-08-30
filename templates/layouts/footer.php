<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h1>Footer</h1>
  <?php /** @var InfoVgController $infos */ ?>

  <p><?= htmlspecialchars($infos->getAdresse()) ?></p>
  <p><?= htmlspecialchars($infos->getTelephone()) ?></p>
  <p><?= htmlspecialchars($infos->getEmail()) ?></p>
  <p><?= htmlspecialchars($infos->getHorairesSemaine()) ?></p>
  <p><?= htmlspecialchars($infos->getHorairesWeekend()) ?></p>
  
</body>
</html>