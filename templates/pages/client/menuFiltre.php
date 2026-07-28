<?php
/**
 * Vue de test : templates/pages/client/menuFiltre.php
 *
 * Hypothèses faites (à adapter selon tes vraies entités/services) :
 * - $menus est un tableau d'objets Menu hydratés (avec ->getTitre(), ->getPrixPersonne(), etc.)
 * - Menu possède : getId(), getTitre(), getDescription(), getPrixPersonne(),
 *   getNombrePersonneMin(), getImageMenu(), getEvenement()->getLibelle(),
 *   getTheme()->getLibelle(), getRegime()->getLibelle(), getStock()
 * - Les listes d'événements/thèmes/régimes pour remplir les selects ne sont
 *   pas encore passées par le contrôleur : ici elles sont codées en dur
 *   juste pour que tu puisses tester visuellement. Il faudra les remplacer
 *   par de vraies données (ex: $evenements, $themes, $regimes passés au render).
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Menus filtrés - VT Gourmand</title>
  <link rel="stylesheet" href="/asset/css/style.css">
</head>
<body class="bg-gray-50 min-h-screen">

  <main class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Nos menus</h1>

    <!-- FORMULAIRE DE FILTRE : méthode GET, comme discuté -->
    <form method="GET" action="/menuFiltre" class="bg-white rounded-lg shadow p-6 mb-8 grid gap-4 md:grid-cols-2">

      <!-- Événement : checkboxes multiples -> evenement_id[] -->
      <?php
        $evenements = [
          1 => "Mariage", 2 => "Anniversaire", 3 => "Baptême", 4 => "Cocktail",
          5 => "Brunch", 6 => "Séminaire d'entreprise", 7 => "Noël", 8 => "Pâques",
          9 => "Aïd", 10 => "Nouvel an chinois", 11 => "Pessah", 12 => "Roch Hachana", 13 => "Hanoucca",
        ];
      ?>
      <fieldset>
        <legend class="font-semibold text-gray-700 mb-2">Événement</legend>
        <div class="grid grid-cols-2 gap-1 max-h-40 overflow-y-auto">
          <?php foreach ($evenements as $id => $libelle): ?>
            <label class="flex items-center gap-2 text-sm">
              <input type="checkbox" name="evenement_id[]" value="<?= $id ?>"
                <?= isset($_GET['evenement_id']) && in_array((string)$id, (array)$_GET['evenement_id']) ? 'checked' : '' ?>>
              <?= htmlspecialchars($libelle) ?>
            </label>
          <?php endforeach; ?>
        </div>
      </fieldset>

      <!-- Thème : select simple -->
      <?php $themes = [1 => "Terroir", 2 => "Europe", 3 => "Afrique", 4 => "Asie", 5 => "Amérique"]; ?>
      <div>
        <label class="block font-semibold text-gray-700 mb-2" for="theme_id">Thème</label>
        <select name="theme_id" id="theme_id" class="border rounded px-3 py-2 w-full">
          <option value="">Tous</option>
          <?php foreach ($themes as $id => $libelle): ?>
            <option value="<?= $id ?>" <?= ($_GET['theme_id'] ?? '') === (string)$id ? 'selected' : '' ?>>
              <?= htmlspecialchars($libelle) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Régime : select simple -->
      <?php $regimes = [1 => "Végétarien", 2 => "Vegan", 3 => "Sans gluten", 4 => "Halal", 5 => "Casher"]; ?>
      <div>
        <label class="block font-semibold text-gray-700 mb-2" for="regime_id">Régime</label>
        <select name="regime_id" id="regime_id" class="border rounded px-3 py-2 w-full">
          <option value="">Tous</option>
          <?php foreach ($regimes as $id => $libelle): ?>
            <option value="<?= $id ?>" <?= ($_GET['regime_id'] ?? '') === (string)$id ? 'selected' : '' ?>>
              <?= htmlspecialchars($libelle) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Prix par personne (max) -->
      <div>
        <label class="block font-semibold text-gray-700 mb-2" for="prix_personne">Prix max / personne (€)</label>
        <input type="number" step="0.01" min="0" name="prix_personne" id="prix_personne"
          value="<?= htmlspecialchars($_GET['prix_personne'] ?? '') ?>"
          class="border rounded px-3 py-2 w-full" placeholder="ex: 25.50">
      </div>

      <!-- Nombre de personnes minimum -->
      <div>
        <label class="block font-semibold text-gray-700 mb-2" for="nombre_personne_min">Nombre de personnes max.</label>
        <input type="number" min="0" name="nombre_personne_min" id="nombre_personne_min"
          value="<?= htmlspecialchars($_GET['nombre_personne_min'] ?? '') ?>"
          class="border rounded px-3 py-2 w-full" placeholder="ex: 10">
      </div>

      <div class="md:col-span-2 flex gap-3">
        <button type="submit" class="bg-emerald-600 text-white px-5 py-2 rounded hover:bg-emerald-700">
          Filtrer
        </button>
        <a href="/menus" class="px-5 py-2 rounded border border-gray-300 text-gray-600 hover:bg-gray-100">
          Réinitialiser
        </a>
      </div>
    </form>

    <!-- RÉSULTATS -->
    <?php if (empty($menus)): ?>
      <p class="text-gray-500 italic">Aucun menu ne correspond à ces critères.</p>
    <?php else: ?>
      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($menus as $menu): ?>
          <a href="/menu?id=<?= $menu->getMenuId() ?>"
             class="bg-white rounded-lg shadow overflow-hidden block hover:shadow-lg hover:-translate-y-0.5 transition">
            <?php if (method_exists($menu, 'getImageMenu') && $menu->getImageMenu()): ?>
              <img src="<?= htmlspecialchars($menu->getImageMenu()) ?>"
                   alt="<?= htmlspecialchars($menu->getTitre()) ?>"
                   class="w-full h-40 object-cover">
            <?php endif; ?>
            <div class="p-4">
              <h2 class="font-bold text-lg text-gray-800"><?= htmlspecialchars($menu->getTitre()) ?></h2>
              <?php if (method_exists($menu, 'getDescription')): ?>
                <p class="text-gray-600 text-sm mt-1"><?= htmlspecialchars($menu->getDescription()) ?></p>
              <?php endif; ?>
              <div class="mt-3 flex justify-between items-center text-sm">
                <span class="font-semibold text-emerald-700">
                  <?= number_format((float)($menu->getPrixPersonne() ?? 0), 2) ?> € / pers.
                </span>
                <?php if (method_exists($menu, 'getNombrePersonneMin')): ?>
                  <span class="text-gray-500">min. <?= $menu->getNombrePersonneMin() ?> pers.</span>
                <?php endif; ?>
              </div>
              <?php if (method_exists($menu, 'getStock')): ?>
                <p class="text-xs text-gray-400 mt-2">Stock disponible : <?= $menu->getStock() ?></p>
              <?php endif; ?>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </main>
</body>
</html>
