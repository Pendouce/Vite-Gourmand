<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use App\Repository\MenuRepository;
use App\Repository\PlatRepository;
use App\Repository\RegimeRepository;
use App\Repository\ThemeRepository;
use App\Service\MenuService;

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
    $this->menuService = new MenuService($menuRepository, $regimeRepository, $evenementRepository, $themeRepository, $platRepository);
  }
}