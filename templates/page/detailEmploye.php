<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h1>Detail Employe</h1>

  <?php  ?>
  <?php /** @var obj $infoEmploye */?>

    <p>Nom : <?= $infoEmploye->getNom() ?></p>
    <p>Prénom : <?= $infoEmploye->getPrenom() ?></p>
    <p>Email : <?= $infoEmploye->getEmail() ?></p>
    <p>Téléphone : <?= $infoEmploye->getTelephone() ?></p>
    <p>Ville : <?= $infoEmploye->getVille() ?></p>
    <p>Code postal : <?= $infoEmploye->getCodePostal() ?></p>
    <p>Adresse : <?= $infoEmploye->getAdresse() ?></p>
</body>
</html>