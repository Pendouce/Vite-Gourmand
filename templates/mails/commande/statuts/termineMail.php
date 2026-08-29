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
  <?php /** @var string $lienAvis */; ?>

  
  <h1>Bonjour <?= $prenom ?>,</h1>
  <div>
    <p>
      <strong>Commande n°<?= $nbCommande ?></strong>
    </p>
    <p>
      Votre commande est maintenant clôturée. 
      Merci d’avoir fait confiance à Vite et Gourmand pour votre événement ! 
      Votre avis compte énormément pour nous et pour la communauté. 
      Pourriez-vous prendre un instant pour partager votre expérience ? 
      <a href="<?= $lienAvis ?>"><?= $lienAvis ?></a>
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

