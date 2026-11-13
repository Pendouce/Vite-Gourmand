<?php

namespace App\Controller;

use App\Service\CommandeService;
use App\Factory\ContainerId;
use App\Service\BoissonService;
use App\Service\CalculPrixService;
use App\Service\MenuService;
use App\Service\PrestationService;
use App\Service\UserService;
use Exception;


class CommandeController extends Controller
{
  private CommandeService $commandeService;
  private PrestationService $prestationService;
  private UserService $userService;
  private MenuService $menuService;
  private BoissonService $boissonService;
  private CalculPrixService $calculPrixService;

  public function __construct() {
    parent::__construct();
    $this->commandeService = ContainerId::getCommandeService();
    $this->prestationService = ContainerId::getPrestationService();
    $this->userService = ContainerId::getUserService();
    $this->menuService = ContainerId::getMenuService();
    $this->boissonService = ContainerId::getBoissonService();
    $this->calculPrixService = new CalculPrixService;
  }

  public function calculerPrixLivraison()
  {
    $adresseClient = $_POST['lieu_livraison']." ".$_POST['ville']." ".$_POST['code_postal'];
    try
    {
      $calculPrix = $this->calculPrixService->calculerPrixDeLivraison(ADRESSE_VG, $adresseClient);
      //echo json_encode($calculPrix);
      echo json_encode(['succes' => true, 'prix' => $calculPrix]);
    }catch(Exception $e){
      echo json_encode(['succes' => false, 'message' => $e->getMessage()]);
    }
  }

