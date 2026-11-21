<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var object $commandes */ ?>
<?php /** @var string $statut */ ?>

<div class="contenue-page">
  <div class="divBlockRetour w-full pb-10 mb-6">
      <?php if (isset($_SESSION['succes'])): ?>
        <p id="msgSucces" class="succes">
            <?= $_SESSION['succes'] ?>
        </p>
        <?php unset($_SESSION['succes']); ?>
      <?php endif; ?>

      <?php if(isset($_SESSION['erreur'])): ?>
        <p class="erreur"><?= $_SESSION['erreur'] ?></p>
        <?php unset($_SESSION['erreur']); ?>
      <?php endif; ?>

      <!-- <div class="divBlockRetourMsg w-full p-2"></div> -->

      <div id="modalDetailcommande" class="modal-container-commande hidden">
        <div class="modal-commande">
          <button type="button" class="btnFermerModalCommande absolute right-6 top-6 hover:text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 lg:size-8">
              <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
          </button>
            <div id="detailCommandeModal" class="hidden pt-6"></div>
        </div>
      </div>
      

      <!-- Commandes -->
      <div class="grid grid-cols-4 md:grid-cols-8">
        <!-- En tete -->
        <div class="col-span-full">
          <div class="grid grid-cols-4 md:grid-cols-8 gap-3 bg-primary/50 rounded-lg p-2">
            <p class="col-span-1">Commande</p>
            <p class="hidden md:block md:col-span-2 md:justify-self-center">Menu</p>
            <p class="col-span-1">Livraison</p>
            <p class="col-span-1">Prix total</p>
            <p class="col-span-1 md:col-span-2 justify-self-center">Statut</p>
            <p class="hidden md:block md:col-span-1">Action</p>
          </div>
        </div>
        <!-- Recap -->
        <?php foreach($commandes as $commande): ?>
            <div class="divCommande col-span-full gap-3 my-2 p-2" data-id="<?= $commande->getCommandeId() ?>">
              <div class="grid grid-cols-4 md:grid-cols-8 gap-3 bg-fond-carte/60 rounded-2xl px-2 py-6 items-center">
                <p class="col-span-1">#<?= $commande->getNbCommande() ?></p>
                <div class="hidden md:block md:col-span-2">
                  <?php foreach($commande->getCommandeMenus() as $menu): ?>
                    <p><?= htmlspecialchars($menu->getMenu()->getTitre()) ?></p>
                  <?php endforeach; ?>
                </div>
                <p class="col-span-1"><?= htmlspecialchars($commande->getDateLivraison()->format('d/m/Y'))?></p>
                <p class="col-span-1"><?= htmlspecialchars($commande->getPrixTotal()) ?> €</p>
                <p class="col-span-1 md:col-span-2 justify-self-center w-30 h-10 <?= $statut[$commande->getStatusId()] ?>"><?= htmlspecialchars($commande->getLibelle()) ?></p>

                <div class="col-span-full md:col-span-1 grid gap-2">
                  <div class=" flex justify-evenly md:flex-col gap-2 items-center">
                    <div class="md:order-last">
                      <?php if($commande->getStatusId() === STATUT_TRANSMISE): ?>
                        <a class="btn-form-dark px-6 py-2 rounded-1xl" href="/modifierCommande?id=<?= $commande->getCommandeId() ?>">Modifier</a>
                        <?php endif; ?>
                    </div>
                    <div class="md:order-first">
                      <button class="btnDetailCommande btn-detail py-2 rounded-1xl" type="button">Details</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        <?php endforeach; ?>
         </div>
  </div>

</div>

</div>


<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>