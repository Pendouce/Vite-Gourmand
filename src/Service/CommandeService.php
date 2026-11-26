<?php

namespace App\Service;

use App\Exceptions\AccesRefuseException;
use App\Repository\BoissonRepository;
use App\Repository\CommandeBoissonRepository;
use App\Repository\CommandeMenuRepository;
use App\Repository\CommandePrestaRepository;
use App\Repository\CommandeRepository;
use App\Repository\MenuRepository;
use App\Repository\PrestationRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use App\Service\VerifService;
use DateTimeImmutable;
use DateTimeZone;
use Exception;

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

  private function normaliserMot(string $mot){
    $mot = trim($mot);
    $mot = mb_strtolower($mot);
    $accents = [
      'à' => 'a', 'â' => 'a', 'ä' => 'a',
      'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
      'î' => 'i', 'ï' => 'i',
      'ô' => 'o', 'ö' => 'o',
      'ù' => 'u', 'û' => 'u', 'ü' => 'u',
      'ç' => 'c',
  ];

  return strtr($mot, $accents);
  }

  // CREATION DE LA COMMANDE
  public function creerCommande(array $data, array $dataMenu, array $dataPresta, array $dataBoisson, float $prixTotalPresta, array $dataUser, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE, ROLE_UTILISATEUR])) throw new AccesRefuseException();
    $user = $this->userRepository->trouveUtilisateurById($data['user_id']);
    $client = $this->userRepository->trouveUtilisateurByEmail($dataUser['email']);

    if($dataUser['email'] !== $user->getEmail()){
      if($user->getRoleId() == ROLE_ADMIN || $user->getRoleId() == ROLE_EMPLOYE){
        if(!$client){
          throw new Exception('Cet utilisateur est introuvable');
        }
        if($this->normaliserMot($dataUser['nom']) !== $this->normaliserMot($client->getNom())||
            $this->normaliserMot($dataUser['prenom']) !== $this->normaliserMot($client->getPrenom())){
          throw new Exception('Les informations ne correspondent pas');
        }
        $data['user_id'] = $client->getUserId();
      }
    }else{
      if($this->normaliserMot($dataUser['nom']) !== $this->normaliserMot($user->getNom())||
          $this->normaliserMot($dataUser['prenom']) !== $this->normaliserMot($user->getPrenom())){
        throw new Exception('Les informations ne correspondent pas');
      }
    }

    if(empty($dataMenu)){
      throw new Exception("Veuillez choisir au moins un menu");
    }

    if(empty($data['lieu_livraison'])){
      throw new Exception("Veuillez saisir une adresse valide");
    }

    $menusCommande = $this->verifMenu($dataMenu);
    $prixPresta = $this->verifPresta($dataPresta, $prixTotalPresta, $menusCommande);
    $boissonsCommande = $this->verifBoisson($dataBoisson);
    $this->verifLivraison($data, 10);
    $this->prixTotal($prixPresta, $menusCommande, $boissonsCommande, $data);

    $data['date_commande'] = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'))->format('Y-m-d H:i:s');
    $data['status_id'] = STATUT_TRANSMISE;
    $data['nb_commande'] = $this->genererNbCommande();
    $data['nb_personne'] = $this->calculPrixService->calculerNbPersonneCommande($menusCommande);

    try{
      $this->commandeRepository->beginTransaction();
      $commande = $this->commandeRepository->creerCommande($data);

      $commandeId = $commande->getCommandeId();
        $this->ajouterMenuCommande($commandeId, $dataMenu);
        $this->ajouterPrestaCommande($commandeId, $prixTotalPresta, $dataPresta, $dataMenu);
        $this->ajouterBoissonCommande($commandeId, $dataBoisson);

      $this->commandeRepository->commit();
    }catch(Exception $e){
      $this->commandeRepository->rollBack();
      throw new Exception('Erreur lors de la création de la commande : '. $e->getMessage());
    }

    //Envoie du mail de confirmation
    $html = $this->mailService->recupererHtml('commande/confirmationMail', ['prenom' => $dataUser['prenom'], 'nbCommande' => $data['nb_commande']]);
    $objet = 'Confirmation de votre commande';
    file_put_contents(APP_ROOT . '/public/afficheMail.php', $html);
    //$this->mailService->envoyer($dataUser['email'], $objet, $html);

    return $commande;
  }

  // AJOUT DES ELEMENTS DE LA COMMANDE
  public function ajouterPrestaCommande(int $commandeId, float $prixTotalPresta, array $dataPresta, array $dataMenu)
  {
    $presta = [];
    foreach($dataPresta as $data){
      $datePresta = new DateTimeImmutable($data['date_presta'], new DateTimeZone('Europe/Paris'));
      $prestaParId = $this->prestationRepository->trouverPrestationparId($data['prestation_id']);

      $data['date_presta'] = $datePresta->format('Y-m-d H:i:s');
      $data['date_retour_prevu'] = $datePresta->modify('+10 days')->format('Y-m-d H:i:s');
      $data['commande_id'] = $commandeId;
      $data['date_retour'] = null;
      $data['taux_retard'] = 600;
      $data['prix_total_presta'] = $prixTotalPresta;
      $data['montant_prestation'] = $this->calculPrixService->calculerPresta(
        $dataMenu,
        $prestaParId->getPrixPresta(),
        $prestaParId->getSeuil(),
        $prestaParId->getSupplement(),
      );

      $presta[] = $this->commandePrestaRepository->ajouterPrestaCommande($data);
      
    }

    return $presta;
  }

  public function ajouterMenuCommande(int $commandeId, array $dataMenu)
  {
    $menu = [];
    foreach($dataMenu as $data){
      $menuParId = $this->menuRepository->trouverMenuParId($data['menu_id']);
      $data['commande_id'] = $commandeId;
      $data['prix_unitaire'] = $menuParId->getPrixPersonne();
      $data['prix_total_menu'] = $this->calculPrixService->calculerTotalMenu(
        $menuParId->getPrixPersonne(), 
        $menuParId->getNombrePersonneMin(),
        $data['nb_personne_menu']
      );
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
      $tabBoisson = [
        ['prix_boisson' => $boissonParId->getPrixBoisson() ,
        'quantite' => $data['quantite'],]
      ];

      $data['commande_id'] = $commandeId;
      $data['prix_unitaire'] = $boissonParId->getPrixBoisson();
      $data['prix_total_boisson'] = $this->calculPrixService->calculerTotalBoisson($tabBoisson);
      $boisson[] = $this->commandeBoissonRepository->ajouterBoissonCommande($data);

      $this->boissonService->decrementerStockBoisson($data['boisson_id'], $data['quantite']);

    }

    return $boisson;
  }

  // AFFICHAGE COMMANDE
  public function afficherCommandes(int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();
    
    $commande = $this->commandeRepository->trouverCommande();
    
    return $this->ajouterElementCommande($commande);
  }

  public function afficherCommandesUser(int $userId, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE, ROLE_UTILISATEUR])) throw new AccesRefuseException();

    $commande = $this->commandeRepository->trouverCommandeUser($userId);
    
    return $this->ajouterElementCommande($commande);
  }

  public function afficherDetailsCommande(int $idCommande, int $userId, int $role)
  {
    $commande = $this->commandeRepository->trouverCommandeParId($idCommande);
    VerifService::verifExisteEnBase($commande, "cette commande ");

    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE]) && $commande->getUserId() !== $userId) throw new AccesRefuseException();

    $user = $this->userRepository->trouveUtilisateurById($commande->getUserId());
    VerifService::verifExisteEnBase($user, "cet utilisateur"); 

    $commande->setUser($user);

    if($role === ROLE_UTILISATEUR && $commande->getUserId() !== $user->getUserId()){
      throw new Exception("Vous n'avez pas accèes a cette commande");
    }

    $commandeElement = $this->ajouterElementCommande([$commande]);
    
    return $commandeElement[0];
  }

  public function afficherCommandesFiltre(array $commandesFiltre, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();
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
    if(!in_array($roleId, [ROLE_ADMIN, ROLE_EMPLOYE, ROLE_UTILISATEUR])) throw new AccesRefuseException();

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
      $menu = $anciennesDonneesMenu[$menuId]['menu'];
      $data['prix_total_menu'] = $this->calculPrixService->calculerTotalMenu(
          $menu->getPrixPersonne(),
          $menu->getNombrePersonneMin(),
          $data['nb_personne_menu']
      );
      $data['commande_id'] = $commandeId;
      unset($data['menu']);
      unset($data['prix_unitaire']);
      $donneesFinalMenu[$menuId] = $data;
    }

    $diffStockMenu = [];
    foreach($nouvellesDonneesMenu as $menuId => $nouveauMenu){
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
      $boisson = $anciennesDonneesBoisson[$boissonId]['boisson'];

      $tabBoisson = [
        [
          'prix_boisson' => $boisson->getPrixBoisson(),
          'quantite' => $data['quantite']
        ]
      ];

    $data['prix_total_boisson'] = $this->calculPrixService->calculerTotalBoisson($tabBoisson);
      $data['commande_id'] = $commandeId;
      unset($data['boisson']);
      unset($data['prix_unitaire']);
      $donneesFinalBoisson[$boissonId] = $data;
    }


    $diffStockBoisson = [];
    foreach($nouvellesDonneesBoisson as $boissonId => $nouvelleBoisson){
      $ancienneBoisson = $anciennesDonneesBoisson[$boissonId];
      $differenceBoisson = $nouvelleBoisson['quantite'] - $ancienneBoisson['quantite'];
      $diffStockBoisson[$boissonId] = $differenceBoisson;
    }



    // Je verifie que tout est ok pour la modification de la commande
    $menusCommande = $this->verifMenu($nouvellesDonneesMenu, $diffStockMenu);
    $prixPresta = $this->verifPresta($dataPresta, $prixTotalPresta, $menusCommande);
    $boissonsCommande = $this->verifBoisson($nouvellesDonneesBoisson, $diffStockBoisson);
    $this->verifLivraison($nouvellesDonneesCommande, 4);
    $this->prixTotal($prixPresta, $menusCommande, $boissonsCommande, $nouvellesDonneesCommande);


    // Je m'assure que si un employe modifie une commande, un motif est envoye
    if(($roleId === ROLE_EMPLOYE || $roleId === ROLE_ADMIN) && empty($motif)){
      throw new Exception('Un motif est obligatoire pour modifier une commande');
    }

    if(empty($nouvellesDonneesCommande['lieu_livraison'])){
      throw new Exception("Veuillez saisir une adresse valide");
    }

    $donneesCommande = [
      'commande_id' => $nouvellesDonneesCommande['commande_id'],
      'nb_personne' => $nouvellesDonneesCommande['nb_personne'],
      'date_livraison' => $nouvellesDonneesCommande['date_livraison'],
      'lieu_livraison' => $nouvellesDonneesCommande['lieu_livraison'],
      'prix_livraison' => $nouvellesDonneesCommande['prix_livraison'],
      'prix_total' => $nouvellesDonneesCommande['prix_total'],
    ];

    try{
      $this->commandeRepository->beginTransaction();
      //$this->commandeRepository->modifierCommande($nouvellesDonneesCommande);
      $this->commandeRepository->modifierCommande($donneesCommande);

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

      $this->modifierPrestaCommande($commandeId, $dataPresta, $prixTotalPresta, $dataMenu);

      $this->commandeRepository->commit();
    }catch(Exception $e){
      $this->commandeRepository->rollBack();
      throw new Exception('Erreur lors de la modification de la commande : '. $e->getMessage());
    }

    $user = $this->userRepository->trouveUtilisateurById($nouvellesDonneesCommande['user_id']);
    if($roleId === ROLE_UTILISATEUR){
      // Mail modification
      $html = $this->mailService->recupererHtml('commande/modificationClientMail', ['prenom' => $user->getPrenom(), 'nbCommande' => $nouvellesDonneesCommande['nb_commande']]);
      $objet = 'Modification de votre commande';
      file_put_contents(APP_ROOT . '/public/afficheMail.php', $html);
      //$this->mailService->envoyer($user->getEmail(), $objet, $html);

    }elseif($roleId === ROLE_EMPLOYE || $roleId === ROLE_ADMIN){
      // Mail modification + motif
      $html = $this->mailService->recupererHtml('commande/modificationEmployeMail', ['prenom' => $user->getPrenom(), 'nbCommande' => $nouvellesDonneesCommande['nb_commande'], 'motif' => $motif]);
      $objet = 'Modification de votre commande';
      file_put_contents(APP_ROOT . '/public/afficheMail.php', $html);
      //$this->mailService->envoyer($user->getEmail(), $objet, $html);
    }
  }

  private function modifierPrestaCommande(int $commandeId, array $dataPresta, float $prixTotalPresta, array $dataMenu)
  {
    $prestas = $this->commandePrestaRepository->trouverPrestaDeLaCommande($commandeId);

    $anciennesDonneesPresta = [];

    foreach($prestas as $presta){
      $anciennesDonneesPresta[$presta->getPrestationId()] = $presta->deshydrate();
    }

    $nouvellesDonneesPresta = [];

    foreach($dataPresta as $presta){
      $datePresta = new DateTimeImmutable($presta['date_presta'], new DateTimeZone('Europe/Paris'));
      $presta['date_retour_prevu'] = $datePresta->modify('+10 days')->format('Y-m-d H:i:s');
      $presta['prix_total_presta'] = $prixTotalPresta;
      $nouvellesDonneesPresta[$presta['prestation_id']] = $presta;
    }

    // Je recupere les ids des anciennes prestas
    $anciensIds = array_keys($anciennesDonneesPresta);
    // Je recupere les ids des nouvelles prestas
    $nouveauxIds = array_keys($nouvellesDonneesPresta);

    // Je compare les ids anciens ids aux nouveaux et stock ceux qui ne sont pas dans le nouveau
    $aSupprimer = array_diff($anciensIds, $nouveauxIds);
    $aAjouter = array_diff($nouveauxIds, $anciensIds);

    foreach($aSupprimer as $prestaId){
      $this->commandePrestaRepository->supprimerPrestaDeLaCommande($commandeId, $prestaId);
    }

    $donneesPrestaAjout = [];
    foreach($aAjouter as $prestaId){
      $donneesPrestaAjout[] = $nouvellesDonneesPresta[$prestaId];
    }

    if(!empty($donneesPrestaAjout)){
      $this->ajouterPrestaCommande($commandeId, $prixTotalPresta, $donneesPrestaAjout, $dataMenu);
    }

    foreach($nouvellesDonneesPresta as $prestaId => $data){
    if(in_array($prestaId, $anciensIds)){
      $data = array_merge($anciennesDonneesPresta[$prestaId], $data);
      $data['commande_id'] = $commandeId;
      unset($data['prestation']);
      unset($data['montant_prestation']);
      unset($data['date_retour']);
      unset($data['taux_retard']);
      $this->commandePrestaRepository->modifierPrestaDeLaCommande($data);
    }
}

  }

  public function modifierStatusCommande(int $commandeId, int $status, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    $this->changerStatutCommande($commandeId, $status);
  }

  private function changerStatutCommande(int $commandeId, int $status)
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
        file_put_contents(APP_ROOT . '/public/afficheMail.php', $html);
        //$this->mailService->envoyer($user->getEmail(), $objet, $html);
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
        file_put_contents(APP_ROOT . '/public/afficheMail.php', $html);
        //$this->mailService->envoyer($user->getEmail(), $objet, $html);
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
        file_put_contents(APP_ROOT . '/public/afficheMail.php', $html);
        //$this->mailService->envoyer($user->getEmail(), $objet, $html);
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
        file_put_contents(APP_ROOT . '/public/afficheMail.php', $html);
        //$this->mailService->envoyer($user->getEmail(), $objet, $html);
        sleep(10);
        if($necessiteRetour === false){
          $this->changerStatutCommande($commandeId, STATUT_TERMINEE);
        }else{
          $this->changerStatutCommande($commandeId, STATUT_ATTEND_RETOUR);
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
        file_put_contents(APP_ROOT . '/public/afficheMail.php', $html);
        //$this->mailService->envoyer($user->getEmail(), $objet, $html);
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
          'lienAvis' => BASE_URL . '/avis?nb_commande='.$commande->getNbCommande()
        ]);
        $objet = 'Commande terminée';
        file_put_contents(APP_ROOT . '/public/afficheMail.php', $html);
        //$this->mailService->envoyer($user->getEmail(), $objet, $html);
      break;
    }
  }

  // ANNULATION DE LA COMMANDE
  public function annulerCommande(int $commandeId, int $roleId, int $userId, ?string $motif = null)
  {
    if(!in_array($roleId, [ROLE_ADMIN, ROLE_EMPLOYE, ROLE_UTILISATEUR])) throw new AccesRefuseException();

    $this->modificationNonPermise($commandeId);
    $status = STATUT_ANNULEE;
    $this->modifierStatusCommande($commandeId, $status, $roleId);

    $this->restaurerStockBoisson($commandeId);
    $this->restaurerStockMenu($commandeId);

    $user = $this->userRepository->trouveUtilisateurById($userId);
    $commande = $this->commandeRepository->trouverCommandeParId($commandeId);


    if($roleId === ROLE_UTILISATEUR){
      // Mail annulation
      $html = $this->mailService->recupererHtml('commande/annulationClientMail', ['prenom' => $user->getPrenom(), 'nbCommande' => $commande->getNbCommande()]);
      $objet = 'Annulation de votre commande';
      file_put_contents(APP_ROOT . '/public/afficheMail.php', $html);
      //$this->mailService->envoyer($user->getEmail(), $objet, $html);
    }elseif(($roleId === ROLE_EMPLOYE || $roleId === ROLE_ADMIN) && !empty($motif)){
      // Mail annulation + motif
      $html = $this->mailService->recupererHtml('commande/annulationEmployeMail', ['prenom' => $user->getPrenom(), 'nbCommande' => $commande->getNbCommande(), 'motif' => $motif]);
      $objet = 'Annulation de votre commande';
      file_put_contents(APP_ROOT . '/public/afficheMail.php', $html);
      //$this->mailService->envoyer($user->getEmail(), $objet, $html);
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
    $menusIds = [];
    $menusCommande = [];
    foreach($dataMenu as $menu){
      
      $this->verifDoubleDeLaCommande($menu['menu_id'], $menusIds);
      $menusIds[] = $menu['menu_id'];

      $menuParId = $this->menuRepository->trouverMenuParId($menu['menu_id']);
      VerifService::verifExisteEnBase($menuParId, 'ce menu');

      $nbPersMin = $menuParId->getNombrePersonneMin();

      if(!$menuParId->isMenuActif()){
        throw new Exception("Ce menu n'est plus disponible");
      }

      if($menu['nb_personne_menu'] < $nbPersMin){
        throw new Exception('Le nombre de personnes minimum est de '. $nbPersMin .' pour le menu '. $menuParId->getTitre());
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
    $prestaCalcul = [];
    foreach($dataPresta as $presta){
      $prestationParId = $this->prestationRepository->trouverPrestationparId($presta['prestation_id']);
      VerifService::verifExisteEnBase($prestationParId, 'cette prestation');

      $prixPresta[] = $prestationParId->getPrixPresta();
      $prestaCalcul[] = [
        'prix_presta' => $prestationParId->getPrixPresta(),
        'seuil' => $prestationParId->getSeuil(),
        'supplement' => $prestationParId->getSupplement()
      ];
    }

    $total = $this->calculPrixService->calculerTotalpresta($menusCommande, $prestaCalcul);

    if($prixTotalPresta != $total){
      throw new Exception('Erreur prix total presta '. $total);
    }

    return $prestaCalcul;
  }

  private function verifBoisson(array $dataBoisson, array $diffStock = [])
  {
    $boissonsCommande = [];
    $boissonsIds = [];
    foreach($dataBoisson as $boisson){

      $this->verifDoubleDeLaCommande($boisson['boisson_id'], $boissonsIds);
      $boissonsIds[] = $boisson['boisson_id'];

      $boissonParId = $this->boissonRepository->trouverBoissonParId($boisson['boisson_id']);

      VerifService::verifExisteEnBase($boissonParId, 'cette boisson');

      $boissonsCommande[] = [
        'prix_boisson' => $boissonParId->getPrixBoisson(),
        'quantite' => $boisson['quantite']
      ];

      VerifService::verifNbPositif($boisson['quantite'],
      "Veuillez choisir une quantité d'au moin 1 pour la boisson ".$boissonParId->getNomBoisson());

      $quantite = $diffStock[$boisson['boisson_id']] ?? $boisson['quantite'];

      $this->boissonService->verifStockBoisson($boisson['boisson_id'], $quantite);
    }
    return $boissonsCommande;
  }

  private function verifLivraison(array $data, int $delais)
  {
    $dateLivraison = new DateTimeImmutable($data['date_livraison']);
    $delaisMinimum = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'))->modify('+ '. $delais. ' days');

    if($dateLivraison < $delaisMinimum){
      throw new Exception('Le delais minimum entre la commande et la livraison est de '.$delais.' jours');
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

  private function verifDoubleDeLaCommande(int $id, array $tableauIds)
  {
    if(in_array($id, $tableauIds)){
      throw new Exception('Une commande ne peut pas contenir plusieurs fois le même menu ou la même boisson');
    }
  }

}
