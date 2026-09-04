<?php

namespace App\Controller;

use App\Service\CommandeService;
use App\Factory\ContainerId;
use Exception;


class CommandeController extends Controller
{
  private CommandeService $commandeService;

  public function __construct() {
    parent::__construct();
    $this->commandeService = ContainerId::getCommandeService();
  }

  public function creerCommande()
  {
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

      $dataCommande = $this->nettoyerDonnees($dataCommande);
      $dataMenus = $this->nettoyerDonnees($dataMenus);
      $dataPrestas = $this->nettoyerDonnees($dataPrestas);
      $dataBoissons = $this->nettoyerDonnees($dataBoissons);

      $role = $_SESSION['role_id'];

      try{
        $dataCommande['user_id'] = (int) $_SESSION['user_id'];
        $commande = $this->commandeService->creerCommande($dataCommande, $dataMenus, $dataPrestas, $dataBoissons, $prixTotalPresta, $dataUser, $role);

        $commandeId = $commande->getCommandeId();
        $this->commandeService->ajouterMenuCommande($commandeId, $dataMenus);
        $this->commandeService->ajouterPrestaCommande($commandeId, $prixTotalPresta, $dataPrestas);
        $this->commandeService->ajouterBoissonCommande($commandeId, $dataBoissons);
        $_SESSION['succes'] = 'Commande passée vous avez recus un mail de confirmation';
        header('location: /commandeMenu');
        exit;
      }catch(Exception $e){
      $_SESSION['erreur'] = $e->getMessage();
      header('location: /commandeMenu');
      exit;
    }
    }else{
      $this->render('pages/client/commandeMenu');
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
        

        $dataCommande = $this->nettoyerDonnees($dataCommande);
        $dataMenus = $this->nettoyerDonnees($dataMenus);
        $dataPrestas = $this->nettoyerDonnees($dataPrestas);
        $dataBoissons = $this->nettoyerDonnees($dataBoissons);

        $motif = htmlspecialchars($_POST['motif']);

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
      $motif = htmlspecialchars($_POST['motif']);
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