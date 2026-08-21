<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Commande</title>
</head>
<body>

<?php /** @var CommandeController $commande */ ?>
  <h1>Détail commande #<?= $commande->getNbCommande() ?></h1>

<h2>Infos générales</h2>
<pre>
Statut : <?= $commande->getLibelle() ?>
Date livraison : <?= $commande->getDateLivraison()->format('d/m/Y H:i') ?>
Total : <?= $commande->getPrixTotal() ?>
</pre>

<h2>Boissons</h2>
<?php foreach ($commande->getCommandeBoissons() as $commandeBoisson): ?>
    <pre><?php print_r($commandeBoisson); ?></pre>
<?php endforeach; ?>

<h2>Menus</h2>
<?php foreach ($commande->getCommandeMenus() as $commandeMenu): ?>
    <pre><?php print_r($commandeMenu); ?></pre>
<?php endforeach; ?>

<h2>Prestations</h2>
<?php foreach ($commande->getCommandePrestations() as $commandePrestation): ?>
    <pre><?php print_r($commandePrestation); ?></pre>
<?php endforeach; ?>

</body>
</html>