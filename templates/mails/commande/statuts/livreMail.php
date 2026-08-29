<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirmation de commande</title>
</head>
<body>
  <?php /** @var string $prenom */; ?>
  <?php /** @var string $nbCommande */; ?>
  <?php /** @var string $adresseLivraison */; ?>

  <h1>Bonjour <?= $prenom ?>,</h1>
  <div>
    <p>
      <strong>Commande n°<?= $nbCommande ?></strong>
    </p>
    <p>
      Votre commande vient d’être livrée a l'adresse suivante :
      <?= $adresseLivraison ?>.
      Nous espérons que tout est conforme à vos attentes. 
      Bonne dégustation et bon événement !
    </p>
    <p>
      Nous vous remercions et restons à votre disposition
      pour toute question.
    </p>
    <p>
      À bientôt,<br>
      L'équipe Vite et Gourmand
    </p>
  </div>
</body>
</html>