<?php

namespace App\Controller;

use App\Factory\ContainerId;
use App\Service\AvisService;
use Exception;

class AvisController extends Controller
{
  private AvisService $avisService;

  public function __construct() {
    parent::__construct();
    $this->avisService = ContainerId::getAvisService();
  }

  public function creerAvis()
  {
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $this->checkCsrfToken();
  
      $data = [
      'note' => $_POST['note'], 
      'commentaire' => $_POST['commentaire'], 
      ];

      $data = $this->nettoyerDonnees($data);

    try{
      $nbCommande = (int)$_GET['nb_commande'];
      //var_dump($nbCommande);
      $userId = (int)$_SESSION['user_id'];
      $this->avisService->creerAvis($data, $nbCommande, $userId);
      $_SESSION['succes'] = 'Merci pour votres retour nous ésperons vous revoir très bientot';
      header('location: /avis');
      exit;
    }catch(Exception $e){
      $_SESSION['erreur'] = $e->getMessage();
      header('location: /avis');
      exit;
    }
    }else{
      $this->render('pages/client/avis');
    }
  }

  public function afficherAvis()
  {
    $role = $_SESSION['role_id'];
    $avis = $this->avisService->afficherAvis($role);
    $this->render('pages/listeAvis', ['avis' => $avis]);
  }

  public function modifierStatutAvis()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();
  
      $publier = htmlspecialchars($_POST['publie']);
      $avisId = (int)$_POST['id'];
      $role = $_SESSION['role_id'];
      
      try{
        $this->avisService->modifierStatusPublie($avisId, $publier, $role);

        if($publier == 1){
          $_SESSION['succes'] = 'Avis accepté';
        }else{
          $_SESSION['succes'] = 'Avis masqué';
        }
        header('location: /avis');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /modifierStatutAvis?id='.$avisId);
        exit;
      }
    }else{
      $this->render('pages/employe/modifierStatutAvis');
    }
  }

}