<?php

namespace App\Controller;

use App\Repository\PlatRepository;
use App\Repository\AllergeneRepository;
use App\Service\PlatService;
use Exception;

class PlatController extends Controller
{
  private PlatService $platService;
  
  public function __construct() {
    parent::__construct();
    $platRepository = new PlatRepository();
    $allergeneRepository = new AllergeneRepository();
    $this->platService = new PlatService($platRepository, $allergeneRepository);
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
      $allergeneId = $_POST['allergene'];
      $this->nettoyerDonnees($data);


      try{
        $platCreer = $this->platService->creerPlat($data);
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
    $plats = $this->platService->afficherPlats();
    $this->render('pages/employe/plat', ['plats' => $plats]);
  }

  public function afficherPlatParType()
  {
    $plats = $this->platService->afficherPlats();
    $this->render('pages/employe/plat', ['plats' => $plats]);
  }

  public function afficherDetailPlat()
  {
    $platId = $_GET['id'];
    //var_dump($platId);
    $plat = $this->platService->afficherParId($platId);
    $this->render('pages/employe/detailPlat', ['plat' => $plat]);
  }

  public function modifierPlat()
  {
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
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
        $data['image_plat'] = $this->uploadImage($_FILES['image_plat']);
      }
      $allergeneId = $_POST['allergene'];
      $data = $this->nettoyerDonnees($data);
      try{
        //$platId= $_GET['id'];
        $platId= $_GET['id'];

        $this->platService->modifierPlat($platId, $data);
        $this->platService->modifierAllergenesDuPlat($platId, $allergeneId);

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

  private function uploadImage(array $file)
  {
    $nomTmpImage = $file ['tmp_name'];
    $image = "/upload/plat/".$file['name'];
    move_uploaded_file($nomTmpImage, APP_ROOT."/public/".$image);
    return $image;
  }
}