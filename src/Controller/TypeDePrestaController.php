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
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $libelle = htmlspecialchars($_POST['libelle']);
      try{
        $this->typeDePrestaService->creerTypeDePresta($libelle);
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
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $libelle = htmlspecialchars($_POST['libelle']);
      $id = (int) $_GET['id'];

      $data = [
        'libelle' => $libelle,
        'type_presta_id' => $id
      ];

      try{
        $this->typeDePrestaService->modifierTypeDePresta($data);
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
    $id = (int) $_GET['id'];
    try{
        $this->typeDePrestaService->supprimerTypeDePresta($id);
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