  public function creerCommande()
  {
    if(!isset($_SESSION['role_id'])){
      header('location: /connexion');
      exit;
    }
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE, ROLE_UTILISATEUR]);

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $this->checkCsrfToken();

      // Je boucle sur les ids des menus que je vais recevoir
      // Et je recupere le nb_personne de chaque menu
      $dataMenus = [];
      foreach($_POST['menu'] as $index => $menuId){
        $dataMenus[] = [
          'menu_id' => (int) $menuId,
          'nb_personne_menu' => (int) $_POST['nb_personne_menu'][$index]
        ];
      }

      $dataPrestas = [];
      foreach(($_POST['prestation'] ?? []) as $prestaId){
        $prestaId = (int) $prestaId;
        if ($prestaId === '' || $prestaId <= 0) {
          continue;
        }
        $dataPrestas[] = [
          'prestation_id' => (int) $prestaId,
          'date_presta' => $_POST['date_livraison'],
          'adresse_presta' => $_POST['lieu_livraison'],
        ];
      }
      $prixTotalPresta = $_POST['prix_total_presta'];

      $dataBoissons = [];
      foreach($_POST['boisson'] as $index => $boissonId){
        $dataBoissons[] = [
          'boisson_id' => (int) $boissonId,
          'quantite' => (int) $_POST['quantite'][$index]
        ];
      }
      
      $dataUser = [
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'email' => $_POST['email'],
        'telephone' => $_POST['telephone'],
      ];

      $dataCommande = [
        'nb_personne' => $_POST['nb_personne'],
        'date_livraison' => $_POST['date_livraison'],
        'lieu_livraison' => $_POST['lieu_livraison'],
        'prix_livraison' => $_POST['prix_livraison'],
        'prix_total' => $_POST['prix_total'],
      ];

      $dataCommande = $this->formaterDonnees($dataCommande);
      $dataMenus = $this->formaterDonnees($dataMenus);
      $dataPrestas = $this->formaterDonnees($dataPrestas);
      $dataBoissons = $this->formaterDonnees($dataBoissons);

      //var_dump($dataPrestas);

      $role = $_SESSION['role_id'];

      try{
        $dataCommande['user_id'] = (int) $_SESSION['user_id'];
        $this->commandeService->creerCommande($dataCommande, $dataMenus, $dataPrestas, $dataBoissons, $prixTotalPresta, $dataUser, $role);
        if(in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])){
          $_SESSION['succes'] = 'Commande passée, un mail de confirmation a été envoyé au client';
          header('location: /commandes');
        exit;
        }else{
          $_SESSION['succes'] = 'Commande passée vous avez recus un mail de confirmation';
          header('location: /mesCommandes');
        exit;
        }
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        $id = (int) ($_GET['id'] ?? 0);
        $menu = $id ? $this->menuService->afficherMenuParId($id) : null;

        if (!$menu) {
          header('location: /menu'); // la route de ta page catalogue
          exit;
        }

      }
    }else{
      $role = $role = $_SESSION['role_id'];
      $userId = $_SESSION['user_id'];
      $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

      $prestations = $this->prestationService->afficherPrestation($role);
      $prestation = $this->prestationService->afficherPrestationParId($role);
      $prestationsParType = $this->prestationService->afficherPrestationParType();
      $menu = $this->menuService->afficherMenuParId($id);
      $menus = $this->menuService->afficherMenus($role);
      $boissons = $this->boissonService->afficherBoisson($role);
      $boisson = $id ? $this->boissonService->afficherBoissonParId($id) : null;

      $evenements = $this->menuService->afficherEvenements();
      $themes = $this->menuService->afficherThemes();
      $regimes = $this->menuService->afficherRegimes();
    
      $prixMenus = array_map(fn ($e) => $e->getPrixPersonne(), $menus);
      $prixMin = min($prixMenus);
      $prixMax = max($prixMenus);

      $modeCommande = $modeCommande ?? false;

      if($role === ROLE_UTILISATEUR){
        $user = $this->userService->afficheInfo($userId);
      }else{
       $user = null;
      }


      $this->render('pages/client/commandeMenu', [
        'user' => $user, 
        'menus' => $menus, 'menu' => $menu,
        'evenements' => $evenements, 
        'themes' => $themes, 'regimes' => $regimes,
        'prestations' => $prestations, 'prestation' => $prestation,
        'prestationsParType' => $prestationsParType, 
        'boissons' => $boissons, 'boisson' => $boisson,
        'prixMin' => $prixMin, 'prixMax' => $prixMax,
        'role' => $role, 'modeCommande' => $modeCommande,
        'titre' => 'commande menu']);
    }
    }

    public function afficherCommandesEmploye(){
      $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

      $role = $_SESSION['role_id'];

      $commandes = $this->commandeService->afficherCommandes($role);
      $this->render('pages/employe/commandes', ['commandes' => $commandes]);
    }

    public function afficherCommandesUser(){
      $this->accesPage([ROLE_UTILISATEUR]);

      $userId = $_SESSION['user_id'];
      $role = $_SESSION['role_id'];

      $commandes = $this->commandeService->afficherCommandesUser($userId, $role);
      
      $this->render('pages/client/mesCommandes', ['commandes' => $commandes]);
    }

    public function afficherDetailsCommande(){
      $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE, ROLE_UTILISATEUR]);

      $commandeId = (int)$_GET['id'];
      $userId = $_SESSION['user_id'];
      $role = $_SESSION['role_id'];

      $commande = $this->commandeService->afficherDetailsCommande($commandeId, $userId, $role);
      $this->render('pages/detailCommande', ['commande' => $commande]);
    }

    public function afficherCommandeFiltre()
    {
      $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

      $commandesFiltre = [];

      if(!empty($_GET['menu'])){
        $commandesFiltre['menu'] = $_GET['menu'];
      }

      if(!empty($_GET['boisson'])){
        $commandesFiltre['boisson'] = $_GET['boisson'];
      }

      if(!empty($_GET['user'])){
        $commandesFiltre['user'] = $_GET['user'];
      }

      if(!empty($_GET['status_id'])){
        $commandesFiltre['status_id'] = $_GET['status_id'];
      }

      if(!empty($_GET['nb_commande'])){
        $commandesFiltre['nb_commande'] = $_GET['nb_commande'];
      }

      $role = $_SESSION['role_id'];

      $commandes = $this->commandeService->afficherCommandesFiltre($commandesFiltre, $role);
      $status = $this->commandeService->afficherStatusCommandes();
      $this->render('pages/employe/commandesFiltre' , ['commandes' => $commandes, 'status' => $status]);
    }

    public function modifierCommande()
    {
      $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE, ROLE_UTILISATEUR]);
      if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();

        $dataMenus = [];
        foreach($_POST['menu'] as $index => $menuId){
          $dataMenus[] = [
            'menu_id' => (int) $menuId,
            'nb_personne_menu' => (int) $_POST['nb_personne_menu'][$index]
          ];
        }

        $dataPrestas = [];
        foreach($_POST['prestation'] as $prestaId){
          $dataPrestas[] = [
            'prestation_id' => (int) $prestaId,
            'date_presta' => $_POST['date_presta'],
            'adresse_presta' => $_POST['adresse_presta'],
          ];
        }
        $prixTotalPresta = $_POST['prix_total_presta'];
        
        $dataBoissons = [];
        foreach($_POST['boisson'] as $index => $boissonId){
          $dataBoissons[] = [
            'boisson_id' => (int) $boissonId,
            'quantite' => (int) $_POST['quantite'][$index]
          ];
        }

        $dataCommande = [
          'nb_personne' => $_POST['nb_personne'],
          'date_livraison' => $_POST['date_livraison'],
          'lieu_livraison' => $_POST['lieu_livraison'],
          'prix_livraison' => $_POST['prix_livraison'],
          'prix_total' => $_POST['prix_total'],
        ];
        

        $dataCommande = $this->formaterDonnees($dataCommande);
        $dataMenus = $this->formaterDonnees($dataMenus);
        $dataPrestas = $this->formaterDonnees($dataPrestas);
        $dataBoissons = $this->formaterDonnees($dataBoissons);

        $motif = $this->formaterValeur($_POST['motif']);

        try{
          $commandeId = (int) $_POST['id'];
          $roleId = (int) $_SESSION['role_id'];
          $this->commandeService->modifierCommande($commandeId, $roleId, $dataCommande, $dataMenus, $dataPrestas, $dataBoissons, $prixTotalPresta, $motif);

          if($roleId === ROLE_UTILISATEUR){
            $_SESSION['succes'] = 'Commande modifier vous avez recus un mail de confirmation';
            header('location: /mesCommandes');
            exit;
          }elseif($roleId === ROLE_EMPLOYE || $roleId === ROLE_ADMIN){
            $_SESSION['succes'] = 'Commande modifier un mail a été envoyé au client';
            header('location: /commandes');
            exit;
          }

        }catch(Exception $e){
          $_SESSION['erreur'] = $e->getMessage();
          header('location: /modifierCommande');
          exit;
        }
      }else{
        $this->render('pages/modifierCommande');
      }
    }

    public function modifierStatusCommande()
    {
      $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('location: /');
        exit;
      }
      $this->checkCsrfToken();

      $commandeId = (int) $_POST['id'];
      $status = (int)$_POST['status_id'];
      $role = $_SESSION['role_id'];

      try{
        $this->commandeService->modifierStatusCommande($commandeId, $status, $role);
        $_SESSION['succes'] = 'Status modifié';
        header('location: /commandes');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /commandes');
        exit;
      }
    }
    
    public function annulerCommandeUser()
    {
      $this->accesPage([ROLE_UTILISATEUR]);
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('location: /');
        exit;
      }
      $this->checkCsrfToken();
  
      $commandeId = (int) $_POST['id'];
      $roleId = $_SESSION['role_id'];
      $userId = $_SESSION['user_id'];
      try{
        $this->commandeService->annulerCommande($commandeId, $roleId, $userId);
       $_SESSION['succes'] = 'Commande annulée';
        header('location: /commandes');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /commandes');
        exit;
      }
    }

    public function annulerCommandeEmploye()
    {
      $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('location: /');
        exit;
      }
      $this->checkCsrfToken();

      $commandeId = (int) $_POST['id'];
      $roleId = (int)$_SESSION['role_id'];
      $userId = $_SESSION['user_id'];
      $motif = $this->formaterValeur($_POST['motif']);
      try{
        $this->commandeService->annulerCommande($commandeId, $roleId, $userId, $motif);
       $_SESSION['succes'] = 'Commande annulée';
        header('location: /mesCommandes');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /mesCommandes');
        exit;
      }
  }

}