<?php

namespace App\Controller;

use App\Factory\ContainerId;
use App\Service\TypeDePrestaService;
use Exception;

class TypeDePrestaController extends Controller
{
  private TypeDePrestaService $typeDePrestaService;

  public function __construct()
  {
    parent::__construct();
    $this->typeDePrestaService = ContainerId::getTypeDePrestaService();
  }

  public function creerTypeDePresta()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();

      $libelle = htmlspecialchars($_POST['libelle']);
      $role = $_SESSION['role_id'];
      try{
        $this->typeDePrestaService->creerTypeDePresta($libelle, $role);
        $_SESSION['succes'] = "Type de prestation ajouté";
        header('location: /prestations');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /prestations');
        exit;
      }
    }else{
      $this->render('pages/employe/prestations');
    }
  }

  public function afficherTypeDePresta()
  {
    $typeDePresta = $this->typeDePrestaService->afficherTypeDePresta();
    $this->render('pages/employe/prestations', ['typeDePresta'=> $typeDePresta]);
  }

  public function modifierTypeDePresta()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();
      $libelle = htmlspecialchars($_POST['libelle']);
      $id = (int) $_POST['id'];

      $data = [
        'libelle' => $libelle,
        'type_presta_id' => $id
      ];
      $role = $_SESSION['role_id'];

      try{
        $this->typeDePrestaService->modifierTypeDePresta($data, $role);
        $_SESSION['succes'] = "Type de prestation modifié";
        header('location: /prestations');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /prestations');
        exit;
      }
    }else{
      $this->render('pages/employe/prestations');
    }
  }

  public function supprimerTypeDePresta()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();

    $id = (int) $_POST['id'];
    $role = $_SESSION['role_id'];
    try{
        $this->typeDePrestaService->supprimerTypeDePresta($id, $role);
        $_SESSION['succes'] = "Type de prestation supprimé";
        header('location: /prestations');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /prestations');
        exit;
      }
  }
}