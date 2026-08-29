<?php

namespace App\Service;

use App\Repository\BoissonRepository;
use App\Repository\CommandeBoissonRepository;
use App\Repository\CommandeMenuRepository;
use App\Repository\CommandePrestaRepository;
use App\Repository\CommandeRepository;
use App\Repository\MenuRepository;
use App\Repository\PrestationRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use DateTimeZone;
use Exception;

use function PHPUnit\Framework\isArray;

class CommandeService
{
  private CommandeRepository $commandeRepository;
  private CommandePrestaRepository $commandePrestaRepository;
  private CommandeMenuRepository $commandeMenuRepository;
  private CommandeBoissonRepository $commandeBoissonRepository;
  private MenuRepository $menuRepository;
  private PrestationRepository $prestationRepository;
  private BoissonRepository $boissonRepository;
  private StatusRepository $statusRepository;
  private UserRepository $userRepository;
  private BoissonService $boissonService;
  private CalculPrixService $calculPrixService;
  private MailService $mailService;
  private MenuService $menuService;

  public function __construct(CommandeRepository $commandeRepository, CommandePrestaRepository $commandePrestaRepository, 
  CommandeMenuRepository $commandeMenuRepository, CommandeBoissonRepository $commandeBoissonRepository, 
  StatusRepository $statusRepository, MenuRepository $menuRepository, 
  PrestationRepository $prestationRepository, BoissonRepository $boissonRepository,
  UserRepository $userRepository, BoissonService $boissonService, CalculPrixService $calculPrixService,
  MailService $mailService, MenuService $menuService)
  {
    $this->commandeRepository = $commandeRepository;
    $this->commandePrestaRepository = $commandePrestaRepository;
    $this->commandeMenuRepository = $commandeMenuRepository;
    $this->commandeBoissonRepository = $commandeBoissonRepository;
    $this->statusRepository = $statusRepository;
    $this->menuRepository = $menuRepository;
    $this->prestationRepository = $prestationRepository;
    $this->boissonRepository = $boissonRepository;
    $this->calculPrixService = $calculPrixService;
    $this->userRepository = $userRepository;
    $this->boissonService = $boissonService;
    $this->mailService = $mailService;
    $this->menuService = $menuService;
  }

  // CREATION DE LA COMMANDE
  public function creerCommande(array $data, array $dataMenu, array $dataPresta, array $dataBoisson, float $prixTotalPresta, array $dataUser)
  {
    $user = $this->userRepository->trouveUtilisateurById($data['user_id']);
    if($dataUser['nom'] !== $user->getNom()||
        $dataUser['prenom'] !== $user->getPrenom() ||
        $dataUser['email'] !== $user->getEmail()){
      throw new Exception('Les informations ne correspondent pas');
    }

    $menusCommande = $this->verifMenu($dataMenu);
    $prixPresta = $this->verifPresta($dataPresta, $prixTotalPresta, $menusCommande);
    $boissonsCommande = $this->verifBoisson($dataBoisson);
    $this->verifLivraison($data);
    $this->prixTotal($prixPresta, $menusCommande, $boissonsCommande, $data);

    $data['date_commande'] = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'))->format('Y-m-d H:i:s');
    $data['status_id'] = STATUT_TRANSMISE;
    $data['nb_commande'] = $this->genererNbCommande();
    $data['nb_personne'] = $this->calculPrixService->calculerNbPersonneCommande($menusCommande);

    $commande = $this->commandeRepository->creerCommande($data);

    //Envoie du mail de confirmation
    $html = $this->mailService->recupererHtml('commande/confirmationMail', ['prenom' => $dataUser['prenom'], 'nbCommande' => $data['nb_commande']]);
    $objet = 'Confirmation de votre commande';
    $this->mailService->envoyer($dataUser['email'], $objet, $html);

    return $commande;
  }

