<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modification de commande</title>
</head>
<body>
  <?php /** @var string $prenom */; ?>
  <?php /** @var string $nbCommande */; ?>
  <?php /** @var string $motif */; ?>
  <h1>Bonjour <?= $prenom ?>,</h1>
  <div>
    <p>
      Nous vous informons que votre commande a été modifiée par notre équipe.
    </p>
    <p>
      <strong>Commande n°<?= $nbCommande ?></strong>
    </p>
    <p>
      Motif indiqué : <?= $motif ?>
    </p>
    <p>
      Pour connaître le détail des modifications apportées ou pour toute question, n'hésitez pas à nous contacter.
    </p>
    <p>
      À bientôt,<br>
      L'équipe Vite et Gourmand
    </p>
  </div>
</body>
</html>
