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
    </tr>
  </thead>
  <tbody>
    <?php /** @var CommandeController $commandes*/ ?>
    <?php foreach ($commandes as $commande): ?>
      <tr>
        <td><?= htmlspecialchars($commande->getNbCommande()) ?></td>

        <td>
          <?php if (!empty($commande->getMenus())): ?>
            <?= htmlspecialchars(implode(', ', array_map(fn($m) => $m->getTitre(), $commande->getMenus()))) ?>
          <?php else: ?>
            -
          <?php endif; ?>
        </td>

        <td>
          <?php if (!empty($commande->getPrestations())): ?>
            <?= htmlspecialchars(implode(', ', array_map(fn($p) => $p->getNomPresta(), $commande->getPrestations()))) ?>
          <?php else: ?>
            -
          <?php endif; ?>
        </td>

        <td>
          <?php if (!empty($commande->getBoissons())): ?>
            <?= htmlspecialchars(implode(', ', array_map(fn($b) => $b->getNomBoisson(), $commande->getBoissons()))) ?>
          <?php else: ?>
            -
          <?php endif; ?>
        </td>

        <td><?= htmlspecialchars($commande->getDateLivraison()->format('d/m/Y')) ?></td>

        <td><?= number_format($commande->getPrixTotal(), 2, ',', ' ') ?> €</td>

        <td><?= htmlspecialchars($commande->getLibelle()) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>


</body>
</html>