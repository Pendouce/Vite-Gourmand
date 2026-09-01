<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h1>Inscription Employe</h1>
  

  <?php 

    use App\Repository\UserRepository;

    /** @var string $csrfToken*/ 
    echo $csrfToken;

    $affiche = new UserRepository();

    //var_dump($affiche->afficheUtilisateur());
    $aff = $affiche->afficheUtilisateur();

    foreach($aff as $utilisateur){
      echo '<pre>';
      print_r($utilisateur);
      echo '</pre>';
    };
  ?>
  <?php if(isset($erreur)): ?>
    <p class="erreur"><?= $erreur ?></p>
  <?php endif; ?>

  <?php if (isset($_SESSION['succes'])): ?>
    <p class="succes">
        <?= $_SESSION['succes'] ?>
    </p>
    <?php unset($_SESSION['succes']); ?>
  <?php endif; ?>

</body>
</html>