<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/asset/css/style.css">

  <title>Document</title>
</head>
<body>
  <h1>Prestation</h1>

    
    <?php /** @var TypeDePrestaController $prestations */ ?>

   <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php foreach ($prestations as $prestation): ?>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">

        <img src="<?= htmlspecialchars($prestation->getImgPresta()) ?>" 
             alt="<?= htmlspecialchars($prestation->getNomPresta()) ?>"
             class="w-full h-48 object-cover">

        <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-800">
                <?= htmlspecialchars($prestation->getNomPresta()) ?>
            </h3>

            <p class="text-sm text-gray-600 mt-1">
                <?= htmlspecialchars($prestation->getLibelle()) ?>
            </p>

            <?php $contenu = $prestation->getContenuPresta(); ?>
            <?php if (!empty($contenu)): ?>
                <ul class="text-sm text-gray-600 mt-2 list-disc list-inside">
                    <?php foreach ($contenu as $item): ?>
                        <li><?= htmlspecialchars($item) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>


            <p class="text-sm text-gray-500 mt-2">
                <?= htmlspecialchars($prestation->getDescriptionPresta()) ?>
            </p>

            <p class="text-xl font-bold text-gray-900 mt-3">
                <?= htmlspecialchars($prestation->getPrixPresta()) ?> €
            </p>

            <div class="flex gap-2 mt-3">
                <?php if ($prestation->isNecessiteRetour()): ?>
                    <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded-full">
                        Retour nécessaire
                    </span>
                <?php endif; ?>

                <?php if (!$prestation->isPrestationActif()): ?>
                    <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">
                        Inactif
                    </span>
                <?php endif; ?>
            </div>

             <p class="text-sm text-gray-500 mt-2">
              Id : <?= htmlspecialchars($prestation->getPrestationId()) ?>
            </p>

        </div>

    </div>
<?php endforeach; ?>

</div>



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