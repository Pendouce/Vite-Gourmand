<?php

namespace App\Controller;

use App\Factory\ContainerId;
use App\Service\InfoVgService;
use Exception;

class InfoVgController extends Controller
{
  private InfoVgService $infoVgService;

  public function __construct() {
    parent::__construct();
    $this->infoVgService = ContainerId::getInfoVgService();
  }

  // INFORMATIONS VITE ET GOURMAND
  public function afficherInfosVg()
  {
    $infos = $this->infoVgService->afficherInfosVg();
    $this->render('layouts/footer', ['infos' => $infos]);
  }

  public function modifierInfosVg()
  {
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

      try{
        $this->infoVgService->modifierInfosVg($data);
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
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();
    
      $data['nom_img'] = $_POST['nom_img'] ?? null;

      if (key_exists('chemin', $_FILES) && $_FILES['chemin']['error'] === UPLOAD_ERR_OK) {
        $data['chemin'] = $this->uploadImage($_FILES['chemin'], "imageSite");
      }

      $data = $this->nettoyerDonnees($data);

      try{
        $data['id'] = $_POST['id'];
        $this->infoVgService->modifierImageSite($data);
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