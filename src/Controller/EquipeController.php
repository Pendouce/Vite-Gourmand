<?php

namespace App\Controller;

use App\Factory\ContainerId;
use App\Service\EquipeService;
use Exception;

class EquipeController extends Controller
{
  private EquipeService $equipeService;

  public function __construct() {
    parent::__construct();
    $this->equipeService = ContainerId::getEquipeService();
  }

  public function creerMembre()
  {
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $data = [
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'poste' => $_POST['poste'],
        'description' => $_POST['description'],
      ];

      if (key_exists('photo', $_FILES) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $data['photo'] = $this->uploadImage($_FILES['photo'], "equipe");
      }

      $data = $this->nettoyerDonnees($data);

      try{
        $this->equipeService->creerMembre($data);
         $_SESSION['succes'] = "Nouveau membre de l'équipe ajouté";
        header('location: /equipeAdmin');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /creerMembre');
        exit;
      }
    }else{
      $this->render('pages/admin/creerMembre');
    }
  }

  public function afficherMembres()
  {
    $role = $_SESSION['role_id'];
    $membres = $this->equipeService->afficherMembres($role);

    $this->render('pages/equipe', ['membres' => $membres]);
  }

   public function modifierMembre()
  {
    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
      $data = [
        'nom' => $_POST['nom'] ?? null,
        'prenom' => $_POST['prenom'] ?? null,
        'poste' => $_POST['poste'] ?? null,
        'description' => $_POST['description'] ?? null,
      ];

      if (key_exists('photo', $_FILES) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $data['photo'] = $this->uploadImage($_FILES['photo'], "equipe");
      }

      $data = $this->nettoyerDonnees($data);

      $data['membre_id'] = (int)$_GET['id'];
      try{
        $this->equipeService->modifierMembre($data);

        $_SESSION['succes'] = "Membre modifié";
        header('location: /afficherMembres');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /modifierMembre?id='.$data['membre_id']);
        exit;
      }
    }
    $this->render('pages/admin/modifierMembre');
  }

  public function modifierStatutMembre()
  {
    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
      $statut = (int)$_POST['actif'];
      $membreId = (int)$_GET['id'];
      
      try{
        $this->equipeService->modifierStatutMembre($membreId, $statut);
        if($statut == 0){
          $_SESSION['succes'] = "Membre masqué";
        }else{
          $_SESSION['succes'] = "Membre affiché";
        }
        header('location: /afficherMembres');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /modifierStatutMembre?id='.$membreId);
        exit;
      }
    }
    $this->render('pages/admin/modifierStatutMembre');
  }
}