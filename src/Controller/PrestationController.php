<?php

namespace App\Controller;

use App\Factory\ContainerId;
use App\Service\PrestationService;
use App\Service\UploadService;
use Exception;


class PrestationController extends Controller
{
  private PrestationService $prestationService;
  private UploadService $uploadService;

  public function __construct()
  {
    parent::__construct();
    $this->prestationService = ContainerId::getPrestationService();
    $this->uploadService = ContainerId::getUploadService();
  }

  public function creerPrestation()
  {
    // GERER L'IMAGE

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();

      $data = [
        'nom_presta' => $_POST['nom_presta'],
        'prix_presta' => $_POST['prix_presta'],
        'description_presta' => $_POST['description_presta'],
        'necessite_retour' => $_POST['necessite_retour'],
        'prestation_actif' => $_POST['prestation_actif'],
        'type_presta_id' => (int) $_POST['type_presta_id'],
        'contenu_presta' => $_POST['contenu_presta']
      ];

      if (key_exists('img_presta', $_FILES) && $_FILES['img_presta']['error'] === UPLOAD_ERR_OK) {
        $extension = $this->uploadService->validerImage($_FILES['img_presta']);
        $data['img_presta'] = $this->uploadImage($_FILES['img_presta'], "prestation", $extension);
      }

      $data = $this->nettoyerDonnees($data);
      //$data['contenu_presta'] = $_POST['contenu_presta'];

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
      $this->checkCsrfToken();

      $data = [
        'nom_presta' => $_POST['nom_presta'] ?? null,
        'prix_presta' => $_POST['prix_presta'] ?? null,
        'description_presta' => $_POST['description_presta'] ?? null,
        'necessite_retour' => $_POST['necessite_retour'] ?? null,
        'prestation_actif' => $_POST['prestation_actif'] ?? null,
        'type_presta_id' => !empty($_POST['type_presta_id']) ? (int) $_POST['type_presta_id'] : null,
        'contenu_presta' => $_POST['contenu_presta'] ?? null
      ];

      if (key_exists('img_presta', $_FILES) && $_FILES['img_presta']['error'] === UPLOAD_ERR_OK) {
        $extension = $this->uploadService->validerImage($_FILES['img_presta']);
        $data['img_presta'] = $this->uploadImage($_FILES['img_presta'], "prestation", $extension);
      }

      $data = $this->nettoyerDonnees($data);

      $prestaId = (int) $_POST['id'];
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
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();

    $prestaId = (int) $_POST['id'];
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
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();

    $prestaId = (int) $_POST['id'];

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
