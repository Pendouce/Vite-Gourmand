<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h1>Gestion Employe</h1>

  <?php if (isset($_SESSION['erreur'])): ?>
    <p class="erreur"><?= $_SESSION['erreur'] ?></p>
    <?php unset($_SESSION['erreur']); ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['succes'])): ?>
    <p class="succes">
        <?= $_SESSION['succes'] ?>
    </p>
    <?php unset($_SESSION['succes']); ?>
  <?php endif; ?>
  <?php

    //var_dump($affiche->afficheUtilisateur());
   /** @var UserController $listeEmploye */;


    foreach($listeEmploye as $employe){
      echo '<pre>';
      print_r($employe);
      echo '</pre>';
    };
  ?>

</body>
</html>