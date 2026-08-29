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
    <h3>Bonne nouvelle ! </h3>
    <p>
      
      Votre commande a été acceptée par notre équipe. 
      Nous allons maintenant préparer votre prestation avec soin. 
      Date de livraison prévue le <?= $dateLivraison ?>. 
      A l'adresse suivante : <?= $adresseLivraison ?>.
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