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
  <h1>Bonjour <?= $prenom ?>,</h1>
  <div>
    <p>
      Nous vous confirmons que votre commande a bien été modifiée selon votre demande.
    </p>
    <p>
      <strong>Commande n°<?= $nbCommande ?></strong>
    </p>
    <p>
      Votre commande reste en attente de validation par notre équipe. Vous pouvez continuer à la modifier
      (à l'exception du choix des menus) ou l'annuler tant qu'elle n'a pas été acceptée.
    </p>
    <p>
      À bientôt,<br>
      L'équipe Vite et Gourmand
    </p>
  </div>
</body>
</html>
