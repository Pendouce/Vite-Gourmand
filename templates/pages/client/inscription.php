<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h1>Inscription</h1>
  

  <?php 

    use App\Repository\UserRepository;

    
    $affiche = new UserRepository();

    //var_dump($affiche->afficheUtilisateur());
    $aff = $affiche->afficheUtilisateur();

    foreach($aff as $utilisateur){
      echo '<pre>';
      print_r($utilisateur);
      echo '</pre>';
    };

    //var_dump($affiche->afficheUtilisateurById(2));

    /* use App\Controller\UserController;

    $us = new UserController;
    $us->inscription(); */
  ?>
  <?php if(isset($erreur)): ?>
    <p class="erreur"><?= $erreur ?></p>
<?php endif; ?>

</body>
</html>