  // AJOUT DES ELEMENTS DE LA COMMANDE
  public function ajouterPrestaCommande(int $commandeId, float $prixTotalPresta, array $dataPresta)
  {
    $presta = [];
    foreach($dataPresta as $data){
      $datePresta = new DateTimeImmutable($data['date_presta'], new DateTimeZone('Europe/Paris'));
  
      $data['date_presta'] = $datePresta->format('Y-m-d H:i:s');
      $data['date_retour_prevu'] = $datePresta->modify('+10 days')->format('Y-m-d H:i:s');
      $data['commande_id'] = $commandeId;
      $data['date_retour'] = null;
      $data['taux_retard'] = 600;
      $data['prix_total_presta'] = $prixTotalPresta;

      //var_dump($data);
      $presta[] = $this->commandePrestaRepository->ajouterPrestaCommande($data);
      
    }

    return $presta;
  }

  public function ajouterMenuCommande(int $commandeId, array $dataMenu)
  {
    $menu = [];
    foreach($dataMenu as $data){
      $data['commande_id'] = $commandeId;
      $menu[] = $this->commandeMenuRepository->ajouterMenuCommande($data);

      // Je decremente le stock des plats du menu commandé
      $this->menuService->stockePlatEtMenu($data['menu_id'], $data['nb_personne_menu'], false);
    }

    return $menu;
  }

  public function ajouterBoissonCommande(int $commandeId, array $dataBoisson)
  {
    $boisson = [];
    foreach($dataBoisson as $data){
      $boissonParId = $this->boissonRepository->trouverBoissonParId($data['boisson_id']);
      $data['commande_id'] = $commandeId;
      $data['prix_unitaire'] = $boissonParId->getPrixBoisson();
      $boisson[] = $this->commandeBoissonRepository->ajouterBoissonCommande($data);

      $this->boissonService->decrementerStockBoisson($data['boisson_id'], $data['quantite']);

    }

    return $boisson;
  }

  // AFFICHAGE COMMANDE
  public function afficherCommandes()
  {
    $commande = $this->commandeRepository->trouverCommande();
    
    return $this->ajouterElementCommande($commande);
  }

  public function afficherCommandesUser(int $userId)
  {
    $commande = $this->commandeRepository->trouverCommandeUser($userId);
    
    return $this->ajouterElementCommande($commande);
  }

  public function afficherDetailsCommande(int $idCommande)
  {
    $commandeId = $this->commandeRepository->trouverCommandeParId($idCommande);
    $commande = $this->ajouterElementCommande([$commandeId]);

    return $commande[0];
  }

  public function afficherCommandesFiltre(array $commandesFiltre)
  {
    $commandes = $this->commandeRepository->trouverCommandeFiltre($commandesFiltre);
    $this->ajouterElementCommande($commandes);

    foreach ($commandes as $commande) {
        // Récupérer le user lié à la commande
        $user = $this->userRepository->trouveUtilisateurById($commande->getUserId());
        $commande->setUser($user);
    }

    return $commandes;
  }


  public function afficherStatusCommandes()
  {
    return $this->statusRepository->trouverStatus();
  }

