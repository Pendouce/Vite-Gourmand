<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var object $commandes */ ?>
<?php /** @var string $statut */ ?>
<?php /** @var int $role */ ?>
<?php /** @var string $csrfToken */ ?>

<div class="contenue-page">
  <div class="divBlockRetour w-full py-6">
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
  </div>

  <div id="modalContainer" class="fixed inset-0 z-50 flex justify-center items-center bg-black/50 hidden">
    <div id="modal" class="flex justify-center items-center bg-fond-carte w-xl p-6 rounded-2xl">
      <form class="flex flex-col gap-6" action="/annulerCommande" method="post">
        <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
        <input type="hidden" name="id" id="idCommande">

        <?php if($role === ROLE_ADMIN || $role === ROLE_EMPLOYE): ?>
          <h2 class="font-h2 text-primary text-xl text-center">Souhaitez vous vraiment annuler la commande #<span id="nbCommande"></span> de <span id="nomClient"></span> ?</h2>
          <div class="flex flex-col items-center">
            <label for="motif">Motif :</label>
            <textarea name="motif" class="grand-input w-xs md:w-2/3" rows="6"></textarea>
          </div>
        <?php else: ?>
          <h2 class="font-h2 text-primary text-xl">Souhaitez vous vraiment annuler la commande <span id="nbCommande"></span> ?</h2>
        <?php endif; ?>

        <div class="flex justify-center gap-8">
          <div>
            <button id="btnAnnuler" class="btn bg-texte text-fond-carte hover:bg-texte/60" type="button">Annuler</button>
          </div>
          <div>
            <button id="btnConfirmAnnulation" class="btn" type="submit">Confirmer</button>
          </div>

        </div>
      </form>
    </div>
  </div>

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
      <div>
        <!-- En tete -->
        <div class="col-span-full">
          <div class="grid grid-cols-4 md:grid-cols-[1fr_2fr_1fr_1fr_1.5fr_1.5fr] gap-3 bg-primary/50 rounded-lg p-2">
            <p>Commande</p>
            <p class="hidden md:block md:justify-self-center">Menu</p>
            <p>Livraison</p>
            <p>Prix total</p>
            <p class="justify-self-center">Statut</p>
            <p class="hidden md:block md:justify-self-center">Action</p>
          </div>
        </div>
        <!-- Recap -->
        <?php foreach($commandes as $commande): ?>
            <div class="divCommande col-span-full my-2 p-2" data-id="<?= $commande->getCommandeId() ?>">
              <div class="grid grid-cols-4 md:grid-cols-[1fr_2fr_1fr_1fr_1.5fr_1.5fr] gap-3 bg-fond-carte/60 rounded-2xl px-2 py-6 items-center">
                <p>#<?= $commande->getNbCommande() ?></p>
                <div class="hidden md:block">
                  <?php foreach($commande->getCommandeMenus() as $menu): ?>
                    <p><?= htmlspecialchars($menu->getMenu()->getTitre()) ?></p>
                  <?php endforeach; ?>
                </div>
                <p><?= htmlspecialchars($commande->getDateLivraison()->format('d/m/Y'))?></p>
                <p><?= htmlspecialchars($commande->getPrixTotal()) ?> €</p>
                <p class="justify-self-center text-center text-sm sm:text-base px-2 sm:px-4 min-h-10 <?= $statut[$commande->getStatusId()] ?>"><?= htmlspecialchars($commande->getLibelle()) ?></p>

                <div class="col-span-full md:col-span-1">
                  <div class="flex justify-between items-center px-4 md:px-0">
                    <?php if($commande->getStatusId() === STATUT_TRANSMISE): ?>
                      <div class="contents md:flex md:flex-col md:gap-2">
                        <div>
                          <a class="btn-form-dark px-6 py-2 md:px-5 rounded-1xl" href="/modifierCommande?id=<?= $commande->getCommandeId() ?>">Modifier</a>
                        </div>
                        <div>
                          <button class="btnAnnulerCommande btn-form px-6 py-2 rounded-1xl md:px-5" type="button" 
                          data-nom="<?= $commande->getUser()->getNom() ." ". $commande->getUser()->getPrenom() ?>" data-id="<?= $commande->getCommandeId() ?>"
                          data-nb-commande="<?= $commande->getNbCommande() ?>">Annuler</button>
                        </div>

                      </div>
                    <?php endif; ?>
                    <div class="md:order-first only:mx-auto">
                      <button class="btnDetailCommande btn-detail py-2 rounded-1xl md:px-4" type="button">Details</button>
                    </div>
                  </div>
                </div>

              </div>
            </div>
        <?php endforeach; ?>
  </div>

</div>


<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>