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
  <h1>Bonjour <?= $prenom ?>,</h1>
  <div>
    <p>
      Nous vous confirmons que votre commande a bien été enregistrée.
    </p>
    <p>
      <strong>Commande n°<?= $nbCommande ?></strong>
    </p>
    <p>
      Votre commande est actuellement en attente de validation par notre équipe.
      Tant que celle-ci n’a pas été acceptée, vous pouvez encore la modifier,
      à l’exception du choix des menus, ou l’annuler si nécessaire.
    </p>
    <p>
      Vous recevrez un e-mail à chaque étape de votre commande afin de suivre
      son évolution.
    </p>
    <p>
      Nous vous remercions pour votre commande et restons à votre disposition
      pour toute question.
    </p>
    <p>
      À bientôt,<br>
      L'équipe Vite et Gourmand
    </p>
  </div>
</body>
</html>