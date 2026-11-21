<?php /** @var object $commande */ ?>
<?php /** @var string $statut */ ?>

<div class="py-4 mt-6">
  <div class="flex flex-col gap-8">
    <div class="bg-primary p-2 rounded-lg">
      <h2 class="uppercase text-xl md:text-2xl text-center font-medium">details de la commande #<?= htmlspecialchars($commande->getNbCommande()) ?></h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 md:items-start gap-8">

    <!-- Infos commande -->
      <div class="md:col-span-1 grid gap-4">
        <div class="grid grid-cols-2 md:grid-cols-1 gap-6 px-4">

          <div class="order-first md:order-0">
            <p><?= htmlspecialchars($commande->getUser()->getNom(). " ". $commande->getUser()->getPrenom()) ?></p>
            <p><?= htmlspecialchars($commande->getUser()->getEmail()) ?></p>
            <?php if($commande->getUser()->getTelephone()): ?>
              <p><?= htmlspecialchars($commande->getUser()->getTelephone()) ?></p>
            <?php endif; ?>
          </div>

          <div class="order-3 bg-primary/50 rounded-2xl p-2 md:order-0 md:w-1/2">
            <p>Commande</p>
            <p> <?= htmlspecialchars($commande->getDateCommande()->format('d/m/Y')) ?></p>
          </div>

          <div class="order-last bg-primary/50 rounded-2xl p-2 md:order-0 md:w-1/2">
            <p>Livraison</p>
            <p> <?= htmlspecialchars($commande->getDateLivraison()->format('d/m/Y')) ?></p>
          </div>

          <div class="hidden md:block">
            <p>Adresse</p>
            <p><?= htmlspecialchars($commande->getLieuLivraison()) ?></p>
          </div>
          <div class="order-2 md:order-0">
            <p class="w-40 <?= $statut ?>"><?= htmlspecialchars($commande->getLibelle()) ?></p>
          </div>
        </div>
      </div>
    
      <!-- Recap commande -->

      <div class="md:col-span-2 py-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 px-4 md:gap-6">

          <div class="hidden md:block col-span-full py-4">
            <div class="grid grid-cols-4 py-2 pl-3 md:bg-primary/50 md:rounded-2xl">
              <p class="col-span-2">Article</p>
              <p class="col-span-1">Quantité</p>
              <p class="col-span-1">Prix</p>
            </div>
          </div>

          <h4 class="text-xl uppercase col-span-full">Menus</h4>
          <?php foreach($commande->getCommandeMenus() as $menu): ?>
            <div class="col-span-1 md:contents">
              <h5 class="text-lg md:col-span-2"><?= htmlspecialchars($menu->getMenu()->getTitre()) ?></h5>
              <p class="md:col-span-1"><?= htmlspecialchars($menu->getNbPersonneMenu()) ?> pers.</p>
            </div>
            <div class="col-span-1 justify-self-center md:justify-self-auto">
              <p><?= htmlspecialchars($menu->getPrixTotalMenu()) ?> €</p>
            </div>
          <?php endforeach; ?>

          <div class="col-span-full h-0.5 bg-primary w-4/5 justify-self-center"></div>
          
          <?php if($commande->getCommandeBoissons()): ?>
          <h4 class="text-xl uppercase col-span-full">Boisson</h4>
          <?php foreach($commande->getCommandeBoissons() as $boisson): ?>
            <div class="md:contents">
              <h5 class="text-lg md:col-span-2"><?= htmlspecialchars($boisson->getBoisson()->getNomBoisson()) ?></h5>
              <p class="md:col-span-1"><?= htmlspecialchars($boisson->getQuantite()) ?> boiss.</p>
            </div>
            <div class="justify-self-center md:justify-self-auto">
              <p><?= htmlspecialchars($boisson->getPrixTotalBoisson()) ?> €</p>
            </div>
          <?php endforeach; ?>

          <div class="col-span-full h-0.5 bg-primary w-4/5 justify-self-center"></div>

          <?php endif; ?>

          
          <?php if($commande->getCommandePrestations()): ?>
            

            <h4 class="text-xl uppercase col-span-full">Prestations</h4>
            <?php foreach($commande->getCommandePrestations() as $presta): ?>
              <div class="md:contents">
                <h5 class="text-lg md:col-span-2"><?= htmlspecialchars($presta->getprestation()->getNomPresta()) ?></h5>
                <p class="md:col-span-1"><?= htmlspecialchars($commande->getNbPersonne()) ?> pers.</p>
              </div>
              <div class="justify-self-center md:justify-self-auto">
                <p><?= htmlspecialchars($presta->getMontantPrestation()) ?> €</p>
              </div>
            <?php endforeach; ?>
            <div class="col-span-full h-0.5 bg-primary w-4/5 justify-self-center hidden md:block"></div>
          <?php endif; ?>

          <div class="hidden md:block md:col-span-3">
            <h5 class="text-lg">Livraison</h5>
           </div>
          <div class="hidden md:block md:col-span-1">
              <p><?= htmlspecialchars($commande->getPrixLivraison()) ?> €</p>
           </div>

          <div class=" col-span-full h-0.75 bg-primary w-4/5 justify-self-center"></div>

            <h3 class="text-2xl md:col-span-3">Total </h3>
            <h3 class="text-2xl justify-self-center md:justify-self-auto"> <?= htmlspecialchars($commande->getPrixTotal()) ?> €</h3>
         
          
        </div>
      </div>
    </div>

  </div>
</div>

