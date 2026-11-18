<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php /** @var object $commande */ ?>

<div class="contenue-page">
  <div class="flex flex-col gap-8">
    <div class="bg-primary p-2 rounded-lg">
      <h2 class="uppercase text-xl md:text-2xl text-center font-medium">details de la commande #<?= htmlspecialchars($commande->getNbCommande()) ?></h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 md:items-start gap-8">
      <div class="md:col-span-1 grid gap-4">
        <div class="grid grid-cols-2 md:grid-cols-1 gap-6 px-4">

          <div class="order-first md:order-0">
            <p><?= htmlspecialchars($commande->getUser()->getNom(). " ". $commande->getUser()->getPrenom()) ?></p>
            <p><?= htmlspecialchars($commande->getUser()->getEmail()) ?></p>
            <?php if($commande->getUser()->getTelephone()): ?>
              <p><?= htmlspecialchars($commande->getUser()->getTelephone()) ?></p>
            <?php endif; ?>
          </div>

          <div class="order-3 bg-primary/50 rounded-2xl p-2 md:order-0">
            <p>Commande</p>
            <p> <?= htmlspecialchars($commande->getDateCommande()->format('d/m/Y')) ?></p>
          </div>

          <div class="order-last bg-primary/50 rounded-2xl p-2 md:order-0">
            <p>Livraison</p>
            <p> <?= htmlspecialchars($commande->getDateLivraison()->format('d/m/Y')) ?></p>
          </div>

          <div class="hidden md:block">
            <p>Adresse</p>
            <p><?= htmlspecialchars($commande->getLieuLivraison()) ?></p>
          </div>
          <div class="order-2 md:order-0">
          <!-- <div class="flex items-center"> -->
            <p><?= htmlspecialchars($commande->getLibelle()) ?></p>
          </div>
        </div>
      </div>
    

      <div class="md:col-span-2 py-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 px-4">

          <h4 class="text-xl uppercase col-span-full">Menus</h4>
          <?php foreach($commande->getCommandeMenus() as $menu): ?>
            <div class="col-span-1">
              <h5 class="text-lg"><?= htmlspecialchars($menu->getMenu()->getTitre()) ?></h5>
              <p><?= htmlspecialchars($menu->getNbPersonneMenu()) ?> personnes</p>
            </div>
            <div class="col-span-1">
              <p class=""><?= htmlspecialchars($menu->getPrixTotalMenu()) ?> €</p>
            </div>
          <?php endforeach; ?>

          
          <?php if($commande->getCommandeBoissons()): ?>

            <div class="col-span-full h-0.5 bg-primary w-4/5 justify-self-center"></div>

          <h4 class="text-xl uppercase col-span-full">Boisson</h4>
          <?php foreach($commande->getCommandeBoissons() as $boisson): ?>
            <div>
              <h5 class="text-lg"><?= htmlspecialchars($boisson->getBoisson()->getNomBoisson()) ?></h5>
              <p><?= htmlspecialchars($boisson->getQuantite()) ?> boissons</p>
            </div>
            <div>
              <p class=""><?= htmlspecialchars($boisson->getPrixTotalBoisson()) ?> €</p>
            </div>
          <?php endforeach; ?>
          <?php endif; ?>

          
          <?php if($commande->getCommandePrestations()): ?>
            
            <div class="col-span-full h-0.5 bg-primary w-4/5 justify-self-center"></div>

            <h4 class="text-xl uppercase col-span-full">Prestations</h4>
            <?php foreach($commande->getCommandePrestations() as $presta): ?>
              <div>
                <h5 class="text-lg"><?= htmlspecialchars($presta->getprestation()->getNomPresta()) ?></h5>
                <p><?= htmlspecialchars($commande->getNbPersonne()) ?> personnes</p>
              </div>
              <div>
                <p><?= htmlspecialchars($presta->getMontantPrestation()) ?> €</p>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>

          <div class=" col-span-full h-0.75 bg-primary w-4/5 justify-self-center"></div>

            <h3 class="text-2xl">Total </h3>
            <h3 class="text-2xl"> <?= htmlspecialchars($commande->getPrixTotal()) ?> €</h3>
         
          
        </div>
      </div>
    </div>

  </div>
</div>


<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>

