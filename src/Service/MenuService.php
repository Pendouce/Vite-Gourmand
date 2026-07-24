<?php

namespace App\Service;

use App\Exceptions\LibelleExistantException;
use App\Repository\EvenementRepository;
use App\Repository\MenuRepository;
use App\Repository\PlatRepository;
use App\Repository\RegimeRepository;
use App\Repository\ThemeRepository;
use App\Entity\Menu;


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
      $plat = $this->platRepository->trouverPlatDuMenu($menuId);
      $menu->setPlat($plat);
    }
    return $menus;
  }

  private function ajouterAllergene(array $menus)
  {
    foreach($menus as $menu){
      $plats = $menu->getPlat();
      $plats = $this->platService->ajouterAllergenes($plats);
      $allergene = $this->fusionnerAllergenes($plats);
      $menu->setPlat($plats);
      $menu->setAllergene($allergene);
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
    $this->trouverleStockMinDesPlats($menu);
  }
  
  return $menus;
}

private function ajouterImage(array $menus)
{
  foreach($menus as $menu){
   $this->trouverImagePlatPrincipale($menu);
  }

  return $menus;
}

public function afficherMenuParId(int $menuId)
{
  $menu = $this->menuRepository->trouverMenuParId($menuId);

  if ($menu == false) {
    return false;
  }

  $plat = $this->platRepository->trouverPlatDuMenu($menuId);
  $plat = $this->platService->ajouterAllergenes($plat);
  $allergene = $this->fusionnerAllergenes($plat);
  $menu->setPlat($plat);
  $menu->setAllergene($allergene);
  $evenement = $this->evenementRepository->trouverEvenementDuMenu($menuId);
  $menu->setEvenement($evenement);
  $regime = $this->regimeRepository->trouverRegimeDuMenu($menuId);
  $menu->setRegime($regime);
  $theme = $this->themeRepository->trouverThemeDuMenu($menuId);
  $menu->setTheme($theme);
  $this->trouverImagePlatPrincipale($menu);
  $this->trouverleStockMinDesPlats($menu);

  return $menu;
}

private function trouverImagePlatPrincipale(Menu $menu)
{
  $plats = $menu->getPlat();

  foreach($plats as $plat){
      if($plat->getTypeId() === 2){
        $menu->setImageMenu($plat->getImagePlat());
        break;
      }
    }
}

private function trouverleStockMinDesPlats(Menu $menu)
{
    $plats = $menu->getPlat();
    
    // Pour chaques plats j'appelle le stock dispo
    $stocks = array_map(function($plat) {
        return $plat->getStockPlat();
    }, $plats);
    
    $stockMenu = min($stocks);

     // Je stocke le resultat dans le menu
    $menu->setStockDispo($stockMenu);
  
  return $menu;
}


  // Je transforme allergene en tableau associatif
  // allergene = [$allergene => getAllergeneId()]
private function fusionnerAllergenes(array $plats)
{
  $tousLesAllergenes = [];
  foreach ($plats as $plat) {
    foreach ($plat->getAllergenes() as $allergene) {
      $tousLesAllergenes[$allergene->getAllergeneId()] = $allergene;
    }
  }

  return $tousLesAllergenes;
}

  private function menuExistant(string $menu)
  {
    if($this->menuRepository->trouverMenuParNom($menu)){
      throw new LibelleExistantException($menu);
    }
  }


}