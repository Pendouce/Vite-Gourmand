<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Annulation de commande</title>
</head>
<body>
  <?php /** @var string $prenom */; ?>
  <?php /** @var string $nbCommande */; ?>
  <?php /** @var string $motif */; ?>
  <h1>Bonjour <?= $prenom ?>,</h1>
  <div>
    <p>
      Nous sommes désolés de vous informer que votre commande a dû être annulée par notre équipe.
    </p>
    <p>
      <strong>Commande n°<?= $nbCommande ?></strong>
    </p>
    <p>
      Motif indiqué : <?= $motif ?>
    </p>
    <p>
      Pour toute question ou pour organiser une nouvelle commande, notre équipe reste à votre entière disposition.
    </p>
    <p>
      À bientôt,<br>
      L'équipe Vite et Gourmand
    </p>
  </div>
</body>
</html>