    // MODIFICATION DE LA COMMANDE
  public function modifierCommande(int $commandeId, int $roleId, array $dataCommande, array $dataMenu, array $dataPresta, array $dataBoisson, float $prixTotalPresta, ?string $motif = null)
  {
    $this->modificationNonPermise($commandeId);

    // Modification de données de la commande
    $commande = $this->commandeRepository->trouverCommandeParId($commandeId);

    $anciennesDonneesCommande = $commande->deshydrate();
    $dataCommande = array_filter($dataCommande, fn($value) => $value !== null);
    $nouvellesDonneesCommande = array_merge($anciennesDonneesCommande, $dataCommande);

    // Modification des données des menus
    $menus = $this->commandeMenuRepository->trouverMenuDeLaCommande($commandeId);

    $anciennesDonneesMenu = [];
    // Je boucle sur les menus de la commande et les stock dans $anciennesDonneesMenu[]
    foreach($menus as $menu){
      $anciennesDonneesMenu[$menu->getMenuId()] = $menu->deshydrate();
    }
    
    $nouvellesDonneesMenu = [];
    // Je boucle sur les menus recu du front et les stock dans $nouvellesDonneesMenu[]
    foreach($dataMenu as $menu){
      $nouvellesDonneesMenu[$menu['menu_id']] = $menu;
      $this->modificationImpossible($menu['menu_id'], $anciennesDonneesMenu);

    }

    $donneesFinalMenu = [];
    foreach($nouvellesDonneesMenu as $menuId => $data){
      $data = array_merge($anciennesDonneesMenu[$menuId], $data);
      $data['commande_id'] = $commandeId;
      unset($data['menu']);
      $donneesFinalMenu[$menuId] = $data;
    }

    $diffStockMenu = [];
    foreach($nouvellesDonneesMenu as $menuId => $nouveauMenu){
      // pour chaque menu recu du front je verifie qu'il existe dnas les anciennes donnees
      $this->elementInvalide($anciennesDonneesMenu, $menuId, 'Menu');

      $ancienMenu = $anciennesDonneesMenu[$menuId];
      // Je calcul la difference de nb_personne de l'ancienne donnee et cellui de la nouvelle
      // et stock cette difference dans diffStockMenu[]
      $differenceMenu = $nouveauMenu['nb_personne_menu'] - $ancienMenu['nb_personne_menu'];
      $diffStockMenu[$menuId] = $differenceMenu;
    }


    // Modification des données des boissons
    $boissons = $this->commandeBoissonRepository->trouverBoissonDeLaCommande($commandeId);

    $anciennesDonneesBoisson = [];

    foreach($boissons as $boisson){
      $anciennesDonneesBoisson[$boisson->getBoissonId()] = $boisson->deshydrate();
    }

    $nouvellesDonneesBoisson = [];
    foreach($dataBoisson as $boisson){
      $nouvellesDonneesBoisson[$boisson['boisson_id']] = $boisson;
      $this->modificationImpossible($boisson['boisson_id'], $anciennesDonneesBoisson);
    }

    $donneesFinalBoisson = [];
    foreach($nouvellesDonneesBoisson as $boissonId => $data){
      $data = array_merge($anciennesDonneesBoisson[$boissonId], $data);
      $data['commande_id'] = $commandeId;
      unset($data['boisson']);
      unset($data['prix_unitaire']);
      $donneesFinalBoisson[$boissonId] = $data;
    }


    $diffStockBoisson = [];
    foreach($nouvellesDonneesBoisson as $boissonId => $nouvelleBoisson){

      $this->elementInvalide($anciennesDonneesBoisson, $boissonId, 'Boisson');

      $ancienneBoisson = $anciennesDonneesBoisson[$boissonId];
      $differenceBoisson = $nouvelleBoisson['quantite'] - $ancienneBoisson['quantite'];
      $diffStockBoisson[$boissonId] = $differenceBoisson;
    }

    // Modification des données des prestas
    $prestas = $this->commandePrestaRepository->trouverPrestaDeLaCommande($commandeId);

    $anciennesDonneesPresta = [];

    foreach($prestas as $presta){
      $anciennesDonneesPresta[$presta->getPrestationId()] = $presta->deshydrate();
    }
    
    $nouvellesDonneesPresta = [];
    foreach($dataPresta as $presta){
      $presta['prix_total_presta'] = $prixTotalPresta;
      $nouvellesDonneesPresta[$presta['prestation_id']] = $presta;
    }

    $donneesFinalePresta = [];
    foreach($nouvellesDonneesPresta as $prestaId => $data){
      $data = array_merge($anciennesDonneesPresta[$prestaId], $data);
      $data['commande_id'] = $commandeId;
      unset($data['prestation']);
      unset($data['taux_retard']);
      $donneesFinalePresta[$prestaId] = $data;
    }

    foreach($nouvellesDonneesPresta as $prestaId => $nouvellePresta){
      $this->elementInvalide($anciennesDonneesPresta, $prestaId, 'Prestation');
    }

    // Je verifie que tout est ok pour la modification de la commande
    $menusCommande = $this->verifMenu($nouvellesDonneesMenu, $diffStockMenu);
    $prixPresta = $this->verifPresta($nouvellesDonneesPresta, $prixTotalPresta, $menusCommande);
    $boissonsCommande = $this->verifBoisson($nouvellesDonneesBoisson, $diffStockBoisson);
    $this->verifLivraison($nouvellesDonneesCommande);
    $this->prixTotal($prixPresta, $menusCommande, $boissonsCommande, $nouvellesDonneesCommande);


    // Je m'assure que si un employe modifie une commande, un motif est envoye
    if(($roleId === ROLE_EMPLOYE || $roleId === ROLE_ADMIN) && empty($motif)){
      throw new Exception('Un motif est obligatoire pour modifier une commande');
    }

    $user = $this->userRepository->trouveUtilisateurById($nouvellesDonneesCommande['user_id']);
    if($roleId === ROLE_UTILISATEUR){
      // Mail modification
      $html = $this->mailService->recupererHtml('commande/modificationClientMail', ['prenom' => $user->getPrenom(), 'nbCommande' => $nouvellesDonneesCommande['nb_commande']]);
      $objet = 'Modification de votre commande';
      $this->mailService->envoyer($user->getEmail(), $objet, $html);

    }elseif($roleId === ROLE_EMPLOYE || $roleId === ROLE_ADMIN){
      // Mail modification + motif
      $html = $this->mailService->recupererHtml('commande/modificationEmployeMail', ['prenom' => $user->getPrenom(), 'nbCommande' => $nouvellesDonneesCommande['nb_commande'], 'motif' => $motif]);
      $objet = 'Modification de votre commande';
      $this->mailService->envoyer($user->getEmail(), $objet, $html);
    }

    unset($nouvellesDonneesCommande['role_id']);
    //unset($nouvellesDonneesCommande['commande_id']);
    unset($nouvellesDonneesCommande['nb_commande']);
    unset($nouvellesDonneesCommande['date_commande']);
    unset($nouvellesDonneesCommande['user_id']);
    unset($nouvellesDonneesCommande['status_id']);
    unset($nouvellesDonneesCommande['libelle']);
    unset($nouvellesDonneesCommande['commande_prestations']);
    unset($nouvellesDonneesCommande['commande_menus']);
    unset($nouvellesDonneesCommande['commande_boissons']);

    try{
      $this->commandeRepository->beginTransaction();
      $this->commandeRepository->modifierCommande($nouvellesDonneesCommande);

      // Je boucle sur chaque menu pour et insert les modifs en base
      foreach($donneesFinalMenu as $menuId => $data){
        $this->commandeMenuRepository->modifierMenuDeLaCommande($data);
      }


      // Recalcul des stocks menus
      foreach($diffStockMenu as $menu => $difference){
        if($difference > 0){
          $this->menuService->stockePlatEtMenu($menu, $difference, false);
        }elseif($difference < 0){
          $this->menuService->stockePlatEtMenu($menu, abs($difference), true);
        }
      }

      foreach($donneesFinalBoisson as $boissonId => $data){
        $this->commandeBoissonRepository->modifierBoissonDeLaCommande($data);
      }

      // Recalcul des stocks des boissons
      foreach($diffStockBoisson as $boisson => $difference){
        if($difference > 0){
          $this->boissonService->decrementerStockBoisson($boisson, $difference);
        }elseif($difference < 0){
          // J'utilise abs pour envoyer un nombre positif a ma methode
          $this->boissonService->incrementerStockBoisson($boisson, abs($difference));
        }
      }

      foreach($donneesFinalePresta as $prestaId => $data){
        $this->commandePrestaRepository->modifierPrestaDeLaCommande($data);
      }

      $this->commandeRepository->commit();
    }catch(Exception $e){
      $this->commandeRepository->rollBack();
      throw new Exception('Erreur lors de la modification de la commande : '. $e->getMessage());
    }
  }

