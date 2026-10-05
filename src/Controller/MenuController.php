<?php

namespace App\Controller;

use App\Factory\ContainerId;
use App\Service\MenuService;
use App\Service\PlatService;
use App\Service\TypeDePlatService;
use Exception;
class MenuController extends Controller
{
  private MenuService $menuService;
  private PlatService $platService;
  private TypeDePlatService $typeDePlatService;

  public function __construct() {
    parent::__construct();
    $this->menuService = ContainerId::getMenuService();
    $this->platService = ContainerId::getPlatService();
    $this->typeDePlatService = ContainerId::getTypeDePlatService();
  }

  public function creerMenu()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $this->checkCsrfToken();
    
      $data = [
        'titre' => $_POST['titre'],
        'prix_personne' => $_POST['prix_personne'],
        'nombre_personne_min' => $_POST['nombre_personne_min'],
        'conditions' => $_POST['conditions'],
        // A gerer dans le service
        'menu_actif' => isset($_POST['menu_actif']) ? 1 : 0,
      ];
      //$data['stock_dispo'] = $_GET['stock_dispo'];
      $data = $this->nettoyerDonnees($data);

      $role = $_SESSION['role_id'];

      try{
        $platId = array_filter($_POST['plat'], fn($id) => $id !== "");
        //$allergeneId = $_POST['allergene'];
        $evenementId = $_POST['evenement'] ?? [];
        $themeId = $_POST['theme'] ?? [];
        $regimeId = $_POST['regime'] ?? [];
        $menuCreer = $this->menuService->creerMenu($data, $role, $platId);
        $menuId = $menuCreer->getMenuId();
        //var_dump($menuId);
        $this->menuService->ajouterPlatAuMenu($menuId, $platId);
        //$this->menuService->ajouterAllergeneAuplat($platId, $allergeneId);
        if(!empty($evenementId)){
          $this->menuService->ajouterEvenementAuMenu($menuId, $evenementId);
        }
        if(!empty($themeId)){
          $this->menuService->ajouterThemeAuMenu($menuId, $themeId);
        }
        if(!empty($regimeId)){
          $this->menuService->ajouterRegimeAuMenu($menuId, $regimeId);
        }

        $_SESSION['succes'] = "Menu ajouté";
        header('location: /menu');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /creerMenu');
        exit;
      }

    }else{
      $role = $_SESSION['role_id'];
      $platsParType = $this->platService->afficherPlatsParType($role);
      $typeDePlats = $this->typeDePlatService->afficheTypeDePlat();
      $plats = $this->platService->afficherPlats($role);
      $evenements = $this->menuService->afficherEvenements();
      $themes = $this->menuService->afficherThemes();
      $regimes = $this->menuService->afficherRegimes();

      $this->render('pages/employe/creerMenu', ['plats' => $plats, 'platsParType' => $platsParType, 'typeDePlats' => $typeDePlats, 'evenements' => $evenements, 'themes' => $themes, 'regimes' => $regimes, 'titre' => 'creer un menu']);
    }
  }

  public function afficherMenus()
  {
    $role = $_SESSION['role_id'] ?? null;
    $menus = $this->menuService->afficherMenus();

    $this->render('pages/employe/menu', ['menus' => $menus, 'role' => $role, 'titre' => 'menus']);
  }

  public function afficherDetailMenu()
  {
    $menuId = $_GET['id'];
    $role = $_SESSION['role_id'] ?? null;
    $menu = $this->menuService->afficherMenuParId($menuId);

    $this->render('pages/employe/detailMenu', ['menu' => $menu,'role' => $role, 'titre' => 'detail menu']);
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
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->checkCsrfToken();
    
      $data = [
        'titre' => $_POST['titre'] ?? null,
        'prix_personne' => $_POST['prix_personne'] ?? null,
        'nombre_personne_min' => $_POST['nombre_personne_min'] ?? null,
        'conditions' => $_POST['conditions'] ?? null,
        'menu_actif' => isset($_POST['menu_actif']) ? 1 : 0,
      ];

      $data = $this->nettoyerDonnees($data);

      $role = $_SESSION['role_id'];

      try{
        $menuId = (int) $_POST['id'];
        $platIds = $_POST['plat'] ?? [];
        //$allergeneId = $_POST['allergene'];
        $evenementIds = $_POST['evenement'] ?? [];
        $themeIds = $_POST['theme'] ?? [];
        $regimeIds = $_POST['regime'] ?? [];
        
        $this->menuService->modifierMenu($menuId, $data, $platIds, $evenementIds, $themeIds, $regimeIds, $role);
        /* $this->menuService->modifierPlatsDuMenu($menuId, $platId);
        //$this->menuService->ajouterAllergeneAuplat($platId, $allergeneId);
        $this->menuService->modifierEvenementsDuMenu($menuId, $evenementId);
        $this->menuService->modifierThemesDuMenu($menuId, $themeId);
        $this->menuService->modifierRegimesDuMenu($menuId, $regimeId); */

        $_SESSION['succes'] = "Menu modifié";
        header('location: /detailMenu?id='.$menuId);
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /modifierMenu?id='.$_GET['id']);
        exit;
      }

    }else{
      $role = $_SESSION['role_id'];
      $platsParType = $this->platService->afficherPlatsParType($role);
      $typeDePlats = $this->typeDePlatService->afficheTypeDePlat();
      $plats = $this->platService->afficherPlats($role);
      $evenements = $this->menuService->afficherEvenements();
      $themes = $this->menuService->afficherThemes();
      $regimes = $this->menuService->afficherRegimes();

      $menu = $this->menuService->afficherMenuParId($_GET['id']);
      $platsDuMenu = array_map(fn($p)=> $p->getPlatId(), $menu->getPlat());
      $evenementsDuMenu = array_map(fn($e)=> $e->getEvenementId(), $menu->getEvenement());
      $regimesDuMenu = array_map(fn($e)=> $e->getRegimeId(), $menu->getRegime());
      $themesDuMenu = array_map(fn($e)=> $e->getThemeId(), $menu->getTheme());

      $this->render('pages/employe/modifierMenu', ['menu' => $menu, 'plats' => $plats, 'platsDuMenu' => $platsDuMenu, 
      'regimesDuMenu' => $regimesDuMenu, 'themesDuMenu' => $themesDuMenu, 'platsParType' => $platsParType, 
      'typeDePlats' => $typeDePlats, 'evenements' => $evenements, 'themes' => $themes, 'regimes' => $regimes, 
      'evenementsDuMenu' => $evenementsDuMenu, 'titre' => 'Modification du menu']);
    }
  }

  public function modifierStatusMenu()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();
    
    $statut = (int) $_POST['menu_actif'];
    $menuId = (int) $_POST['id'];
    $role = $_SESSION['role_id'];

    try{
      $this->menuService->modifierStatusMenu($menuId, $statut, $role);
      echo json_encode(['succes' => true, 'message' => "Status modifié", 'statut' => $statut]);
    }catch(Exception $e){
      echo json_encode(['succes' => false, 'message' => $e->getMessage()]);
    }
  }

  public function supprimerMenu()
  {
    $this->accesPage([ROLE_ADMIN, ROLE_EMPLOYE]);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();
    
    $menuId = (int) $_POST['id'];
    $role = $_SESSION['role_id'];

    try{
      $this->menuService->supprimermenu($menuId, $role);
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