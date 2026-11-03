<?php /** @var array $prestations */?>
<?php /** @var array $prestationsParType */?>
<?php /** @var array $typeDePresta */?>
<?php /** @var object $role */?>
<?php /** @var string $csrfToken */ ?>

<?php /** @var string $modeCommande */?>

<input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">

  <div  class="blockRetour w-full py-2 mb-2">
    <?php if (isset($_SESSION['succes'])): ?>
      <p class="succes">
          <?= $_SESSION['succes'] ?>
      </p>
      <?php unset($_SESSION['succes']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['erreur'])): ?>
      <p class="erreur"><?= $_SESSION['erreur'] ?></p>
      <?php unset($_SESSION['erreur']); ?>
    <?php endif; ?>
  </div>

  <?php if(!$modeCommande && ($role === ROLE_ADMIN || $role === ROLE_EMPLOYE)): ?>

    <div class="flex justify-between items-center gap-3">

      <div>
        <button id="btnGererTypePresta" class="flex justify-center border border-primary/50 bg-primary/80 text-fond-carte rounded-2xl py-2 px-4 md:py-4 md:px-6 text-lg font-medium w-fit" type="button">Gerer les types</button>
      </div>
      

      <div class="flex justify-center border border-primary/50 bg-primary/80 text-fond-carte rounded-2xl py-2 px-4 md:py-4 md:px-6 text-lg font-medium w-fit">
        <a href="/creerPrestation">Nouvelle prestation</a>
      </div>
    </div>
  <?php endif; ?>

  <p class="text-center text-lg py-8">Completez votre evenement avec nos services professionels et notre location de materiel.</p>
 
    <?php foreach($prestationsParType as $typeDePrestaLibelle => $prestations): ?>
      <section class="py-8">
        <h2 class="font-h2 uppercase underline underline-offset-4 text-3xl text-center text-primary font-medium lg:text-4xl py-8 md:py-10"><?= htmlspecialchars($typeDePrestaLibelle) ?></h2>
        <div class="grid grid-cols-2 gap-4 sm:gap-0">
          <?php foreach($prestations as $prestation): ?>
          <?php if(count($prestation->getContenuPresta()) > 1): ?>
            <div class="cart-presta-sm col-span-1 flex flex-col gap-4">
              <div class="blockRetour w-full mb-1"></div>
              <div class="flex flex-col gap-2">
                <img class="w-15 object-cover self-center" src="<?= htmlspecialchars($prestation->getImgPresta()) ?>" alt="">
                <h4 class="text-lg"><?= htmlspecialchars($prestation->getNomPresta()) ?></h4>
              </div>
              <div>
                <ul class="list-disc marker:text-xs">
                  <?php foreach($prestation->getContenuPresta() as $contenuePresta): ?>
                    <li class="font-light"><?= htmlspecialchars($contenuePresta) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
              <div class="flex flex-col items-center gap-4">
                <p class="text-primary">A partir de <?= htmlspecialchars($prestation->getPrixPresta()) ?>€</p>
                <?php if(!$modeCommande): ?>
                  <a class="btn-detail py-1 w-fit" href="/detailPrestation?id=<?= $prestation->getPrestationId() ?>">Details</a>
                <?php else: ?>
                  <button class="btn-detail py-1 w-fit" type="button" data-id="<?= $prestation->getPrestationId() ?>">Details</button>
                <?php endif; ?>
              </div>

              <?php if(!$modeCommande && ($role === ROLE_ADMIN || $role === ROLE_EMPLOYE)): ?>
              <div class="self-end">
                <label class="text-sm">Statut: </label>
                  <label class="flex gap-1">
                    <span class="toggleLabelText text-texte/60 text-xs"><?= $prestation->isPrestationActif() == 1 ? 'Actif' : 'Inactif' ?></span>
                  <input class="peer appearance-none togglePrestationInput" type="checkbox" name="prestation_actif" data-id="<?= $prestation->getPrestationId() ?>" <?= $prestation->isPrestationActif() == 1 ? 'checked' : '' ?>>
                  <span class="toggle"></span>
                </label>
              </div>
            <?php endif; ?>
            </div>
          <?php else: ?>
            <div class="cart-presta col-span-full gap-2 relative">
              <div class="blockRetour w-full mb-1"></div>
              <div class="flex flex-col gap-2">
                <img class="w-15 object-cover self-center bg-transparent" src="<?= htmlspecialchars($prestation->getImgPresta()) ?>" alt="">
                <h4 class="text-lg"><?= htmlspecialchars($prestation->getNomPresta()) ?></h4>
              </div>
              <div>
                <?php foreach($prestation->getContenuPresta() as $contenuePresta): ?>
                  <p class="font-light text-center"><?= htmlspecialchars($contenuePresta) ?></p>
                <?php endforeach; ?>
              </div>

              <div class="flex flex-col items-center gap-4">
                <p class="text-primary">A partir de <?= htmlspecialchars($prestation->getPrixPresta()) ?>€</p>
                <?php if(!$modeCommande): ?>
                  <a class="btn-detail py-1 w-fit" href="/detailPrestation?id=<?= $prestation->getPrestationId() ?>">Details</a>
                  <?php else: ?>
                    <button class="btn-detail py-1 w-fit" data-id="<?= $prestation->getPrestationId() ?>">Details</button>
                    <?php endif; ?>
              </div>

              <?php if(!$modeCommande && ($role === ROLE_ADMIN || $role === ROLE_EMPLOYE)): ?>
                <div class="absolute bottom-3 right-4">
                  <label class="text-sm">Statut: </label>
                    <label class="flex gap-1">
                      <span class="toggleLabelText text-texte/60 text-xs"><?= $prestation->isPrestationActif() == 1 ? 'Actif' : 'Inactif' ?></span>
                    <input class="peer appearance-none togglePrestationInput" type="checkbox" name="prestation_actif" data-id="<?= $prestation->getPrestationId() ?>" <?= $prestation->isPrestationActif() == 1 ? 'checked' : '' ?>>
                    <span class="toggle"></span>
                  </label>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
          </div>
      </section>
    <?php endforeach; ?>