<?php

namespace App\Service;

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

  public function __construct(MenuRepository $menuRepository, RegimeRepository $regimeRepository,EvenementRepository $evenementRepository, ThemeRepository $themeRepository, PlatRepository $platRepository)
  {
    $this->menuRepository = $menuRepository;
    $this->regimeRepository = $regimeRepository;
    $this->evenementRepository = $evenementRepository;
    $this->themeRepository = $themeRepository;
    $this->platRepository = $platRepository;
  }
}