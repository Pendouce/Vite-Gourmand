<?php

namespace App\Controller;

use App\Repository\CommandeMenuRepository;
use App\Repository\CommandePrestaRepository;
use App\Repository\CommandeRepository;
use App\Repository\MenuRepository;
use App\Repository\PrestationRepository;
use App\Repository\UserRepository;
use App\Service\CalculPrixService;
use App\Service\CommandeService;
use App\Service\MailService;
use Exception;

class CommandeController extends Controller
{
  private CommandeService $commandeService;

  public function __construct() {
    parent::__construct();
    $commandeRepository = new CommandeRepository();
    $commandePrestaRepository = new CommandePrestaRepository();
    $commandeMenuRepository = new CommandeMenuRepository();
    $menuRepository = new MenuRepository;
    $prestationRepository = new PrestationRepository();
    $userRepository = new UserRepository();
    $calculPrixService = new CalculPrixService();
    $mailService = new MailService();
    $this->commandeService = new CommandeService($commandeRepository, $commandePrestaRepository,$commandeMenuRepository, 
    $menuRepository, $prestationRepository, $userRepository, $calculPrixService, $mailService);
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

      $this->nettoyerDonnees($dataCommande);
      $this->nettoyerDonnees($dataMenus);
      $this->nettoyerDonnees($dataPrestas);

      try{
        $dataCommande['user_id'] = (int) $_SESSION['user_id'];
        $commande = $this->commandeService->creerCommande($dataCommande, $dataMenus, $dataPrestas, $prixTotalPresta, $dataUser);

        $commandeId = $commande->getCommandeId();
        $this->commandeService->ajouterMenuCommande($commandeId, $dataMenus);
        $this->commandeService->ajouterPrestaCommande($commandeId, $prixTotalPresta, $dataPrestas);
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
}