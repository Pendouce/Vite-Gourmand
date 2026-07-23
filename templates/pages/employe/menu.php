<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h1>Menu</h1>

  <?php
/** @var array $menus */
?>

  <?php foreach ($menus as $menu): ?>
    <div>
        <h2><?= $menu->getTitre() ?></h2>
        <p><?= $menu->getPrixPersonne() ?> € /personne</p>
        <p>Minimum <?= $menu->getNombrePersonneMin() ?> personnes</p>
        <p><?= $menu->getConditions() ?></p>

<img src="<?= $menu->getImageMenu() ?>" alt="<?= $menu->getTitre() ?>" style="width: 300px; height: auto; object-fit: cover;">

        <ul>
            <?php foreach ($menu->getPlat() as $plat): ?>
                <li><?= $plat->getTitre() ?></li>
            <?php endforeach; ?>
        </ul>

        <p>Allergènes :
        <?php foreach ($plat->getAllergenes() as $allergene): ?>
            <?= $allergene->getLibelle() ?>
        <?php endforeach; ?>


        <p>Régime :
            <?php foreach ($menu->getRegime() as $regime): ?>
                <?= $regime->getLibelle() ?>
            <?php endforeach; ?>
        </p>

        <p>Thème :
            <?php foreach ($menu->getTheme() as $theme): ?>
                <?= $theme->getLibelle() ?>
            <?php endforeach; ?>
        </p>

        <p>Événement :
            <?php foreach ($menu->getEvenement() as $evenement): ?>
                <?= $evenement->getLibelle() ?>
            <?php endforeach; ?>
        </p>

        <p>Stock dispo : <?= $menu->getStockDispo() ?></p>

        <p>Actif : <?= $menu->isMenuActif() ? 'Oui' : 'Non' ?></p>

        <p <?= $menu->getMenuId() ?>></p>
    </div>
<?php endforeach; ?>



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
</body>
</html>