<?php

namespace App\Service;

use App\Entity\Menu;
use App\Exceptions\AccesRefuseException;
use App\Exceptions\LibelleExistantException;
use App\Repository\EvenementRepository;
use App\Repository\MenuRepository;
use App\Repository\PlatRepository;
use App\Repository\RegimeRepository;
use App\Repository\ThemeRepository;
use Exception;

class MenuService
{
  private MenuRepository $menuRepository;
  private RegimeRepository $regimeRepository;
  private EvenementRepository $evenementRepository;
  private ThemeRepository $themeRepository;
  private PlatRepository $platRepository;
  private PlatService $platService;
  private CalculStockService $calculStockService;

  public function __construct(MenuRepository $menuRepository, RegimeRepository $regimeRepository,EvenementRepository $evenementRepository, ThemeRepository $themeRepository, PlatRepository $platRepository, PlatService $platService, CalculStockService $calculStockService)
  {
    $this->menuRepository = $menuRepository;
    $this->regimeRepository = $regimeRepository;
    $this->evenementRepository = $evenementRepository;
    $this->themeRepository = $themeRepository;
    $this->platRepository = $platRepository;
    $this->platService = $platService;
    $this->calculStockService = $calculStockService;
  }

  public function creerMenu(array $data, int $role, array $platsDuMenu)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();
    $this->menuExistant($data['titre']);

    if(empty($platsDuMenu)){
      throw new Exception("Veuillez selectionner au moins un plat");
    }

    $plats = array_map(fn($id) => $this->platRepository->trouverPlatParId($id), $platsDuMenu);

    $stock = array_map(fn($plat) => $plat->getStockPlat(), $plats);
    $data['stock_dispo'] = min($stock);

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
    $this->ajouterElementsMenu($menu);

