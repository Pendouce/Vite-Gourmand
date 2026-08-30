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

  public function afficherInfosVg()
  {
    $infos = $this->infoVgService->afficherInfosVg();
    $this->render('layouts/footer', ['infos' => $infos]);
  }

  public function modifierInfosVg()
  {
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
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
}