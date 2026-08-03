<?php

namespace App\Controller;

use App\Repository\PrestationRepository;
use App\Repository\TypeDePrestaRepository;
use App\Service\PrestationService;
use Exception;


class PrestationController extends Controller
{
  private PrestationService $prestationService;

  public function __construct()
  {
    parent::__construct();
    $prestationRepository = new PrestationRepository();
    $typePrestaRepository = new TypeDePrestaRepository();
    $this->prestationService = new PrestationService($prestationRepository, $typePrestaRepository);
  }

  public function creerPrestation()
  {
    // GERER L'IMAGE

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $data = [
        'nom_presta' => $_POST['nom_presta'],
        'prix_presta' => $_POST['prix_presta'],
        'description_presta' => $_POST['description_presta'],
        'necessite_retour' => $_POST['necessite_retour'],
        'prestation_actif' => $_POST['prestation_actif'],
        'type_presta_id' => (int) $_POST['type_presta_id']
      ];

      if (key_exists('img_presta', $_FILES) && $_FILES['img_presta']['error'] === UPLOAD_ERR_OK) {
        $data['img_presta'] = $this->uploadImage($_FILES['img_presta'], "prestation");
      }

      $data = $this->nettoyerDonnees($data);

      try{
        //var_dump($data);
        $this->prestationService->creerPrestation($data);

        $_SESSION['succes'] = "Prestation ajouté";
        header('location: /prestations');
        exit;

      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /creerPrestation');
        exit;
      }

    }else{
      $this->render('pages/employe/creerPrestation');
    }
  }

  public function afficherPrestation()
  {
    $prestations = $this->prestationService->afficherPrestation();
    $this->render('pages/employe/prestations', ['prestations' => $prestations]);
  }

  public function afficherPrestationParId()
  {
    $id = (int) $_GET['id'];

    $prestation = $this->prestationService->afficherPrestationParId($id);
    $this->render('pages/employe/detailPresta', ['prestation' => $prestation]);
  }

  public function modifierPrestation()
  {
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $data = [
        'nom_presta' => $_POST['nom_presta'] ?? null,
        'prix_presta' => $_POST['prix_presta'] ?? null,
        'description_presta' => $_POST['description_presta'] ?? null,
        'necessite_retour' => $_POST['necessite_retour'] ?? null,
        'prestation_actif' => $_POST['prestation_actif'] ?? null,
        'type_presta_id' => (int) $_POST['type_presta_id'] ?? null
      ];

      if (key_exists('img_presta', $_FILES) && $_FILES['img_presta']['error'] === UPLOAD_ERR_OK) {
        $data['img_presta'] = $this->uploadImage($_FILES['img_presta'], "prestation");
      }

      $data = $this->nettoyerDonnees($data);

      $prestaId = (int) $_GET['id'];
      try{
        $this->prestationService->modifierPrestation($prestaId, $data);

        $_SESSION['succes'] = "Prestation modifé";
        header('location: /prestations');
        exit;

      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /modifierPrestation');
        exit;
      }

    }else{
      $this->render('pages/employe/modifierPrestation');
    }
  }

  public function modifierStatusPrestation()
  {
    $prestaId = (int) $_GET['id'];
    //var_dump($prestaId);
    $status = $_POST['prestation_actif'];

    try{
        $this->prestationService->modifierStatusPrestation($prestaId, $status);

        $_SESSION['succes'] = "Status modifé";
        header('location: /detailPrestation?id='.$prestaId);
        exit;

      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /detailPrestation?id='.$prestaId);
        exit;
      }
  }

  public function supprimerPrestation()
  {
    $prestaId = (int) $_GET['id'];

    try{
        $this->prestationService->supprimerPrestation($prestaId);

        $_SESSION['succes'] = "Prestation supprimeée";
        header('location: /prestations');
        exit;

      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /detailPrestation?id='.$prestaId);
        exit;
      }
  }
}
