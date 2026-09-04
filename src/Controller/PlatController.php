<?php

namespace App\Controller;

use App\Service\PlatService;
use App\Factory\ContainerId;
use App\Service\UploadService;
use Exception;

class PlatController extends Controller
{
  private PlatService $platService;
  private UploadService $uploadService;
  
  public function __construct() {
    parent::__construct();
    $this->platService = ContainerId::getPlatService();
    $this->uploadService = ContainerId::getUploadService();
  }

  public function creerPlat()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();

      $data = [
        'titre' => $_POST['titre'],
        'description_plat' => $_POST['description_plat'],
        'prix_personne' => $_POST['prix_personne'],
        'stock_plat' => $_POST['stock_plat'],
        'plat_actif' => $_POST['plat_actif'],
        'type_id' => $_POST['type_id'],
      ];
      if (key_exists('image_plat', $_FILES) && $_FILES['image_plat']['error'] === UPLOAD_ERR_OK) {
        $extension = $this->uploadService->validerImage($_FILES['image_plat']);
        $data['image_plat'] = $this->uploadImage($_FILES['image_plat'], "plat", $extension);
      }
      $allergeneId = $_POST['allergene'];
      $this->nettoyerDonnees($data);
      $role = $_SESSION['role_id'];

      try{
        $platCreer = $this->platService->creerPlat($data, $role);
        $platId = $platCreer->getPlatId();
        $this->platService->ajouterAllergeneAuplat($platId, $allergeneId);
        $_SESSION['succes'] = "Plat ajouté";
        header('location: /plats');
        exit;

      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /plats');
        exit;
      }

    }else{
      $this->render('pages/employe/creerPlat');
    }
  }

  public function afficherPlat()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    $role = $_SESSION['role_id'];
    $plats = $this->platService->afficherPlats($role);

    $this->render('pages/employe/plat', ['plats' => $plats]);
  }

  public function afficherPlatParType()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    $role = $_SESSION['role_id'];

    $plats = $this->platService->afficherPlats($role);
    $this->render('pages/employe/plat', ['plats' => $plats]);
  }

  public function afficherDetailPlat()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    $platId = $_GET['id'];
    $role = $_SESSION['role_id'];

    //var_dump($platId);
    $plat = $this->platService->afficherParId($platId, $role);
    $this->render('pages/employe/detailPlat', ['plat' => $plat]);
  }

  public function modifierPlat()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();
  
      $data = [
        'titre' => $_POST['titre'] ?? null,
        'description_plat' => $_POST['description_plat'] ?? null,
        'prix_personne' => $_POST['prix_personne'] ?? null,
        'stock_plat' => $_POST['stock_plat'] ?? null,
        'type_id' => $_POST['type_id'] ?? null,
        'plat_actif' => $_POST['plat_actif'] ?? null,
        //'libelle' => $_POST['libelle'] ?? null,
      ];
      if (key_exists('image_plat', $_FILES) && $_FILES['image_plat']['error'] === UPLOAD_ERR_OK) {
        $extension = $this->uploadService->validerImage($_FILES['image_plat']);
        $data['image_plat'] = $this->uploadImage($_FILES['image_plat'], "plat", $extension);
      }
      $allergeneId = $_POST['allergene'];
      //var_dump($allergeneId);
      $data = $this->nettoyerDonnees($data);
      try{
        $platId = $_POST['id'];
        $role = $_SESSION['role_id'];

        $this->platService->modifierPlat($platId, $data, $role);
        $this->platService->modifierAllergenesDuPlat($platId, $allergeneId, $role);

        $_SESSION['succes'] = "Plat modifié";
        header('location: /detailPlat?id='.$platId);
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /detailPlat?id='.$platId);
        exit;
      }

    }else{
      $this->render('pages/employe/modifierPlat');
    }
  }

  public function modifierStatusPlat()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();

    $statut = (int) $_POST['plat_actif'];
    $platId = $_POST['id'];
    $role = $_SESSION['role_id'];

    try{
      $this->platService->modifierStatusPlat($platId, $statut, $role);
      $_SESSION['succes'] = "Statut modifié";
      header('location: /detailPlat?id='.$platId);
      exit;
    }catch(Exception $e){
      $_SESSION['erreur'] = $e->getMessage();
      header('location: /detailPlat?id='.$platId);
      exit;
    }
  }

  public function modifierStockPlat()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();

    $stock = (int) $_POST['stock_plat'];
    $platId = $_POST['id'];
    $role = $_SESSION['role_id'];

    try{
      $this->platService->modifierStockPlat($platId, $stock, $role);
      $_SESSION['succes'] = "Stock modifié";
      header('location: /detailPlat?id='.$platId);
      exit;
    }catch(Exception $e){
      $_SESSION['erreur'] = $e->getMessage();
      header('location: /detailPlat?id='.$platId);
      exit;
    }
  }

  public function supprimerPlat()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();

    $platId = $_POST['id'];
    $role = $_SESSION['role_id'];

    try{
      $this->platService->supprimerPlat($platId, $role);
      $_SESSION['succes'] = "Le plat a bien ete supprimé";
      header('location: /plats');
      exit;
    }catch(Exception $e){
      $_SESSION['erreur'] = $e->getMessage();
      header('location: /plats');
      exit;
    }
  }

}