    return $menu;
  }

  public function afficherMenuParId(int $menuId)
  {
    $menu = $this->menuRepository->trouverMenuParId($menuId);

    if ($menu == false) {
      return false;
    }

    $menus = [$menu];
    $this->ajouterElementsMenu($menus);

    return $menu;
  }

  public function afficherMenuFiltre(array $menuFiltre)
  {
    $menus = $this->menuRepository->trouverMenuFiltre($menuFiltre);
    $this->ajouterElementsMenu($menus);

    return $menus;
  }

  public function afficherEvenements()
  {
    return $this->evenementRepository->trouverEvenement();
  }

  public function afficherThemes()
  {
    return $this->themeRepository->trouverTheme();
  }

  public function afficherRegimes()
  {
    return $this->regimeRepository->trouverRegime();
  }

  public function modifierMenu(int $menuId, array $data, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    if(!empty($data['titre'])){
      $this->menuExistant($data['titre'], $menuId);
    }

    $ancienMenu = $this->afficherMenuParId($menuId);
    $anciennesDonnees = $ancienMenu->deshydrate();

    $data = array_filter($data, fn($value) => $value !== null);

    $nouvellesDonnees = array_merge($anciennesDonnees, $data);
    $nouvellesDonnees['menu_id'] = $menuId;

    unset($nouvellesDonnees['id']);
    unset($nouvellesDonnees['plat']);
    unset($nouvellesDonnees['allergene']);
    unset($nouvellesDonnees['image_menu']);
    unset($nouvellesDonnees['evenement']);
    unset($nouvellesDonnees['regime']);
    unset($nouvellesDonnees['theme']);

    $this->menuRepository->modifierMenu($nouvellesDonnees);
  }

  public function modifierPlatsDuMenu(int $menuId, array $platIds)
  {
    $repo = $this->platRepository;

    $nouveauxPlatIds = array_filter($platIds);

    $this->modifierElementDuMenu(
      $menuId, 
      $nouveauxPlatIds, 
      /* $platIds,*/
      $repo, 
      'trouverPlatDuMenu',
      'getPlatId',
      fn($menuId, $id) => $repo->ajouterPlatAuMenu($menuId, $id),
      fn($menuId, $id) => $repo->supprimerPlatDuMenu($menuId, $id),
      );
  }

  public function modifierEvenementsDuMenu(int $menuId, array $evenementIds)
  {
    $repo = $this->evenementRepository;
    $this->modifierElementDuMenu(
      $menuId, 
      $evenementIds, 
      $repo, 
      'trouverEvenementDuMenu',
      'getEvenementId',
      fn($menuId, $id) => $repo->ajouterEvenementAuMenu($menuId, $id),
      fn($menuId, $id) => $repo->supprimerEvenementDuMenu($menuId, $id),
      );
  }

  public function modifierThemesDuMenu(int $menuId, array $themeIds): void
  {
    $repo = $this->themeRepository;

    $this->modifierElementDuMenu(
        $menuId,
        $themeIds,
        $repo,
        'trouverThemeDuMenu',
        'getThemeId',
        fn($menuId, $id) => $repo->ajouterThemeAuMenu($menuId, $id),
        fn($menuId, $id) => $repo->supprimerThemeDuMenu($menuId, $id)
    );
  }

  public function modifierRegimesDuMenu(int $menuId, array $regimeIds): void
  {
    $repo = $this->regimeRepository;

    $this->modifierElementDuMenu(
        $menuId,
        $regimeIds,
        $repo,
        'trouverRegimeDuMenu',
        'getRegimeId',
        fn($menuId, $id) => $repo->ajouterRegimeAuMenu($menuId, $id),
        fn($menuId, $id) => $repo->supprimerRegimeDuMenu($menuId, $id)
    );
  }

  public function modifierStatusMenu(int $menuId, int $status, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    $this->menuRepository->modifierStatusMenu($menuId, $status);
  }

  public function supprimerMenu(int $menuId, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    $this->platRepository->supprimerMenu($menuId);
    $this->evenementRepository->supprimerMenu($menuId);
    $this->regimeRepository->supprimerMenu($menuId);
    $this->themeRepository->supprimerMenu($menuId);
    $this->menuRepository->supprimerMenu($menuId);
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
  
  private function ajouterElementsMenu(array $menus)
  {
    $this->ajouterPlat($menus);
    $this->ajouterAllergene($menus);

    $this->ajouterRegime($menus);
    $this->ajouterTheme($menus);
    $this->ajouterEvenement($menus);
    $this->ajouterStock($menus);
    $this->ajouterImage($menus);

    return $menus;
  }

  private function menuExistant(string $nomMenu, ?int $id = null)
  {
    $menu = $this->menuRepository->trouverMenuParNom($nomMenu);

    if($menu && $menu->getMenuId() !== $id){
      throw new LibelleExistantException($nomMenu);
    }
  }

  private function modifierElementDuMenu(int $menuId, array $nouveauxId, object $repo, string $mehodeTrouver, string $getId, callable $ajouter, callable $supprimer)
  {
    $nvxElements = $nouveauxId;
    $anciensElement = $repo->$mehodeTrouver($menuId);
    $anciensElementId = array_map(fn($element) => $element->$getId(), $anciensElement);
    $aSupprimer = array_diff($anciensElementId, $nvxElements);
    $aAjouter = array_diff($nvxElements, $anciensElementId);

    foreach($aSupprimer as $id){
      $supprimer($menuId, $id);
    }
    foreach($aAjouter as $id){
      $ajouter($menuId, $id);
    }
  }

  
  public function stockePlatEtMenu(int $menuId, int $nbPersonneMenu, bool $increment) : void
  {
    $plats = $this->platRepository->trouverPlatDuMenu($menuId);

    foreach($plats as $plat){
      if($increment == true){
      $stockPlat = $this->calculStockService->calculerRetourStockPlat($plat->getStockPlat(), $nbPersonneMenu);
      }else{
        $stockPlat = $this->calculStockService->calculerStockPlat($plat->getStockPlat(), $nbPersonneMenu);
      }
      $this->platRepository->modifierStockPlat($plat->getPlatId(), $stockPlat);
    
      $menuContenantPlats = $this->menuRepository->trouverMenuDePlat($plat->getPlatId());

      foreach($menuContenantPlats as $menu){
        $platDuMenu = $this->platRepository->trouverPlatDuMenu($menu->getMenuId());
        $stockPlats = array_map(fn ($sp) => $sp->getStockPlat(), $platDuMenu);
        $stockMenu = min($stockPlats);
        $this->menuRepository->modifierStockMenu($menu->getMenuId(), $stockMenu);
      }
    }
  }


  public function verifStockDispo(int $menuId, int $nbPersonneMenu): void
  {
    $platsDuMenu = $this->platRepository->trouverPlatDuMenu($menuId);
    $stocksPlats = array_map(fn($plat) => $plat->getStockPlat(), $platsDuMenu);
    $this->calculStockService->calculerStockMenu($stocksPlats, $nbPersonneMenu);
  }

}