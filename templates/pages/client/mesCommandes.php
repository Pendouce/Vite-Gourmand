<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mes Commandes</title>
</head>
<body>
  <table>
  <thead>
    <tr>
      <th>N° Commande</th>
      <th>Menu(s)</th>
      <th>Prestation(s)</th>
      <th>Boisson(s)</th>
      <th>Date livraison</th>
      <th>Prix total</th>
      <th>Statut</th>
      <th>Id</th>
    </tr>
  </thead>
  <tbody>
    <?php /** @var CommandeController $commandes*/ ?>
    <?php foreach ($commandes as $commande): ?>
      <tr>
        <td><?= htmlspecialchars($commande->getNbCommande()) ?></td>

        <td>
          <?php if (!empty($commande->getCommandeMenus())): ?>
            <?= htmlspecialchars(implode(', ', array_map(fn($m) => $m->getMenu()->getTitre(), $commande->getCommandeMenus()))) ?>
          <?php else: ?>
            -
          <?php endif; ?>
        </td>

        <td>
          <?php if (!empty($commande->getCommandePrestations())): ?>
            <?= htmlspecialchars(implode(', ', array_map(fn($p) => $p->getPrestation()->getNomPresta(), $commande->getCommandePrestations()))) ?>
          <?php else: ?>
            -
          <?php endif; ?>
        </td>

        <td>
          <?php if (!empty($commande->getCommandeBoissons())): ?>
            <?= htmlspecialchars(implode(', ', array_map(fn($b) => $b->getBoisson()->getNomBoisson(), $commande->getCommandeBoissons()))) ?>
          <?php else: ?>
            -
          <?php endif; ?>
        </td>

        <td><?= htmlspecialchars($commande->getDateLivraison()->format('d/m/Y')) ?></td>

        <td><?= number_format($commande->getPrixTotal(), 2, ',', ' ') ?> €</td>

        <td><?= htmlspecialchars($commande->getLibelle()) ?></td>

        <td><?= htmlspecialchars($commande->getCommandeId()) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</body>
</html>