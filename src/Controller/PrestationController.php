<?php

namespace App\Controller;

use App\Factory\ContainerId;
use App\Service\PrestationService;
use App\Service\TypeDePrestaService;
use App\Service\UploadService;
use Exception;


class PrestationController extends Controller
{
  private PrestationService $prestationService;
  private TypeDePrestaService $typeDePrestaService;
  private UploadService $uploadService;

  public function __construct()
  {
    parent::__construct();
    $this->prestationService = ContainerId::getPrestationService();
    $this->typeDePrestaService = ContainerId::getTypeDePrestaService();
    $this->uploadService = ContainerId::getUploadService();
  }

  public function creerPrestation()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();

      $data = [
        'nom_presta' => $_POST['nom_presta'],
        'prix_presta' => $_POST['prix_presta'],
        'description_presta' => $_POST['description_presta'],
        'necessite_retour' => !empty($_POST['necessite_retour']) ? 1 : 0,
        'prestation_actif' => isset($_POST['prestation_actif']) ? 1 : 0,
        'type_presta_id' => (int) $_POST['type_presta_id'],
        'contenu_presta' => $_POST['contenu_presta'],
        'seuil' => $_POST['seuil'],
        'supplement' => $_POST['supplement']
      ];

      if (key_exists('img_presta', $_FILES) && $_FILES['img_presta']['error'] === UPLOAD_ERR_OK) {
        $extension = $this->uploadService->validerImage($_FILES['img_presta']);
        $data['img_presta'] = $this->uploadImage($_FILES['img_presta'], "prestation", $extension);
      }

      $data = $this->formaterDonnees($data);
      $role = $_SESSION['role_id'];

      try{
        $this->prestationService->creerPrestation($data, $role);

        $_SESSION['succes'] = "Prestation ajouté";
        header('location: /prestations');
        exit;

      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /creerPrestation');
        exit;
      }

    }else{
      $role = $_SESSION['role_id'];
      $prestations = $this->prestationService->afficherPrestation($role);
      $typeDePresta = $this->typeDePrestaService->afficherTypeDePresta();
      $prestationParType = $this->prestationService->afficherPrestationParType($role);

    $this->render('pages/employe/creerPrestation', [
      'prestations' => $prestations, 'typeDePresta' => $typeDePresta, 'prestationParType' => $prestationParType, 
      'role' => $role, 'titre' => 'creer prestation']);
      //$this->render('pages/employe/creerPrestation', ['titre' => 'creer prestation']);
    }
  }

  public function afficherPrestation()
  {
    $role = $_SESSION['role_id'] ?? null;

    $prestations = $this->prestationService->afficherPrestation($role);
    $prestationParType = $this->prestationService->afficherPrestationParType($role);
    $typeDePresta = $this->typeDePrestaService->afficherTypeDePresta();
    

    $this->render('pages/employe/prestations', [
      'prestations' => $prestations, 'prestationParType' => $prestationParType, 
      'typeDePresta' => $typeDePresta,
      'role' => $role, 'titre' => 'nos prestations']);
  }

  public function afficherPrestationParId()
  {
    $id = (int) $_GET['id'];
    $role = $_SESSION['role_id'] ?? null;

    $prestation = $this->prestationService->afficherPrestationParId($id);
    $this->render('pages/employe/detailPresta', ['prestation' => $prestation, 'role' => $role, 'titre' =>'detail prestation']);
  }

  public function modifierPrestation()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();

      $data = [
        'nom_presta' => $_POST['nom_presta'] ?? null,
        'prix_presta' => $_POST['prix_presta'] ?? null,
        'description_presta' => $_POST['description_presta'] ?? null,
        'necessite_retour' => !empty($_POST['necessite_retour']) ? 1 : 0,
        'prestation_actif' => isset($_POST['prestation_actif']) ? 1 : 0,
        'type_presta_id' => !empty($_POST['type_presta_id']) ? (int) $_POST['type_presta_id'] : null,
        'contenu_presta' => $_POST['contenu_presta'] ?? null,
        'seuil' => $_POST['seuil'] ?? null,
        'supplement' => $_POST['supplement'] ?? null
      ];

      if (key_exists('img_presta', $_FILES) && $_FILES['img_presta']['error'] === UPLOAD_ERR_OK) {
        $extension = $this->uploadService->validerImage($_FILES['img_presta']);
        $data['img_presta'] = $this->uploadImage($_FILES['img_presta'], "prestation", $extension);
      }

      $data = $this->formaterDonnees($data);

      $prestaId = (int) $_POST['id'];
      $role = $_SESSION['role_id'];

      try{
        $this->prestationService->modifierPrestation($prestaId, $data, $role);

        $_SESSION['succes'] = "Prestation modifé";
        header('location: /detailPrestation?id='.$prestaId);
        exit;

      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /modifierPrestation');
        exit;
      }

    }else{
      $role = $_SESSION['role_id'];

      $prestation = $this->prestationService->afficherPrestationParId($_GET['id']);
      $typeDePresta = $this->typeDePrestaService->afficherTypeDePresta();
      $prestationParType = $this->prestationService->afficherPrestationParType($role);
      

    $this->render('pages/employe/modifierPrestation', [
      'prestation' => $prestation, 'typeDePresta' => $typeDePresta, 'prestationParType' => $prestationParType, 
      'role' => $role, 'titre' => 'modifier prestation']);
      //$this->render('pages/employe/modifierPrestation', ['titre' => 'modifier prestation']);
    }
  }

  public function modifierStatusPrestation()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();

    $prestaId = (int) $_POST['id'];
    $statut = $_POST['prestation_actif'];
    $role = $_SESSION['role_id'];

    try{
      $this->prestationService->modifierStatusPrestation($prestaId, $statut, $role);
      echo json_encode(['succes' => true, 'message' => 'Statut modifié', 'statut' => $statut]);
    }catch(Exception $e){
      echo json_encode(['succes' => false, 'message' => $e->getMessage()]);
    }
  }

  public function supprimerPrestation()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();

    $prestaId = (int) $_POST['id'];
    $role = $_SESSION['role_id'];

    try{
        $this->prestationService->supprimerPrestation($prestaId, $role);

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
