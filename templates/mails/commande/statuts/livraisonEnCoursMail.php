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
  <?php /** @var string $dateLivraison */; ?>
  <?php /** @var string $adresseLivraison */; ?>

  <h1>Bonjour <?= $prenom ?>,</h1>
  <div>
    <p>
      <strong>Commande n°<?= $nbCommande ?></strong>
    </p>
    <p>
      Votre commande est actuellement en cours de livraison, a l'adresse suivante <?= $adresseLivraison ?>.
      Créneau estimé : <?= $dateLivraison ?>. 
      Merci de vous assurer qu’une personne soit présente pour la réception.
    </p>
    <p>
      Vous recevrez un nouvel e-mail a chaque etapes de votre commande.
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