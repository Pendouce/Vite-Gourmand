<?php

namespace App\Controller;

use App\Factory\ContainerId;
use App\Service\MenuService;
use Exception;
class MenuController extends Controller
{
  private MenuService $menuService;

  public function __construct() {
    parent::__construct();
    $this->menuService = ContainerId::getMenuService();
  }

  public function creerMenu()
  {
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $this->checkCsrfToken();
    
      $data = [
        'titre' => $_POST['titre'],
        'prix_personne' => $_POST['prix_personne'],
        'nombre_personne_min' => $_POST['nombre_personne_min'],
        'conditions' => $_POST['conditions'],
        // A gerer dans le service
        //'stock_dispo' => $_GET['stock_dispo'],
        //'menu_actif' => $_POST['menu_actif'],
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
      $this->checkCsrfToken();
    
      $data = [
        'titre' => $_POST['titre'] ?? null,
        'prix_personne' => $_POST['prix_personne'] ?? null,
        'nombre_personne_min' => $_POST['nombre_personne_min'] ?? null,
        'conditions' => $_POST['conditions'] ?? null,
        'menu_actif' => $_POST['menu_actif'] ?? null,
      ];

      $data = $this->nettoyerDonnees($data);

      try{
        $menuId = (int) $_POST['id'];
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
        header('location: /detailMenu?id='.$menuId);
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
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();
    
    $status = (int) $_POST['menu_actif'];
    $menuId = (int) $_POST['id'];

    try{
      $this->menuService->modifierStatusMenu($menuId, $status);
      $_SESSION['succes'] = "Status modifié";
      header('location: /detailMenu?id='.$menuId);
      exit;
    }catch(Exception $e){
      $_SESSION['erreur'] = $e->getMessage();
      header('location: /detailMenu?id='.$menuId);
      exit;
    }
  }

  public function supprimerMenu()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();
    
    $menuId = (int) $_POST['id'];

    try{
      $this->menuService->supprimermenu($menuId);
      $_SESSION['succes'] = "Le menu a bien ete supprimé";
      header('location: /menu');
      exit;
    }catch(Exception $e){
      $_SESSION['erreur'] = $e->getMessage();
      header('location: /menu');
      exit;
    }
  }
}