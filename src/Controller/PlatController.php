<?php

namespace App\Controller;

use App\Service\PlatService;
use App\Factory\ContainerId;
use App\Service\TypeDePlatService;
use App\Service\UploadService;
use Exception;

class PlatController extends Controller
{
  private PlatService $platService;
  private TypeDePlatService $typeDePlatService;
  private UploadService $uploadService;
  
  public function __construct() {
    parent::__construct();
    $this->platService = ContainerId::getPlatService();
    $this->typeDePlatService = ContainerId::getTypeDePlatService();
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
        // Si checkbox non cochée valeur de plat_actif = 0
        'plat_actif' => isset($_POST['plat_actif']) ? 1 : 0,
        'type_id' => $_POST['type_id'],
      ];

      try{
      if (key_exists('image_plat', $_FILES) && $_FILES['image_plat']['error'] === UPLOAD_ERR_OK) {
        $extension = $this->uploadService->validerImage($_FILES['image_plat']);
        $data['image_plat'] = $this->uploadImage($_FILES['image_plat'], "plat", $extension);
      }
      $allergeneId = $_POST['allergene'];
      $data = $this->nettoyerDonnees($data);
      $allergeneId = $this->nettoyerDonnees($allergeneId);
      
      $this->nettoyerDonnees($data);
      $role = $_SESSION['role_id'];

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
      $typeDePlat = $this->typeDePlatService->afficheTypeDePlat();
      $allergenes = $this->platService->afficherAllergenes();
      $this->render('pages/employe/creerPlat', ['typeDePlat' => $typeDePlat, 'allergenes' => $allergenes, 'titre' => 'creer un plat']);
    }
  }

  public function afficherPlat()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    $role = $_SESSION['role_id'];
    $plats = $this->platService->afficherPlats($role);
    $platsParType = $this->platService->afficherPlatsParType($role);

    $this->render('pages/employe/plat', ['plats' => $plats, 'platsParType' => $platsParType, 'titre' => 'plats']);
  }

 /*  public function afficherPlatParType()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    $role = $_SESSION['role_id'];

    $platsParType = $this->platService->afficherPlatsParType($role);

    $this->render('pages/employe/plat', ['platsParType' => $platsParType, 'titre' => 'plats']);
  } */

  public function afficherDetailPlat()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    $platId = $_GET['id'];
    $role = $_SESSION['role_id'];

    //var_dump($platId);
    $plat = $this->platService->afficherParId($platId, $role);
    $this->render('pages/employe/detailPlat', ['plat' => $plat, 'titre' => 'details plat']);
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

        $plat = $this->platService->afficherParId($platId, $role);
        $allergenes = $this->platService->afficherAllergenes();

        $_SESSION['succes'] = "Plat modifié";
        header('location: /detailPlat?id='.$platId);
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /detailPlat?id='.$platId);
        exit;
      }

    }else{
      $platId = $_GET['id'];
      $role = $_SESSION['role_id'];
      $plat = $this->platService->afficherParId($platId, $role);
      $typeDePlat = $this->typeDePlatService->afficheTypeDePlat();
      $allergenes = $this->platService->afficherAllergenes();
      // Je recupere les ids (uniquement les ids) des allergenes du plat et les stock dans $allergenesDuPlatIds
      $allergenesDuPlatIds = array_map(fn($a) => $a->getAllergeneId(), $plat->getAllergenes());

      $this->render('pages/employe/modifierPlat', ['typeDePlat' => $typeDePlat, 'plat' => $plat, 'allergenes' => $allergenes, 'allergenesDuPlatIds' => $allergenesDuPlatIds, 'titre' => 'modifier plat']);
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
      // Envoie du json pour pouvoir comuniquer avec js
      echo json_encode(['succes' => true, 'message' => 'Statut modifié', 'statut' => $statut]);
    }catch(Exception $e){
      echo json_encode(['succes' => false, 'message' => $e->getMessage()]);
    }
  }
  /* public function modifierStatusPlat()
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
  } */

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