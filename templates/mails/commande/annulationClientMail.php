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
  <h1>Bonjour <?= $prenom ?>,</h1>
  <div>
    <p>
      Nous vous confirmons l'annulation de votre commande à votre demande.
    </p>
    <p>
      <strong>Commande n°<?= $nbCommande ?></strong>
    <p>
      Si cette annulation ne correspond pas à votre demande, ou pour toute question, n'hésitez pas à nous contacter.
    </p>
    <p>
      À bientôt,<br>
      L'équipe Vite et Gourmand
    </p>
  </div>
</body>
</html>
