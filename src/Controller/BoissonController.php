<?php

namespace App\Controller;

use App\Factory\ContainerId;
use App\Service\BoissonService;
use App\Service\UploadService;
use Exception;

class BoissonController extends Controller
{
  private BoissonService $boissonService;
  private UploadService $uploadService;

  public function __construct() {
    parent::__construct();
    $this->boissonService = ContainerId::getBoissonService();
    $this->uploadService = ContainerId::getUploadService();
  }

  public function creerBoisson(): void
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();
  
      $data = [
        'nom_boisson' => $_POST['nom_boisson'],
        'prix_boisson' => $_POST['prix_boisson'],
        'alcool' => $_POST['alcool'],
        'stock_boisson' => (int) $_POST['stock_boisson'],
        'boisson_actif' => isset($_POST['boisson_actif']) ? 1 : 0,
        'description_boisson' => $_POST['description_boisson'],
      ];

      if (key_exists('photo_boisson', $_FILES) && $_FILES['photo_boisson']['error'] === UPLOAD_ERR_OK) {
        $extension = $this->uploadService->validerImage($_FILES['photo_boisson']);
        $data['photo_boisson'] = $this->uploadImage($_FILES['photo_boisson'], "boisson", $extension);
      }
      $data = $this->nettoyerDonnees($data);

      $role = $_SESSION['role_id'];

      try{
        $this->boissonService->creerBoisson($data, $role);
        $_SESSION['succes'] = 'Boisson ajoutée';
        header('location: /boisson');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /boisson');
        exit;
      }

    }else{
      $this->render('pages/employe/creerBoisson', ['titre' => 'creer boisson']);
    }
  }


  public function afficherBoisson(): void
  {
    $boissons = $this->boissonService->afficherBoisson();

    $this->render('pages/employe/boisson', ['boissons' => $boissons, 'titre' => 'boissons']);
  }

  public function afficherBoissonParId(): void
  {
    $boissonId = (int) $_GET['id'];
    $boisson = $this->boissonService->afficherBoissonParId($boissonId);

    $this->render('pages/employe/detailBoisson', ['boisson' => $boisson, 'titre' => 'detail boisson']);
  }

  public function modifierBoisson(): void
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();
  
      $data = [
        'nom_boisson' => $_POST['nom_boisson'] ?? null,
        'prix_boisson' => $_POST['prix_boisson'] ?? null,
        'alcool' => $_POST['alcool'] ?? null,
        'stock_boisson' => $_POST['stock_boisson'] ?? null,
        'boisson_actif' => $_POST['boisson_actif'] ?? null,
        'description_boisson' => $_POST['description_boisson'] ?? null,
      ];

      if (key_exists('photo_boisson', $_FILES) && $_FILES['photo_boisson']['error'] === UPLOAD_ERR_OK) {
        $extension = $this->uploadService->validerImage($_FILES['photo_boisson']);
        $data['photo_boisson'] = $this->uploadImage($_FILES['photo_boisson'], "boisson", $extension);
      }

      $data = $this->nettoyerDonnees($data);

      try{
        $boissonId = (int) $_POST['id'];
        $role = $_SESSION['role_id'];
        
        $this->boissonService->modifierBoisson($boissonId, $data, $role);
        $_SESSION['succes'] = 'Boisson Modifiée';
        header('location: /detailBoisson?id='.$boissonId);
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /modifierBoisson');
        exit;
      }

    }else{
      $boissonId = (int) $_GET['id'];
      $boisson = $this->boissonService->afficherBoissonParId($boissonId);
      $this->render('pages/employe/modifierBoisson', ['boisson' => $boisson, 'titre' => 'modifier boisson']);
    }
  }

  public function modifierStatusBoisson(): void
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();
  
    $statut = (int)$_POST['boisson_actif'];
    $boissonId = (int)$_POST['id'];
    $role = $_SESSION['role_id'];

    try{
      $this->boissonService->modifierStatusBoisson($boissonId, $statut, $role);
      echo json_encode(['succes' => true, 'message' => 'Status modifié', 'statut' => $statut]);

    }catch(Exception $e){
      echo json_encode(['succes' => false, 'message' => $e->getMessage()]);
    }
  }

  public function modifierStockBoisson(): void
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();
  
    $stock = (int)$_POST['stock_boisson'];
    $boissonId = $_POST['id'];
    $role = $_SESSION['role_id'];

    try{
      $this->boissonService->modifierStockBoisson($boissonId, $stock, $role);
      $_SESSION['succes'] = 'Stock modifié';
      echo json_encode(['succes' => true, 'message' => 'Stock modifié', 'stock' => $stock]);
    }catch(Exception $e){
      echo json_encode(['succes' => false, 'message' => $e->getMessage()]);
    }
  }

  public function supprimerBoisson(): void
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('location: /');
        exit;
      }
      $this->checkCsrfToken();
  
    $boissonId = $_POST['id'];
    $role = $_SESSION['role_id'];

    try{
        $this->boissonService->supprimerBoisson($boissonId, $role);
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