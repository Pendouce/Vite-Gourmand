<?php

namespace App\Controller;

use App\Repository\AllergeneRepository;
use App\Repository\EvenementRepository;
use App\Repository\MenuRepository;
use App\Repository\PlatRepository;
use App\Repository\RegimeRepository;
use App\Repository\ThemeRepository;
use App\Service\MenuService;
use App\Service\PlatService;
use Exception;

class MenuController extends Controller
{
  private MenuService $menuService;

  public function __construct() {
    parent::__construct();
    $menuRepository = new MenuRepository();
    $regimeRepository = new RegimeRepository();
    $evenementRepository = new EvenementRepository();
    $themeRepository = new ThemeRepository();
    $platRepository = new PlatRepository();
    $allergeneRepository = new AllergeneRepository();
    $platService = new PlatService($platRepository, $allergeneRepository);
    $this->menuService = new MenuService($menuRepository, $regimeRepository, $evenementRepository, $themeRepository, $platRepository, $platService);
  }

  public function creerMenu()
  {
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $data = [
        'titre' => $_POST['titre'],
        'prix_personne' => $_POST['prix_personne'],
        'nombre_personne_min' => $_POST['nombre_personne_min'],
        'conditions' => $_POST['conditions'],
        'stock_dispo' => $_GET['stock_dispo'],
        'menu_actif' => $_POST['menu_actif'],
      ];
      //$data['stock_dispo'] = $_GET['stock_dispo'];
      $data = $this->nettoyerDonnees($data);

      try{
        $platId = $_POST['plat'];
        //$allergeneId = $_POST['allergene'];
        $evenementId = $_POST['evenement'];
        $themeId = $_POST['theme'];
        $regimeId = $_POST['regime'];
        $menuCreer = $this->menuService->creerMenu($data);
        $menuId = $menuCreer->getMenuId();
        //var_dump($menuId);
        $this->menuService->ajouterPlatAuMenu($menuId, $platId);
        //$this->menuService->ajouterAllergeneAuplat($platId, $allergeneId);
        $this->menuService->ajouterEvenementAuMenu($menuId, $evenementId);
        $this->menuService->ajouterThemeAuMenu($menuId, $themeId);
        $this->menuService->ajouterRegimeAuMenu($menuId, $regimeId);

        $_SESSION['succes'] = "Menu ajouté";
        header('location: /menu');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /menu');
        exit;
      }


    }else{
      $this->render('pages/employe/creerMenu');
    }
  }

  public function afficherMenus()
  {
    $menus = $this->menuService->afficherMenus();
    $this->render('pages/employe/menu', ['menus' => $menus]);
  }
}