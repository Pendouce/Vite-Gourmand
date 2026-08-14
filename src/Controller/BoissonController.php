<?php

namespace App\Controller;

use App\Factory\ContainerId;
use App\Service\BoissonService;
use Exception;

class BoissonController extends Controller
{
  private BoissonService $boissonService;

  public function __construct() {
    parent::__construct();
    $this->boissonService = ContainerId::getBoissonService();
  }

  public function creerBoisson()
  {
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $data = [
        'nom_boisson' => $_POST['nom_boisson'],
        'prix_boisson' => $_POST['prix_boisson'],
        'alcool' => $_POST['alcool'],
        'stock_boisson' => (int) $_POST['stock_boisson'],
        'boisson_actif' => $_POST['boisson_actif'],
      ];

      if (key_exists('photo_boisson', $_FILES) && $_FILES['photo_boisson']['error'] === UPLOAD_ERR_OK) {
        $data['photo_boisson'] = $this->uploadImage($_FILES['photo_boisson'], "boisson");
      }

      $data = $this->nettoyerDonnees($data);

      /* var_dump($data);
      echo '_____________________________
      ______________________';
       */
      try{
        $this->boissonService->creerBoisson($data);
        $_SESSION['succes'] = 'Boisson ajoutée';
        header('location: /boisson');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /boisson');
        exit;
      }

    }else{
      $this->render('pages/employe/creerBoisson');
    }
  }

  public function afficherBoisson()
  {
    $boissons = $this->boissonService->afficherBoisson();

    $this->render('pages/employe/boisson', ['boissons' => $boissons]);
  }

  public function afficherBoissonParId()
  {
    $boissonId = (int) $_GET['id'];
    $boisson = $this->boissonService->afficherBoissonParId($boissonId);

    $this->render('pages/employe/detailBoisson', ['boisson' => $boisson]);
  }
}