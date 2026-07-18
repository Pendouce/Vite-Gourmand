<?php

namespace App\Controller;

use App\Repository\TypeDePlatRepository;
use App\Service\TypeDePlatService;
use Exception;

class TypeDePlatController extends Controller
{
  private TypeDePlatService $typeDePlatService;

  public function __construct() {
    parent::__construct();
    $typeDePlatRepository = new TypeDePlatRepository();
    $this->typeDePlatService = new TypeDePlatService($typeDePlatRepository);
  }

  public function creerTypeDePlat()
  {
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $libelle = htmlspecialchars($_POST['libelle']);
      try{
        $this->typeDePlatService->creerTypeDePlat($libelle);
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
    //var_dump($typeDePlat);
    $this->render('pages/employe/plat', ['typeDePlat' => $typeDePlat]);
  }
}