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
      Nous avons bien pris en compte votre demande de réinitialisation de mot de passe.
    </p>

    <p>
      Un nouveau mot de passe temporaire a été généré pour votre compte :
    </p>

    <p>
      <?php    /** @var string $nouveauMdp */;?>
      <strong><?= $nouveauMdp ?></strong>
    </p>

    <p>
      Nous vous recommandons de modifier ce mot de passe dès votre prochaine connexion depuis votre espace personnel.
    </p>

    <p>
      À bientôt,<br>
      L'équipe Vite et Gourmand
    </p>
  </div>
</body>
</html>