<?php

namespace App\Service;

use App\Exceptions\LibelleExistantException;
use App\Repository\EvenementRepository;
use App\Repository\MenuRepository;
use App\Repository\PlatRepository;
use App\Repository\RegimeRepository;
use App\Repository\ThemeRepository;

class MenuService
{
  private MenuRepository $menuRepository;
  private RegimeRepository $regimeRepository;
  private EvenementRepository $evenementRepository;
  private ThemeRepository $themeRepository;
  private PlatRepository $platRepository;
  private PlatService $platService;

  public function __construct(MenuRepository $menuRepository, RegimeRepository $regimeRepository,EvenementRepository $evenementRepository, ThemeRepository $themeRepository, PlatRepository $platRepository, PlatService $platService)
  {
    $this->menuRepository = $menuRepository;
    $this->regimeRepository = $regimeRepository;
    $this->evenementRepository = $evenementRepository;
    $this->themeRepository = $themeRepository;
    $this->platRepository = $platRepository;
    $this->platService = $platService;
  }

  public function creerMenu(array $data)
  {
    $this->menuExistant($data['titre']);

    return $this->menuRepository->creerMenu($data);
  }

  public function ajouterEvenementAuMenu(int $menuId, array $evenementsId)
  {
    foreach($evenementsId as $evenement){
      $this->evenementRepository->ajouterEvenementAuMenu($menuId, $evenement);
    }
  }

  public function ajouterPlatAuMenu(int $menuId, array $platsId)
  {
    foreach($platsId as $plat){
      $this->platRepository->ajouterPlatAuMenu($menuId, $plat);
    }
  }

  public function ajouterRegimeAuMenu(int $menuId, array $regimesId)
  {
    foreach($regimesId as $regime){
      $this->regimeRepository->ajouterRegimeAuMenu($menuId, $regime);
    }
  }

  public function ajouterThemeAuMenu(int $menuId, array $themesId)
  {
    foreach($themesId as $theme){
      $this->themeRepository->ajouterThemeAuMenu($menuId, $theme);
    }
  }

  public function afficherMenus()
  {
    $menu = $this->menuRepository->trouverMenu();
    $this->ajouterPlat($menu);
    $this->ajouterAllergene($menu);
    //var_dump($menu[0]->getPlat());
    $this->ajouterRegime($menu);
    $this->ajouterTheme($menu);
    $this->ajouterEvenement($menu);
    $this->ajouterStock($menu);
    $this->ajouterImage($menu);

    return $menu;
  }

  private function ajouterPlat(array $menus)
  {
    foreach($menus as $menu){
      $menuId = $menu->getMenuId();
      //var_dump($menuId);
      $plat = $this->platRepository->trouverPlatDuMenu($menuId);
      //var_dump($plat);
      $menu->setPlat($plat);
    }
    return $menus;
  }

  private function ajouterAllergene(array $menus)
  {
    foreach($menus as $menu){
      $plats = $menu->getplat();
      //var_dump($menuId);
      $plats = $this->platService->ajouterAllergenes($plats);
      //var_dump($plat);
      $menu->setPlat($plats);
    }
    return $menus;
  }

  private function ajouterRegime(array $menus)
  {
    foreach($menus as $menu){
      $menuId = $menu->getMenuId();
      $regime = $this->regimeRepository->trouverRegimeDuMenu($menuId);
      $menu->setRegime($regime);
    }
    return $menus;
  }

  private function ajouterTheme(array $menus)
  {
    foreach($menus as $menu){
      $menuId = $menu->getMenuId();
      $theme = $this->themeRepository->trouverThemeDuMenu($menuId);
      $menu->setTheme($theme);
    }
    return $menus;
  }

  private function ajouterEvenement(array $menus)
  {
    foreach($menus as $menu){
      $menuId = $menu->getMenuId();
      $evenement = $this->evenementRepository->trouverEvenementDuMenu($menuId);
      $menu->setEvenement($evenement);
    }
    return $menus;
  }

  private function ajouterStock(array $menus)
{
  foreach($menus as $menu){
    $plats = $menu->getPlat();
    
    // Pour chaques plats j'appelle le stock dispo
    $stocks = array_map(function($plat) {
        return $plat->getStockPlat();
    }, $plats);
    
    $stockMenu = min($stocks);

     // Je stocke le resultat dans le menu
    $menu->setStockDispo($stockMenu);
  }
  return $menus;
}

private function ajouterImage(array $menus)
{
  foreach($menus as $menu){
    $plats = $menu->getPlat();
    
    foreach($plats as $plat){
      if($plat->getTypeId() === 2){
        $menu->setImageMenu($plat->getImagePlat());
        break;
      }
    }
  }
  return $menus;
}



  private function menuExistant(string $menu)
  {
    if($this->menuRepository->trouverMenuParNom($menu)){
      throw new LibelleExistantException($menu);
    }
  }


}