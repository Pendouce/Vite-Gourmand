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
      $this->checkCsrfToken();
  
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

  public function modifierBoisson()
  {
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();
  
      $data = [
        'nom_boisson' => $_POST['nom_boisson'] ?? null,
        'prix_boisson' => $_POST['prix_boisson'] ?? null,
        'alcool' => $_POST['alcool'] ?? null,
        'stock_boisson' => $_POST['stock_boisson'] ?? null,
        'boisson_actif' => $_POST['boisson_actif'] ?? null,
      ];

      if (key_exists('photo_boisson', $_FILES) && $_FILES['photo_boisson']['error'] === UPLOAD_ERR_OK) {
        $data['photo_boisson'] = $this->uploadImage($_FILES['photo_boisson'], "boisson");
      }

      $data = $this->nettoyerDonnees($data);

      try{
        $boissonId = (int) $_POST['id'];
        
        $this->boissonService->modifierBoisson($boissonId, $data);
        $_SESSION['succes'] = 'Boisson Modifiée';
        header('location: /detailBoisson?id='.$boissonId);
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /modifierBoisson');
        exit;
      }

    }else{
      $this->render('pages/employe/modifierBoisson');
    }
  }

  public function modifierStatusBoisson()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();
  
    $status = (int)$_POST['boisson_actif'];
    $boissonId = (int)$_POST['id'];

    try{
      $this->boissonService->modifierStatusBoisson($boissonId, $status);
      $_SESSION['succes'] = 'Status modifié';
      header('location: /detailBoisson?id='.$boissonId);
      exit;
    }catch(Exception $e){
      $_SESSION['erreur'] = $e->getMessage();
      header('location: /detailBoisson?id='.$boissonId);
      exit;
    }
  }

  public function modifierStockBoisson()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();
  
    $stock = (int)$_POST['stock_boisson'];
    $boissonId = $_POST['id'];

    try{
      $this->boissonService->modifierStockBoisson($boissonId, $stock);
      $_SESSION['succes'] = 'Stock modifié';
      header('location: /detailBoisson?id='.$boissonId);
      exit;
    }catch(Exception $e){
      $_SESSION['erreur'] = $e->getMessage();
      header('location: /detailBoisson?id='.$boissonId);
      exit;
    }
  }

  public function supprimerBoisson()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('location: /');
        exit;
      }
      $this->checkCsrfToken();
  
    $boissonId = $_POST['id'];
    try{
        $this->boissonService->supprimerBoisson($boissonId);
        $_SESSION['succes'] = 'Boisson supprimée';
        header('location: /boisson');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /detailBoisson?id='.$boissonId);
        exit;
      }
  }
}