<?php

namespace App\Controller;

use App\Repository\TypeDePrestaRepository;
use App\Service\TypeDePrestaService;
use Exception;

class TypeDePrestaController extends Controller
{
  private TypeDePrestaService $typeDePrestaService;

  public function __construct()
  {
    parent::__construct();
    $typeDePrestaRepository = new TypeDePrestaRepository();
    $this->typeDePrestaService = new TypeDePrestaService($typeDePrestaRepository);
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
}