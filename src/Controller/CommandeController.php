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
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      // Je boucle sur les ids des menus que je vais recevoir
      // Et je recupere le nb_personne de chaque menu
      $dataMenus = [];
      foreach($_POST['menu'] as $index => $menuId){
        $dataMenus[] = [
          'menu_id' => (int) $menuId,
          'nb_personne_menu' => (int) $_POST['nb_personne_menu'][$index]
        ];
        //var_dump($dataMenus);
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
      //var_dump($dataCommande['nb_personne']);

      $dataCommande = $this->nettoyerDonnees($dataCommande);
      $dataMenus = $this->nettoyerDonnees($dataMenus);
      $dataPrestas = $this->nettoyerDonnees($dataPrestas);
      $dataBoissons = $this->nettoyerDonnees($dataBoissons);

      try{
        $dataCommande['user_id'] = (int) $_SESSION['user_id'];
        $commande = $this->commandeService->creerCommande($dataCommande, $dataMenus, $dataPrestas, $dataBoissons, $prixTotalPresta, $dataUser);

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
      $commandes = $this->commandeService->afficherCommandes();
      $this->render('pages/employe/commandes', ['commandes' => $commandes]);
    }

    public function afficherCommandesUser(){
      $userId = $_SESSION['user_id'];
      $commandes = $this->commandeService->afficherCommandesUser($userId);
      
      $this->render('pages/client/mesCommandes', ['commandes' => $commandes]);
    }

    public function afficherCommandesParId(){
      $commandeId = $_GET['id'];
      $commande = $this->commandeService->afficherCommandeParId($commandeId);
      $this->render('pages/detailCommande', ['commandes' => $commande]);
    }

}