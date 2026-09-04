<?php

namespace App\Controller;

use App\Factory\ContainerId;
use App\Service\EquipeService;
use App\Service\UploadService;
use Exception;

class EquipeController extends Controller
{
  private EquipeService $equipeService;
  private UploadService $uploadService;

  public function __construct() {
    parent::__construct();
    $this->equipeService = ContainerId::getEquipeService();
    $this->uploadService = ContainerId::getUploadService();
  }

  public function creerMembre()
  {
    $this->accesPage([ROLE_ADMIN]);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();
    
      $data = [
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'poste' => $_POST['poste'],
        'description' => $_POST['description'],
      ];

      if (key_exists('photo', $_FILES) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $extension = $this->uploadService->validerImage($_FILES['photo']);
        $data['photo'] = $this->uploadImage($_FILES['photo'], "equipe", $extension);
      }
      $data = $this->nettoyerDonnees($data);

      $role = $_SESSION['role_id'];

      try{
        $this->equipeService->creerMembre($data, $role);
         $_SESSION['succes'] = "Nouveau membre de l'équipe ajouté";
        header('location: /afficherMembres');
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
    $role = $_SESSION['role_id'] ?? null;
    $membres = $this->equipeService->afficherMembres($role);

    $this->render('pages/equipe', ['membres' => $membres]);
  }

  public function modifierMembre()
  {
    $this->accesPage([ROLE_ADMIN]);

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
      $this->checkCsrfToken();
    
      $data = [
        'nom' => $_POST['nom'] ?? null,
        'prenom' => $_POST['prenom'] ?? null,
        'poste' => $_POST['poste'] ?? null,
        'description' => $_POST['description'] ?? null,
      ];

      if (key_exists('photo', $_FILES) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $extension = $this->uploadService->validerImage($_FILES['photo']);
        $data['photo'] = $this->uploadImage($_FILES['photo'], "equipe", $extension);
      }
      $data = $this->nettoyerDonnees($data);

      $data['membre_id'] = (int)$_POST['id'];
      $role = $_SESSION['role_id'];

      try{
        $this->equipeService->modifierMembre($data, $role);

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
    $this->accesPage([ROLE_ADMIN]);

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
      $this->checkCsrfToken();
    
      $statut = (int)$_POST['actif'];
      $membreId = (int)$_POST['id'];
      $role = $_SESSION['role_id'];

      try{
        $this->equipeService->modifierStatutMembre($membreId, $statut, $role);
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

  public function supprimerMembre()
  {
    $this->accesPage([ROLE_ADMIN]);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();
    
    $membreId = (int)$_POST['id'];
    $role = $_SESSION['role_id'];

    try{
      $this->equipeService->supprimerMembre($membreId, $role);
      $_SESSION['succes'] = "Membre supprimé";
      header('location: /afficherMembres');
      exit;
    }catch(Exception $e){
      $_SESSION['erreur'] = $e->getMessage();
      header('location: /modifierMembre?id='.$membreId);
      exit;
    }
  }
}