  public function modifierStatusCommande(int $commandeId, int $status)
  {
    $this->commandeRepository->modifierStatusCommande($commandeId, $status);
    $this->actionModifStatus($commandeId);
  }

  private function actionModifStatus(int $commandeId)
  {
    $commande = $this->commandeRepository->trouverCommandeParId($commandeId);
    $user = $this->userRepository->trouveUtilisateurById($commande->getUserId());
    $status = $commande->getStatusId();
    $prestaCommande = $this->commandePrestaRepository->trouverPrestaDeLaCommande($commandeId);

    switch($status){
      case STATUT_ACCEPTEE :
        // Mail commande acceptée
        $html = $this->mailService->recupererHtml('commande/statuts/accepteMail', [
          'prenom' => $user->getPrenom(), 
          'nbCommande' => $commande->getNbCommande(), 
          'dateLivraison' => $commande->getDateLivraison()->format('d/m/Y'), 
          'adresseLivraison' => $commande->getLieuLivraison()
        ]);
        $objet = 'Commande acceptée';
        $this->mailService->envoyer($user->getEmail(), $objet, $html);
      break;

      case STATUT_EN_PREPARATION :
        // Mail commande en preparation
        $html = $this->mailService->recupererHtml('commande/statuts/preparationEnCoursMail', [
          'prenom' => $user->getPrenom(), 
          'nbCommande' => $commande->getNbCommande(), 
          'dateLivraison' => $commande->getDateLivraison()->format('d/m/Y'), 
          'adresseLivraison' => $commande->getLieuLivraison()
        ]);
        $objet = 'Commande en cours de préparation';
        $this->mailService->envoyer($user->getEmail(), $objet, $html);
      break;

      case STATUT_EN_COUR_LIV :
        // Mail commande en cours de livraison
        $html = $this->mailService->recupererHtml('commande/statuts/livraisonEncoursMail', [
          'prenom' => $user->getPrenom(), 
          'nbCommande' => $commande->getNbCommande(), 
          'dateLivraison' => $commande->getDateLivraison()->format('d/m/Y'), 
          'adresseLivraison' => $commande->getLieuLivraison()
        ]);
        $objet = 'Commande en cours de livraison';
        $this->mailService->envoyer($user->getEmail(), $objet, $html);
      break;

      case STATUT_LIVREE :
        $necessiteRetour = false;

        foreach($prestaCommande as $presta){
          if($presta->getPrestation()->isNecessiteRetour()){
            $necessiteRetour = true;
          }
        }
        // Mail commande livrée
        $html = $this->mailService->recupererHtml('commande/statuts/livreMail', [
          'prenom' => $user->getPrenom(), 
          'nbCommande' => $commande->getNbCommande(), 
          'adresseLivraison' => $commande->getLieuLivraison()
        ]);
        $objet = 'Commande livrée';
        $this->mailService->envoyer($user->getEmail(), $objet, $html);
        sleep(10);
        if($necessiteRetour === false){
          $this->modifierStatusCommande($commandeId, STATUT_TERMINEE);
        }else{
          $this->modifierStatusCommande($commandeId, STATUT_ATTEND_RETOUR);
        }
      break;

      case STATUT_ATTEND_RETOUR :
        $dateRetourPrevu = null;
        $tauxRetard = null;

        foreach($prestaCommande as $presta){
          $dateRetourPrevu = $presta->getDateRetourPrevu()->format('d/m/Y');
          $tauxRetard = $presta->getTauxRetard();
        }
        // Mail commande en attente de retour du materiel
        $html = $this->mailService->recupererHtml('commande/statuts/attenteMaterielMail', [
          'prenom' => $user->getPrenom(), 
          'nbCommande' => $commande->getNbCommande(), 
          'dateLivraison' => $commande->getDateLivraison()->format('d/m/Y'),
          'adresseVG' => ADRESSE_VG,
          'dateRetourMax' => $dateRetourPrevu,
          'tauxRetard' => $tauxRetard
        ]);
        $objet = 'Commande en attente de retour du materiel';
        $this->mailService->envoyer($user->getEmail(), $objet, $html);
      break;

      case STATUT_TERMINEE :
        $dateDujour = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));
        $dateRetour = $dateDujour->format('Y-m-d H:i:s');
        
