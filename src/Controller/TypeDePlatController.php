<?php

namespace App\Controller;

use App\Factory\ContainerId;
use App\Service\TypeDePlatService;
use Exception;

class TypeDePlatController extends Controller
{
  private TypeDePlatService $typeDePlatService;

  public function __construct() {
    parent::__construct();
    $this->typeDePlatService = ContainerId::getTypeDePlatService();
  }

  public function creerTypeDePlat()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();

      $libelle = htmlspecialchars($_POST['libelle']);
      $role = $_SESSION['role_id'];

      try{
        $this->typeDePlatService->creerTypeDePlat($libelle, $role);
        $_SESSION['succes'] = "Type de plat ajouté";
        header('location: /plats');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /plats');
        exit;
      }
    }else{
      $this->render('pages/employe/plat');
    }
  }

  public function afficherTypeDePlat()
  {
    $typeDePlat = $this->typeDePlatService->afficheTypeDePlat();
    $this->render('pages/employe/plat', ['typeDePlat' => $typeDePlat]);
  }

  public function modifierTypeDePlat()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();

      $libelle = htmlspecialchars($_POST['libelle']);
      $id = $_POST['type_id'];
      $role = $_SESSION['role_id'];

      try{
        $this->typeDePlatService->modifieTypeDePlat($libelle, $id, $role);
        $_SESSION['succes'] = 'Type de plat modifié';
        header('location: /plats');
        exit;

      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /modifierTypeDePlat');
        exit;
      }
    }else{
      $this->render('pages/employe/modifierTypeDePlat');
    }
  }

  public function supprimerTypeDePlat()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();

    $id = $_POST['type_id'];
    $role = $_SESSION['role_id'];

    try{
      $this->typeDePlatService->supprimeTypeDePlat($id, $role);
      $_SESSION['succes'] = 'Type de plat supprimé';
      header('location: /plats');
      exit;

    }catch(Exception $e){
      $_SESSION['erreur'] = $e->getMessage();
      header('location: /plats');
      exit;
    }
  }

}