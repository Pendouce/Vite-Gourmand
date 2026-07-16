<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php    /** @var string $prenom */;?>

  <h1>Bonjour <?= $prenom ?>,</h1>
  <div>

    <p>
      Votre compte employé sur <strong>Vite et Gourmand</strong> a bien été créé.
    </p>

    <p>
      Un mot de passe temporaire vous a été attribué pour votre première connexion :
    </p>

    <p>
      <?php    /** @var string $mdp */;?>
      <strong>Mot de passe temporaire :</strong> <?= $mdp ?>
    </p>

    <p>
      Pour des raisons de sécurité, nous vous recommandons de modifier ce mot de passe dès votre première connexion depuis votre espace personnel.
    </p>

    <p>
      À bientôt,<br>
      L'équipe Vite et Gourmand
    </p>
  </div>
</body>
</html>