        foreach($prestaCommande as $presta){
          $this->commandePrestaRepository->modifierDateRetourPresta([
            'prestation_id' => $presta->getPrestationId(), 
            'commande_id' => $commandeId, 
            'date_retour' => $dateRetour
          ]);
          $presta->setDateRetour($dateDujour);
        }
        // Mail commande terminée
        $html = $this->mailService->recupererHtml('commande/statuts/termineMail', [
          'prenom' => $user->getPrenom(), 
          'nbCommande' => $commande->getNbCommande(), 
          'lienAvis' => BASE_URL . '/avis?nbCommande='.$commande->getNbCommande()
        ]);
        $objet = 'Commande terminée';
        $this->mailService->envoyer($user->getEmail(), $objet, $html);
      break;
    }
  }

  // ANNULATION DE LA COMMANDE
  public function annulerCommande(int $commandeId, int $roleId, int $userId, ?string $motif = null)
  {
    $this->modificationNonPermise($commandeId);
    $status = STATUT_ANNULEE;
    $this->modifierStatusCommande($commandeId, $status);

    $this->restaurerStockBoisson($commandeId);
    $this->restaurerStockMenu($commandeId);

    $user = $this->userRepository->trouveUtilisateurById($userId);
    $commande = $this->commandeRepository->trouverCommandeParId($commandeId);


    if($roleId === ROLE_UTILISATEUR){
      // Mail annulation
      $html = $this->mailService->recupererHtml('commande/annulationClientMail', ['prenom' => $user->getPrenom(), 'nbCommande' => $commande->getNbCommande()]);
      $objet = 'Annulation de votre commande';
      $this->mailService->envoyer($user->getEmail(), $objet, $html);
    }elseif(($roleId === ROLE_EMPLOYE || $roleId === ROLE_ADMIN) && !empty($motif)){
      // Mail annulation + motif
      $html = $this->mailService->recupererHtml('commande/annulationEmployeMail', ['prenom' => $user->getPrenom(), 'nbCommande' => $commande->getNbCommande(), 'motif' => $motif]);
      $objet = 'Annulation de votre commande';
      $this->mailService->envoyer($user->getEmail(), $objet, $html);
    }
  }

  private function genererNbCommande(){
    $nbGenere = rand(100, 9999);
    while($this->commandeRepository->trouverCommandeParNb($nbGenere)){
      $nbGenere = rand(100, 9999);
    }
    return $nbGenere;
  }

  private function ajouterElementCommande(array $commandes)
  {
    foreach ($commandes as $commande) {
        $commande->setCommandeMenus($this->commandeMenuRepository->trouverMenuDeLaCommande($commande->getCommandeId()));
        $commande->setCommandePrestations($this->commandePrestaRepository->trouverPrestaDeLaCommande($commande->getCommandeId()));
        $commande->setCommandeBoissons($this->commandeBoissonRepository->trouverBoissonDeLaCommande($commande->getCommandeId()));
    }
    return $commandes;
  }

  private function modificationImpossible(int $nvlDonnees, array $ancDonnees)
  {
    if(!array_key_exists($nvlDonnees, $ancDonnees)){
        throw new Exception("Tout est modifiable excepté le choix des menus et des boissons");
      }
  }
  private function elementInvalide(array $anciennesDonnees, int $id, string $element)
  {
    if(!isset($anciennesDonnees[$id])){
        throw new Exception($element.' invalide pour cette commande');
      }
  }

  private function modificationNonPermise(int $commandeId)
  {
    $commande = $this->commandeRepository->trouverCommandeParId($commandeId);
    if($commande->getStatusId() !== STATUT_TRANSMISE){
      throw new Exception('La commande ne peux plus etre modifiée');
    }
  }


  private function restaurerStockBoisson(int $commandeId)
  {
    $boissonCommande = $this->commandeBoissonRepository->trouverBoissonDeLaCommande($commandeId);

    foreach($boissonCommande as $boisson){
      $this->boissonService->incrementerStockBoisson($boisson->getBoissonId(), $boisson->getQuantite());
    }
  }

  private function restaurerStockMenu(int $commandeId)
  {
    $menuCommande = $this->commandeMenuRepository->trouverMenuDeLaCommande($commandeId);

    foreach($menuCommande as $menu){
      $this->menuService->stockePlatEtMenu($menu->getMenuId(), $menu->getNbPersonneMenu(), true);
    }
  }

  private function verifMenu(array $dataMenu, array $diffStock = []): array
  {
    // Je boucle sur les menus
    // Je verifie que chacun des menus selectionée a le nombre de personnes minimum requis
    $menusCommande = [];
    foreach($dataMenu as $menu){
      $menuParId = $this->menuRepository->trouverMenuParId($menu['menu_id']);
      $nbPersMin = $menuParId->getNombrePersonneMin();

      if($menu['nb_personne_menu'] < $nbPersMin){
        throw new Exception('Nombre de personnes minimum '. $nbPersMin .' pour le menu '. $menuParId->getTitre());
      }

      // Tablau des menus de ma commande
      $menusCommande[] =
        // Infos d'UN menu
        [
          'prix_personne' => $menuParId->getPrixPersonne(), 
          'nombre_personne_min' => $nbPersMin, 
          'nb_personne_menu' => $menu['nb_personne_menu']
        ];

      // Si il y a un menu_id dans $diffStock je le stock dans $nbPersMenu sinon je stocke $menu['nb_personne_menu']
      $nbPersMenu = $diffStock[$menu['menu_id']] ?? $menu['nb_personne_menu'];

      $this->menuService->verifStockDispo($menu['menu_id'], $nbPersMenu);
    }
    return $menusCommande;
  }

  private function verifPresta(array $dataPresta, float $prixTotalPresta, array $menusCommande)
  {
    //Je boucle sur mes prestation pour recuperer le prix de chacunes d'entres elles
    $prixPresta = [];
    foreach($dataPresta as $presta){
      $prestationParId = $this->prestationRepository->trouverPrestationparId($presta['prestation_id']);
      $prixPresta[] = $prestationParId->getPrixPresta();
    }

    if($prixTotalPresta != $this->calculPrixService->calculerTotalpresta($menusCommande, $prixPresta)){
      throw new Exception('Erreur prix total presta '. $this->calculPrixService->calculerTotalpresta($menusCommande, $prixPresta));
    }
    return $prixPresta;
  }

  private function verifBoisson(array $dataBoisson, array $diffStock = [])
  {
    $boissonsCommande = [];
    foreach($dataBoisson as $boisson){
      $boissonParId = $this->boissonRepository->trouverBoissonParId($boisson['boisson_id']);

      $boissonsCommande[] = [
        'prix_boisson' => $boissonParId->getPrixBoisson(),
        'quantite' => $boisson['quantite']
      ];

      $quantite = $diffStock[$boisson['boisson_id']] ?? $boisson['quantite'];

      $this->boissonService->verifStockBoisson($boisson['boisson_id'], $quantite);
    }
    return $boissonsCommande;
  }

  private function verifLivraison(array $data)
  {
    $dateLivraison = new DateTimeImmutable($data['date_livraison']);
    $delaisMinimum = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'))->modify("+5 days");

    if($dateLivraison < $delaisMinimum){
      throw new Exception('Le delais minimum entre la commande et la livraison est de 5 jours');
    }

    if(abs($data['prix_livraison'] - $this->calculPrixService->calculerPrixDeLivraison(ADRESSE_VG , $data['lieu_livraison'])) > 0.01){
      throw new Exception('Erreur de prix livraison');
    }
  }

  private function prixTotal(array $prixPresta, array $menusCommande, array $boissonsCommande, array $data)
  {
    if(abs($data['prix_total'] - $this->calculPrixService->calculTotalCommande($prixPresta, $menusCommande, $boissonsCommande, ADRESSE_VG, $data['lieu_livraison'])) > 0.01){
      throw new Exception('Erreur de prix total');
    }
  }

}
