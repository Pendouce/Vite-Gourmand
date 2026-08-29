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
  <?php /** @var string $adresseVG */; ?>
  <?php /** @var string $tauxRetard */; ?>
  <?php /** @var string $dateRetourMax */; ?>
  
  <h1>Bonjour <?= $prenom ?>,</h1>
  <div>
    <p>
      <strong>Commande n°<?= $nbCommande ?></strong>
    </p>
    <p>
      Nous espérons que votre événement s’est bien déroulé ! 
      Votre commande est maintenant en attente de récupération du matériel mis à disposition. 
      Adresse de récupération : <?= $adresseVG ?>. 
    </p>
    <p>
      Vous disposez d'un delais de 10 jours a partir de la date de prestation avant l'application du taux de retard,
      qui s'eleve a <?= $tauxRetard ?> €. 
    </p>
    <p>
      Date limite pour la restitution du materiel <?= $dateRetourMax ?>
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