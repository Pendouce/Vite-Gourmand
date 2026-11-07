
<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>
<?php require_once(APP_ROOT.'/templates/layouts/pageBanner.php');?>

<?php /** @var array $prestations */?>
<?php /** @var array $prestationsParType */?>
<?php /** @var object $prestation */?>
<?php /** @var object $user */?>
<?php /** @var object $menu */?>
<?php /** @var object $boisson */?>
<?php /** @var object $role */?>
<?php /** @var string $csrfToken */ ?>
<?php /** @var string $modeCommande */?>
<?php $modeCommande = true ?>


<div class="contenue-page">

  <div id="modalMenu" class="modal-container-commande hidden">
    <div class="modal-commande">
      <button type="button" class="btnFermerModalCommande absolute right-6 top-6 hover:text-primary">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 lg:size-8">
          <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
        </svg>
      </button>
      <?php include(APP_ROOT.'/templates/partials/menus.php');?>
    </div>
  </div>

  <div id="modalBoisson" class="modal-container-commande hidden">
    <div class="modal-commande">
      <button type="button" class="btnFermerModalCommande absolute right-6 top-6 hover:text-primary">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 lg:size-8">
          <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
        </svg>
      </button>
      <?php include(APP_ROOT.'/templates/partials/boissons.php');?>
    </div>
  </div>

  <div id="modalPresta" class="modal-container-commande hidden">
    <div class="modal-commande">
      <button type="button" class="btnFermerModalCommande absolute right-6 top-6 hover:text-primary">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 lg:size-8">
          <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
        </svg>
      </button>
      <?php include(APP_ROOT.'/templates/partials/presta.php');?>
    </div>
  </div>

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

          <section id="sectionMenus" class="flex flex-col border border-primary py-4 px-6 bg-fond-carte/60 gap-6">
            <div class="flex flex-col items-center gap-2">
              <h3 class="uppercase text-xl text-center md:text-2xl">menus selectionés</h3>
              <div class="h-px bg-primary w-2/3 self-center"></div>
            </div>
            <div class="flex justify-evenly">
              <div class="divMenus flex flex-wrap gap-8">
                <div class="divMenu px-2 flex flex-col gap-6">
                  <!-- Titre menu -->
                  <input type="hidden" name="menu[]" id="<?= htmlspecialchars($menu->getMenuId()) ?>">
                  <div class="relative">
                    <h4 class="titreMenu font-medium text-center text-lg md:text-xl"><?= htmlspecialchars($menu->getTitre()) ?></h4>
                    <button class="btnSupprimer absolute -left-4 -top-2 hover:text-primary" type="button">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                  <!-- prix + nb pers -->
                  <div class="menu flex flex-col gap-2">
                      <div class="flex gap-2">
                        <p>Prix unitaire: </p>
                        <div class="flex gap-1">
                          <span class="prixUnitaireMenu"><?= htmlspecialchars($menu->getPrixPersonne()) ?></span>
                          <p>€ /pers</p>
                        </div>
                        
                      </div>

                      <div id="divNbPers" class="flex flex-col gap-2">
                        <label for="">Nombre des personnes:</label>
                        <div class="flex gap-2">
                          <button class="btnMoins" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 lg:size-8 text-primary">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                          </button>
                          <input name="nb_personne_menu" class="nbPersonneMenu text-center border border-primary/50 py-1 px-2 rounded-lg w-15 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                           min="<?= $menu->getNombrePersonneMin() ?>" max="<?= $menu->getStockDispo() ?>" step="1" type="number"
                           value="<?= $menu->getNombrePersonneMin() ?>">
                          <button class="btnPlus" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 lg:size-8 text-primary">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                          </button>
                        </div>
                      </div>
                  

                  </div>
                  <!-- Sous total -->
                  <div class="flex gap-3">
                    <p>Sous total: </p>
                    <div class="flex gap-1">
                      <span class="sousTotalMenu"><?= $menu->getPrixPersonne() ?></span>
                      <p>€</p>
                    </div>
                  </div>
                </div>
              </div>

                <!-- Div ajouter menu -->

              <div class="w-20 p-4 flex justify-center">
                <button id="btnNvMenu" class="border border-primary rounded-2xl p-4 self-center" type="button">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 md:size-12 text-primary">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </button>
              </div>
            </div>

            <div class="flex gap-3 font-medium justify-center">
              <p> Total menus: </p>
              <div class="flex gap-1">
                <span class="total"></span>
                <p>€</p>
              </div>
            </div>
          </section>

          <!-- BOISSON -->

          <section id="sectionBoissons" class="flex flex-col border border-primary py-4 px-6 bg-fond-carte/60 gap-6 relative">
            <div class="flex flex-col items-center gap-2">
              <h3 class="uppercase text-xl text-center md:text-2xl">boisson</h3>
              <div class="h-px bg-primary w-2/3 self-center"></div>
            </div>
            <div class="flex justify-evenly">

              <div class="divBoissons flex justify-evenly">

              
              </div>
                  <!-- Div ajouter Boisson -->
  
              <div class="w-20 p-4 flex justify-center">
                <button id="btnNvlBoisson" class="border border-primary rounded-2xl p-4 self-center" type="button">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 md:size-12 text-primary">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Total boisson -->
            <div class="flex gap-3 font-medium justify-center">
              <p> Total boissons: </p>
              <div class="flex gap-1">
                <span class="total"></span>
                <p>€</p>
              </div>
            </div>

            <!-- Template boisson -->
                <template id="templateBoisson">
  
                  <div class="divBoisson flex flex-col gap-6">
    
                    <!-- Titre boisson -->
                      <input type="hidden" name="boisson[]" id="<?= htmlspecialchars($boisson->getBoissonId()) ?>">
                      <div class="relative">
                        <h4 class="titreBoisson flex justify-center flex-wrap font-medium text-center text-lg md:text-xl"><?= htmlspecialchars($boisson->getNomBoisson()) ?></h4>
                        <button class="btnSupprimer absolute -left-4 -top-2 hover:text-primary" type="button">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>
    
                      <!-- prix + quantite -->
                      <div class="boisson flex flex-col gap-2">
                          <div class="flex gap-2">
                            <p>Prix unitaire: </p>
                            <div class="flex gap-1">
                              <span class="prixUnitaireBoisson"><?= htmlspecialchars($boisson->getPrixBoisson()) ?></span>
                              <p>€</p>
                            </div>
                            
                          </div>
    
                          <div class="divQuantiteflex flex-col gap-2">
                            <label for="">Quantité:</label>
                            <div class="flex gap-2">
                              <button class="btnMoins" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 lg:size-8 text-primary">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                              </button>
                              <input name="quantite" class="quantiteBoisson text-center border border-primary/50 py-1 px-2 rounded-lg w-15 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                               min="1" max="<?= $boisson->getStockBoisson() ?>" step="1" type="number"
                               value="0">
                              <button class="btnPlus" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 lg:size-8 text-primary">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                              </button>
                            </div>
                          </div>
                    
                      </div>
                      <!-- Sous total -->
                      <div class="flex gap-3">
                        <p>Sous total: </p>
                        <div class="flex gap-1">
                          <span class="sousTotalBoisson"><?= $boisson->getPrixBoisson() ?></span>
                          <p>€</p>
                        </div>
                      </div>
                  </div>
                </template>
          </section>
    </section>

    <!-- PRESTATIONS -->
    
    <section>
        <div class="mb-8 relative">
          <div class="flex flex-col gap-3">
            <div class="h-0.75 bg-primary w-2/3 self-center"></div>
            <div class="h-0.5 bg-primary w-1/2 self-center"></div>
          </div>
          <button id="btnModalPresta" class="absolute right-30 top-6 text-primary" type="button">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                  </svg>
                </button>
          <h2 class="font-h2 uppercase text-3xl text-center font-medium text-primary lg:text-4xl py-6 md:py-4">
            prestations
          </h2>
          <div class="flex flex-col gap-3">
            <div class="h-0.5 bg-primary w-1/2 self-center"></div>
            <div class="h-0.75 bg-primary w-2/3 self-center"></div>
          </div>
        </div>
          <!-- Divs presta -->

        <div class="flex flex-col gap-10">
          <?php foreach($prestationsParType as $typeDePrestaLibelle => $prestations): ?>
            <section class="flex flex-col border border-primary py-4 px-6 bg-fond-carte/60 gap-6 relative">
                <h3 class="uppercase text-xl text-center md:text-2xl"><?= $typeDePrestaLibelle ?></h3>
              <div class="h-px bg-primary w-2/3 self-center"></div>
              
              <div class="flex flex-col items-center gap-4">
                <select class="petit-input mx-0" name="prestation[]">
                  <option value="">Non</option>
                  <?php foreach($prestations as $presta): ?>
                    <option value="<?= $presta->getNomPresta() ?>"><?= htmlspecialchars($presta->getNomPresta()) ?></option>
                  <?php endforeach; ?>
                </select>

                <!-- <div class="flex gap-2">
                  <p>A partir de:</p>
                  <div class="flex gap-1">
                    <span class="prixTotalPresta"></span>
                    <p>€ / <span class="seuilPresta"></span> pers</p>
                  </div>

                </div>
                  <div class="flex gap-3">
                    <p>Sous total: </p>
                    <div class="flex gap-1">
                      <span class="prixPresta">10</span>
                      <p>€</p>
                    </div>

                  </div> -->
              </div>
              
            </section>
          <?php endforeach; ?>
        </div>
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
              <p class="text-texte/90 text-sm pb-4">*majoration de 59 centime par kilometre parcourus sera apliquée si hors bordeaux</p>
            </div>
            <div class="flex flex-col col-span-1">
              <label for="">Date et heure</label>
              <input class="petit-input" type="datetime-local" name="date_livraison">
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
            <input id="inputPrixTotal" class="w-12 px-2" value="1">
            <p>€</p>
            
          </div>
          <div class="h-px bg-primary"></div>
        </div>

        <button class="btn-form" type="submit">Commander</button>
      </div>
  </form>

</div>





<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>