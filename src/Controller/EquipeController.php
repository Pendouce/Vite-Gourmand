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
}