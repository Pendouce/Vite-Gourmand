<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Test filtre commandes</title>
</head>
<body>
    <?php 
    /** @var array $commandes */
    /** @var array $status */ ?>

<form method="GET" action="/commandesFiltre">

    <label for="nb_commande">N° commande</label>
    <input type="text" id="nb_commande" name="nb_commande"
           value="<?= htmlspecialchars($_GET['nb_commande'] ?? '') ?>">

    <label for="user">Client</label>
    <input type="text" id="user" name="user"
           value="<?= htmlspecialchars($_GET['user'] ?? '') ?>">

    <label for="menu">Menu</label>
    <input type="text" id="menu" name="menu"
           value="<?= htmlspecialchars($_GET['menu'] ?? '') ?>">

    <label for="boisson">Boisson</label>
    <input type="text" id="boisson" name="boisson"
           value="<?= htmlspecialchars($_GET['boisson'] ?? '') ?>">

    <label for="status_id">Statut</label>
    <select id="status_id" name="status_id">
        <option value="">Tous</option>
        <?php foreach ($status as $s): ?>
            <option value="<?= $s->getStatusId() ?>"
                <?= ($_GET['status_id'] ?? '') == $s->getStatusId() ? 'selected' : '' ?>>
                <?= htmlspecialchars($s->getLibelle()) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Rechercher</button>
    <button type="button" onclick="window.location.href='/commandesFiltre'">Réinitialiser</button>

</form>

<hr>

<?php if (empty($commandes)): ?>
    <p>Aucune commande trouvée.</p>
<?php else: ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>N° commande</th>
            <th>Client</th>
            <th>Statut</th>
        </tr>
        <?php foreach ($commandes as $commande): ?>
            <tr>
                <td><?= htmlspecialchars($commande->getNbCommande()) ?></td>
                <td><?= htmlspecialchars($commande->getUser()->getNom()) ?></td>
                <td>
                    <?php foreach ($status as $s): ?>
                        <?php if ($s->getStatusId() === $commande->getStatusId()): ?>
                            <?= htmlspecialchars($s->getLibelle()) ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>


</body>
</html>
