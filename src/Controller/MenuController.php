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

  public function afficherDetailMenu()
  {
    $menuId = $_GET['id'];
    $menu = $this->menuService->afficherMenuParId($menuId);
    $this->render('pages/employe/detailMenu', ['menu' => $menu]);
  }

  public function afficherMenuFiltre()
  {
    $menuFiltre = [];

    if(isset($_GET['evenement_id'])){
      $menuFiltre['evenement_id'] = $_GET['evenement_id'];
    }
    
    if(isset($_GET['theme_id'])){
      $menuFiltre['theme_id'] = $_GET['theme_id'];
    }

    if(isset($_GET['regime_id'])){
      $menuFiltre['regime_id'] = $_GET['regime_id'];
    }

    if(isset($_GET['prix_personne'])){
      $menuFiltre['prix_personne'] =(float) $_GET['prix_personne'];
    }

    if(isset($_GET['nombre_personne_min'])){
      $menuFiltre['nombre_personne_min'] = (int) $_GET['nombre_personne_min'];
    }

    $menus = $this->menuService->afficherMenuFiltre($menuFiltre);
    $this->render('pages/client/menuFiltre', ['menus' => $menus]);
  }

  public function modifierMenu()
  {
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $data = [
        'titre' => $_POST['titre'] ?? null,
        'prix_personne' => $_POST['prix_personne'] ?? null,
        'nombre_personne_min' => $_POST['nombre_personne_min'] ?? null,
        'conditions' => $_POST['conditions'] ?? null,
        'menu_actif' => $_POST['menu_actif'] ?? null,
      ];

      $data = $this->nettoyerDonnees($data);

      try{
        $menuId = (int) $_GET['id'];
        $platId = $_POST['plat'] ?? [];
        //$allergeneId = $_POST['allergene'];
        $evenementId = $_POST['evenement'] ?? [];
        $themeId = $_POST['theme'] ?? [];
        $regimeId = $_POST['regime'] ?? [];
        //var_dump($menuId);
        $this->menuService->modifierPlatsDuMenu($menuId, $platId);
        //$this->menuService->ajouterAllergeneAuplat($platId, $allergeneId);
        $this->menuService->modifierEvenementsDuMenu($menuId, $evenementId);
        $this->menuService->modifierThemesDuMenu($menuId, $themeId);
        $this->menuService->modifierRegimesDuMenu($menuId, $regimeId);
        $this->menuService->modifierMenu($menuId, $data);

        $_SESSION['succes'] = "Menu modifié";
        header('location: /detailMenu?='.$menuId);
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /modifierMenu');
        exit;
      }

    }else{
      $this->render('pages/employe/modifierMenu');
    }
  }

  public function modifierStatusMenu()
  {
    $status = (int) $_POST['plat_actif'];
    $menuId = (int) $_GET['id'];

    try{
      $this->menuService->modifierStatusMenu($menuId, $status);
      $_SESSION['succes'] = "Status modifié";
      header('location: /detailMenu?id='.$menuId);
      exit;
    }catch(Exception $e){
      $_SESSION['erreur'] = $e->getMessage();
      header('location: /detailMenu?='.$menuId);
      exit;
    }
  }
}