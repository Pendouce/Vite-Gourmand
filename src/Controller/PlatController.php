<?php

namespace App\Controller;

use App\Repository\PlatRepository;
use App\Service\PlatService;
use Exception;

class PlatController extends Controller
{
  private PlatService $platService;
  
  public function __construct() {
    parent::__construct();
    $platRepository = new PlatRepository();
    $this->platService = new PlatService($platRepository);
  }

  public function creerPlat()
  {
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

      $nomTmpImage = $_FILES['image_plat']['tmp_name'];
      $image = "/upload/plat/".$_FILES['image_plat']['name'];
      move_uploaded_file($nomTmpImage, APP_ROOT."/public/".$image);

      $data = [
        'titre' => $_POST['titre'],
        'image_plat' => $image,
        'description_plat' => $_POST['description_plat'],
        'prix_personne' => $_POST['prix_personne'],
        'stock_plat' => $_POST['stock_plat'],
        'plat_actif' => $_POST['plat_actif'],
        'type_id' => $_POST['type_id'],
      ];
      $this->nettoyerDonnees($data);

      try{
        $this->platService->creerPlat($data);
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
    $plats = $this->platService->afficherPlat();
    $this->render('pages/employe/plat', ['plats' => $plats]);
  }
}