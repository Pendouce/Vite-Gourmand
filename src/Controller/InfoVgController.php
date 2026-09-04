<?php

namespace App\Controller;

use App\Factory\ContainerId;
use App\Service\InfoVgService;
use App\Service\UploadService;
use Exception;

class InfoVgController extends Controller
{
  private InfoVgService $infoVgService;
  private UploadService $uploadService;

  public function __construct() {
    parent::__construct();
    $this->infoVgService = ContainerId::getInfoVgService();
    $this->uploadService = ContainerId::getUploadService();
  }

  // INFORMATIONS VITE ET GOURMAND
  public function afficherInfosVg()
  {
    $infos = $this->infoVgService->afficherInfosVg();
    $this->render('layouts/footer', ['infos' => $infos]);
  }

  public function modifierInfosVg()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();
    
      $data = [
        'adresse' => $_POST['adresse'] ?? null, 
        'telephone' => $_POST['telephone'] ?? null, 
        'email' => $_POST['email'] ?? null, 
        'horaires_semaine' => $_POST['horaires_semaine'] ?? null, 
        'horaires_weekend' => $_POST['horaires_weekend'] ?? null
      ];

      $data = $this->nettoyerDonnees($data);

      $role = $_SESSION['role_id'];

      try{
        $this->infoVgService->modifierInfosVg($data, $role);
         $_SESSION['succes'] = "Informations modifiées";
        header('location: /');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /modifierInfosVg');
        exit;
      }
    }else{
      $this->render('pages/employe/modifierInfosVg');
    }
  }

  // IMAGES DU SITE
  public function afficherImagesSite()
  {
    $images = $this->infoVgService->afficherImagesSite();
    $this->render('pages/imagesSite', ['images' => $images]);
  }

  public function modifierImageSite()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();
    
      $data['nom_img'] = $_POST['nom_img'] ?? null;

      if (key_exists('chemin', $_FILES) && $_FILES['chemin']['error'] === UPLOAD_ERR_OK) {
        $extension = $this->uploadService->validerImage($_FILES['chemin']);
        $data['chemin'] = $this->uploadImage($_FILES['chemin'], "imageSite", $extension);
      }

      $data = $this->nettoyerDonnees($data);
      
      $data['id'] = $_POST['id'];
      $role = $_SESSION['role_id'];

      try{
        $this->infoVgService->modifierImageSite($data, $role);
         $_SESSION['succes'] = "Image modifié";
        header('location: /imagesSite');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /modifierImageSite?id='.$data['id']);
        exit;
      }
    }else{
      $this->render('pages/employe/modifierImageSite');
    }
  }

}