
<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var array $prestations */?>
<?php /** @var array $prestationsParType */?>
<?php /** @var object $prestation */?>
<?php /** @var object $user */?>
<?php /** @var object $menu */?>
<?php /** @var array $boisson */?>
<?php /** @var object $role */?>
<?php /** @var string $csrfToken */ ?>


<div class="contenue-page">

  <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">

  <div  class="w-full py-4 mb-2">
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

  <form class="flex flex-col gap-8" action="/creerBoisson" method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $csrfToken ?>">

      <!-- Recap commande -->
      <section>
        <div class="mb-8">
          <div class="flex flex-col gap-3">
            <div class="h-0.75 bg-primary w-2/3 self-center"></div>
            <div class="h-0.5 bg-primary w-1/2 self-center"></div>
          </div>
          <h2 class="font-h2 uppercase text-3xl text-center font-medium text-primary lg:text-4xl py-6 md:py-4">
            recapitulatif de votre <br> commande
          </h2>
          <div class="flex flex-col gap-3">
            <div class="h-0.5 bg-primary w-1/2 self-center"></div>
            <div class="h-0.75 bg-primary w-2/3 self-center"></div>
          </div>
        </div>
        
        <!--  -->

        <!-- Div menus -->
        <div class="flex flex-col gap-10">

          <section class="flex flex-col border border-primary py-4 px-6 bg-fond-carte/60 gap-6">
            <div class="flex flex-col items-center gap-2">
              <h3 class="uppercase text-xl text-center md:text-2xl">menus selectionés</h3>
              <div class="h-px bg-primary w-2/3 self-center"></div>
            </div>
            <div class="flex justify-center">
              <div class="flex flex-col gap-6">
                <!-- Titre menu -->
                <input type="hidden" name="menu[]" id="<?= htmlspecialchars($menu->getMenuId()) ?>">
                <div>
                  <h4 class="text-lg md:text-xl"><?= htmlspecialchars($menu->getTitre()) ?></h4>
                </div>
                <!-- prix + nb pers -->
                <div class="flex flex-col gap-2">
                    <div>
                      <p>
                        Prix unitaire: <?= htmlspecialchars($menu->getPrixPersonne()) ?>€ /pers
                      </p>
                    </div>

                    <div id="divNbPers" class="flex flex-col gap-2">
                      <label for="">Nombre des personnes:</label>
                      <div class="flex gap-2">
                        <button id="btnMoin" type="button">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 lg:size-8 text-primary">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                          </svg>
                        </button>
                        <input id="nbPersonne" name="nb_personne_menu" class="border border-primary/50 py-1 px-2 rounded-lg w-15 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" min="<?= $menu->getNombrePersonneMin() ?>" step="1" type="number">
                        <button id="btnPlus" type="button">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 lg:size-8 text-primary">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                          </svg>
                        </button>
                      </div>
                    </div>

                </div>
                <!-- Sous total -->
                <div>
                  <label for="">Sous total: </label>
                  <input type="text" name="">
                </div>
              </div>
              <div>
                <!-- Div ajouter menu -->
              </div>
              <div class="w-20 p-4 self-center">
                <button id="btnAjouter" class="border border-primary rounded-2xl p-4" type="button">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 md:size-12 text-primary">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </button>
              </div>
            </div>
          </section>

          <!-- Divs presta -->

          <?php foreach($prestationsParType as $typeDePrestaLibelle => $prestations): ?>
            <section class="flex flex-col border border-primary py-4 px-6 bg-fond-carte/60 gap-6">
              <h3 class="uppercase text-xl text-center md:text-2xl"><?= $typeDePrestaLibelle ?></h3>
              <div class="h-px bg-primary w-2/3 self-center"></div>
              
              <div class="flex flex-col items-center gap-4">
                <select class="petit-input mx-0" name="prestation[]">
                  <option value="">Non</option>
                  <?php foreach($prestations as $presta): ?>
                    <option value="<?= $presta->getNomPresta() ?>"><?= htmlspecialchars($presta->getNomPresta()) ?></option>
                  <?php endforeach; ?>
                </select>
                  <p>
                    Prix unitaire: <?= htmlspecialchars($menu->getPrixPersonne()) ?>€ /pers
                  </p>
                  <div class="flex gap-2">
                    <p>Sous total: </p>
                    <span>10</span>
                    <p>€</p>

                  </div>
              </div>
              
            </section>
          <?php endforeach; ?>
          </section>

      <!-- Infos -->
      <section>
        <div class="mb-6">
          <div class="flex flex-col gap-3">
            <div class="h-0.75 bg-primary w-2/3 self-center"></div>
            <div class="h-0.5 bg-primary w-1/2 self-center"></div>
          </div>
          <h2 class="font-h2 uppercase text-3xl text-center font-medium text-primary lg:text-4xl py-6 md:py-4">vos informations</h2>
          <div class="flex flex-col gap-3">
            <div class="h-0.5 bg-primary w-1/2 self-center"></div>
            <div class="h-0.75 bg-primary w-2/3 self-center"></div>
          </div>
        </div>

        <div class="cart-form w-full max-w-4xl">
          <div class="grid grid-cols-2 gap-4 px-3 md:px-0">
              <div class="flex flex-col col-span-1">
                <label for="">Nom</label>
                <input class="petit-input" type="text" name="nom" value="<?= htmlspecialchars($user?->getNom() ?? '') ?>">
              </div>
              <div class="flex flex-col col-span-1">
                <label for="">Prenom</label>
                <input class="petit-input" type="text" name="prenom" value="<?= htmlspecialchars($user?->getPrenom() ?? '') ?>">
              </div>
            <div class="flex flex-col col-span-2">
              <label for="">Email</label>
              <input class="grand-input" type="text" name="email" value="<?= htmlspecialchars($user?->getEmail() ?? '') ?>">
            </div>

            <div class="flex flex-col col-span-1">
              <label for="">Telephone</label>
              <input class="petit-input" type="tel" name="telephone" value="<?= htmlspecialchars($user?->getTelephone() ?? '') ?>">
            </div>

            <div class="flex flex-col col-span-2">
              <label for="">Adresse de livraison</label>
              <input class="grand-input" type="text" name="lieu_livraison">
            </div>
            <div class="flex flex-col col-span-1">
              <label for="">Date et heure</label>
              <input class="petit-input" type="datetime-local" name="date_livraison">
            </div>
            <div class="col-span-full">
              <p>*majoration de 59 centime par kilometre parcourus sera apliquée si hors bordeaux</p>
            </div>
          </div>
        </div>
      </section>
      <!-- Prix / commande -->
      <div class="flex flex-col items-center gap-6">
        <div class="py-8">
          <div class="h-px bg-primary"></div>
          <div class="flex text-center bg-fond-carte/60 gap-2 p-4">
            <label for="">Prix total : </label>
            <input class="w-12 px-2" value="1">
            <p>€</p>
            
          </div>
          <div class="h-px bg-primary"></div>
        </div>

        <button class="btn-form" type="submit">Commander</button>
      </div>
  </form>

</div>





<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>